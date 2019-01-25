<?php declare(strict_types=1);

namespace Wallet\Controller\XRP;
require('vendor/autoload.php');

use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\MessageFactory;
use Wallet\Controller\XRP\Api\Response;
use Wallet\Controller\XRP\Api\Request;

/**
 *  A rippled client.
 */
class Client
{
    /** @var HttpClient */
    private $httpClient;

    /** @var string */
    private $endpoint;

    /** @var string Hostname or IP of the endpoint */
    private $host;

    /** @var MessageFactory */
    protected $messageFactory;

    /** @var int Port of the endpoint */
    private $port;

    /** @var string http or https */
    private $scheme;

    /**
     * Connection constructor.
     * @param $config
     * @param HttpClient|null $httpClient
     */
    public function __construct($config, HttpClient $httpClient = null, MessageFactory $messageFactory = null)
    {
        $type = gettype($config);

        switch ($type) {
        case 'string':
            $this->setEndpoint($config);
            break;

        case 'array':
            $this->setEndpoint($config['endpoint'] ? $config['endpoint'] : null);
            break;

        default:
            throw new \InvalidArgumentException('Constructor argument must be endpoint string or config array');

        }

        if ($this->endpoint !== null) {
            $parts = parse_url($this->endpoint);
            if ($parts === false || !isset($parts['scheme'], $parts['host'], $parts['port'])) {
                throw new \InvalidArgumentException('Invalid endpoint format');
            }

            $this->scheme = $parts['scheme'];
            $this->setHost($parts['host']);
            $this->setPort($parts['port'] ? $parts['port'] : null);
        } else {
            $this->setScheme($config['scheme'] ? $config['scheme'] : null);
            $this->setHost($config['host'] ? $config['host'] : null);
            $this->setPort($config['port'] ? $config['port'] : null);
        }

        if (empty($this->scheme)) {
            throw new \InvalidArgumentException('scheme is required if endpoint is not supplied');
        }

        // Validate protocol
        if (!\in_array($this->scheme, ['http', 'https'])) {
            throw new \OutOfBoundsException('scheme must be http or https');
        }

        if (empty($this->host)) {
            throw new \InvalidArgumentException('host is required if endpoint is not supplied');
        }

        // Auto set port based on protocol if it has not been passed in
        $port = $this->getPort() ? $this->getPort() : ($this->getScheme() === 'https' ? 443 : 80);

        // Validate port
        if ($port < 1 || $port >= 65535) {
            throw new \OutOfBoundsException('port must be between 1 and 65535');
        }

        $this->port = $port;

        // Setup HttpClient & messageFactory
        $this->setHttpClient($httpClient ?: HttpClientDiscovery::find());
        $this->setMessageFactory($messageFactory ?: MessageFactoryDiscovery::find());

        // Build endpoint
        if (empty($this->endpoint)) {
            $this->setEndpoint($this->getScheme() . '://' . $this->getHost() . ':' . $this->getPort());
        }
    }

    /**
     * @param string $methodName
     * @param array $params
     * @return Request
     * @throws \Exception
     */
    public function request($methodName, array $params)
    {
        return new Request($methodName, $params, $this);
    }

    /**
     * @param string $methodName
     * @param array $params
     * @return Response
     * @throws \Exception
     */
    public function send($methodName, array $params = [])
    {
        return $this->request($methodName, $params)->send();
    }

    /**
     * @param string $method
     * @param array|null $params
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Http\Client\Exception
     */
    public function post($method, array $params = null)
    {
        $json = $this->prepareJson($method, $params);

        $request = $this->getMessageFactory()->createRequest(
            'POST',
            $this->endpoint,
            ['Content-Type' => 'application/json'],
            $json
        );

        return $this->httpClient->sendRequest($request);
    }

    /**
     * @param string $method The API method string.
     * @param array|null $params Associative array of method parameters.
     * @return string Raw JSON formatted to send in the API body.
     */
    public function prepareJson($method, array $params = null)
    {
        if ($params === null) {
            $params = new \stdClass();
        }
        $request = ['method' => $method, 'params' => []];
        $request['params'][] = $params;
        return json_encode($request);
    }

    public function getHttpClient()
    {
        return $this->httpClient;
    }

    public function setHttpClient(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getMessageFactory()
    {
        return $this->messageFactory;
    }

    public function setMessageFactory(MessageFactory $messageFactory)
    {
        $this->messageFactory = $messageFactory;
    }

    public function getEndpoint()
    {
        return $this->endpoint;
    }

    public function setEndpoint($endpoint = null)
    {
        $this->endpoint = $endpoint;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function setHost($host = null)
    {
        $this->host = $host;
    }

    public function getPort()
    {
        return $this->port;
    }

    public function setPort($port = null)
    {
        $this->port = $port;
    }

    public function getScheme()
    {
        return $this->scheme;
    }

    public function setScheme($scheme = null)
    {
        $this->scheme = $scheme;
    }
}
