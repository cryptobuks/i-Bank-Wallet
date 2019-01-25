<?php declare(strict_types=1);

namespace Wallet\Controller\XRP\Api;

use Wallet\Controller\XRP\Client;

class Request
{
    /** @var Client */
    private $client;

    /** @var FieldableInterface */
    private $method;

    /** @var string */
    private $methodName;

    /** @var array */
    private $params;

    /**
     * Request constructor.
     * @param string $methodName
     * @param array $params
     * @param Client|null $client
     * @throws \Exception
     */
    public function __construct($methodName, array $params, Client $client = null)
    {
        $this->setMethod($methodName, $params);

        if ($client !== null) {
            $this->setClient($client);
        }
    }

    /**
     * @return Response
     * @throws \Exception
     */
    public function send()
    {
        return new Response($this->client->post($this->methodName, $this->params));
    }

    /**
     * Returns class path from API snake cased method name.
     *
     * @param string $methodName Snake cased method name.
     * @return string Class path to the method.
     * @throws \Exception
     */
    private function findClass($methodName)
    {
        $class = '\\Wallet\Controller\XRP\\Api\\Method\\' . \Wallet\Controller\XRP\Util::CaseFromSnake($methodName);
        if (!class_exists($class)) {
            throw new \Exception(sprintf('No class found for method: %s', $methodName));
        }
        return $class;
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
    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return FieldableInterface
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $methodName
     * @param array|null $params
     * @throws \Exception
     */
    public function setMethod($methodName, array $params = null)
    {
        $this->methodName = $methodName;
        $this->params = $params;

        $methodClass = $this->findClass($methodName);
        $this->method = new $methodClass($params);
    }

    /**
     * @return string
     */
    public function getMethodName()
    {
        return $this->methodName;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }
}
