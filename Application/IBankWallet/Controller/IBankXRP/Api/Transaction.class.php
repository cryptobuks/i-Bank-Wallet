<?php declare(strict_types=1);

namespace Wallet\Controller\XRP\Api;

use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Process\Process;
use Wallet\Controller\XRP\Client;
use Wallet\Controller\XRP\Exception\TransactionException;
use Wallet\Controller\XRP\Exception\TransactionSignException;
use Wallet\Controller\XRP\Exception\TransactionTypeException;

class Transaction
{
    /** @var Client */
    private $client;

    /** @var string */
    private $json;

    /** @var bool */
    private $signed;

    /** @var FieldableInterface */
    private $type;

    /** @var array */
    private $tx;

    /** @var string */
    private $txBlob;

    /** @var string */
    private $txId;

    /**
     * Transaction constructor.
     * @param array|string $tx A transaction represented by JSON or an associative array.
     * @param Client|null $client
     * @throws TransactionException
     * @throws TransactionTypeException
     */
    public function __construct($tx, Client $client = null)
    {
        // Dynamically set tx & json based on type.
        if (\is_array($tx)) {
            $this->setTx($tx);
        } elseif (\is_string($tx)) {
            $this->setJson($tx);
        } else {
            throw new TransactionException('Invalid tx passed into the constructor; Must be a string or array');
        }

        if ($client !== null) {
            $this->setClient($client);
        }
    }

    /**
     * @param string $type
     * @return string
     * @throws TransactionTypeException
     */
    public function findClass($type)
    {
        $class = '\\Wallet\Controller\XRP\\Api\\TransactionType\\' . $type;
        if (!class_exists($class)) {
            throw new TransactionTypeException(sprintf('No class found for transaction type %s', $type));
        }
        return $class;
    }

    /**
     * @param string $secret
     * @throws TransactionSignException
     * @throws TransactionTypeException
     * @throws TransactionException
     */
    public function signLocal($secret)
    {
        // Auto-fillable fields are required before signing.
        $tx = $this->getTx();

        // Set sequence if it does not exist.
        if (!isset($tx['Sequence']) || $tx['Sequence'] < 1) {
            $tx['Sequence'] = $this->getAccountSequence($tx['Account']);
            $this->setTx($tx);
        }

        $process = new Process(['xrpsign', $this->getJson(), $secret]);

        try {
            $process->run();
            if (!$process->isSuccessful()) {
                $response['stderr'] = trim($process->getErrorOutput());
                throw new TransactionSignException(sprintf('Unable to sign transaction: %s', $process->getErrorOutput()));
            }

            $json = trim($process->getOutput());
            $data = json_decode($json, true);
            if (json_last_error()!== JSON_ERROR_NONE) {
                throw new TransactionSignException('Unable to parse output of xrpsign command');
            }

            $this->setTx($data['tx']);
            $this->setTxBlob($data['tx_blob']);
            $this->setTxId($data['tx_id']);
            $this->setSigned(true);
        } catch (\Exception $e) {
            throw new TransactionSignException('Unable to sign transaction: ' . $e->getMessage());
        }
    }

    /**
     * Retrieves the current account sequence.
     *
     * @param string $account Account id.
     * @return int Account sequence.
     * @throws TransactionSignException
     */
    public function getAccountSequence($account)
    {
        $client = $this->getClient();
        if ($client === null) {
            throw new TransactionSignException('Client must be present if Sequence paramter does not exist');
        }

        $params = [
            'account' => $account,
        ];

        $res = $client->send('account_info', $params);
        return $res->getResult()['account_data']['Sequence'];
    }

    /**
     * Submit this transaction, signed or unsigned.
     *
     * @param string $secret Regular key.
     * @param bool   $signLocal Sign locally.
     * @return Response|null
     * @throws TransactionException
     * @throws \Exception
     * @throws \Http\Client\Exception
     */
    public function submit($secret, bool $signLocal = true)
    {
        if ($this->getClient() === null) {
            throw new TransactionException('Transaction must have a Client to submit');
        }

        $res = null;
        if ($signLocal) {
            $this->signLocal($secret);

            $txBlob = $this->getTxBlob();
            if ($txBlob === null) {
                throw new TransactionException('Local sign was unsuccessful.');
            }

            // Submit signed transaction.
            $res = $this->getClient()->send('submit', [
                'tx_blob' => $txBlob
            ]);
        } else {
            // Submit unsigned transaction with secret.
            $res = $this->getClient()->send('submit', [
                'tx_json' => $this->getTx(),
                'secret' => $secret
            ]);
        }

        return $res;
    }

    /**
     * @return string
     */
    public function getJson()
    {
        return $this->json;
    }

    /**
     * @param string $json
     * @throws TransactionException
     * @throws TransactionTypeException
     */
    public function setJson($json)
    {
        $tx = json_decode($json, true);
        if (json_last_error()!== JSON_ERROR_NONE) {
            throw new TransactionException('Invalid JSON passed into the constructor');
        }

        $this->json = $json;
        $this->tx = $tx;
        $this->setType();
    }

    /**
     * @return bool
     */
    public function isSigned()
    {
        return $this->signed;
    }

    /**
     * @param bool $signed
     */
    public function setSigned( $signed)
    {
        $this->signed = $signed;
    }

    /**
     * @return array
     */
    public function getTx()
    {
        return $this->tx;
    }

    /**
     * @param array $tx
     * @throws TransactionException
     * @throws TransactionTypeException
     */
    public function setTx(array $tx)
    {
        $this->tx = $tx;
        $this->json = json_encode($tx);
        $this->setType();
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @throws TransactionException
     * @throws TransactionTypeException
     */
    public function setType()
    {
        $tx = $this->getTx();
        if (!isset($tx['TransactionType'])) {
            throw new TransactionException('TransactionType is required');
        }

        $txType = $tx['TransactionType'];
        $class = $this->findClass($txType);
        $this->type = new $class($tx);
    }

    /**
     * @return string
     */
    public function getTxBlob()
    {
        return $this->txBlob;
    }

    /**
     * @param string $txBlob
     */
    public function setTxBlob($txBlob = null)
    {
        $this->txBlob = $txBlob;
    }

    /**
     * @return string
     */
    public function getTxId()
    {
        return $this->txId;
    }

    /**
     * @param string $txId
     */
    public function setTxId($txId = null)
    {
        $this->txId = $txId;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param Client $client
     */
    public function setClient(Client $client = null)
    {
        $this->client = $client;
    }
}
