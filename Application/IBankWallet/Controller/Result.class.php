<?php declare(strict_types=1);

namespace IBankWallet\Controller;


class Result
{
    /** @var array */
    public $data;

    /** @var int */
    public $errorCode;

    /** @var bool True when result status is success */
    private $success;

    /** @var string */
    public $errorMessage;

    /**
     * MethodResponse constructor.
     *
     * @param ResponseInterface $response
     * @throws \Exception
     */
    public function __construct($errCode, $errMsg, $data)
    {
        $this->setErrorCode($errCode);
        $this->setErrorMessage($errMsg);
        $this->setData($data);
        if ($errCode == 0 ) {
            $this->success = true;
        } else {
            $this->success = false;
        }
    }

    /**
     * @return string
     */
    public function getRaw()
    {
        return $this->raw;
    }

    /**
     * @param string $raw
     */
    public function setRaw($raw)
    {
        $this->raw = json_encode($raw);
    }

    /**
     * @return int
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @param int $errorCode
     */
    public function setErrorCode($errorCode = null)
    {
        $this->errorCode = $errorCode;
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @param string $errorMessage
     */
    public function setErrorMessage($errorMessage = null)
    {
        $this->errorMessage = $errorMessage;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->data= $data;
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return $this->success;
    }
}
