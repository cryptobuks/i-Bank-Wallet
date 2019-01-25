<?php declare(strict_types=1);

namespace Wallet\Controller\XRP\Api;

use Wallet\Controller\XRP\Exception\FieldException;

/**
 * Field.
 */
class Field
{
    /** @var string */
    private $name;

    /** @var string */
    private $description;

    /** @var string */
    private $jsonType = 'String';

    /** @var string */
    private $internalType = 'String';

    /** @var bool */
    private $required = false;

    /**
     * Field constructor.
     * @param array $params
     * @throws FieldException
     */
    public function __construct(array $params)
    {
        if (!isset($params['name'])) {
            throw new FieldException(sprintf('Missing parameter: %s', 'name'));
        }

        foreach ($params as $key => $val) {
            switch ($key) {
            case 'name':
                $this->setName($val);
                break;
            case 'description':
                $this->setDescription($val);
                break;
            case 'jsonType':
                $this->setJsonType($val);
                break;
            case 'internalType':
                $this->setInternalType($val);
                break;
            case 'required':
                $this->setRequired($val);
                break;
            }
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getJsonType()
    {
        return $this->jsonType;
    }

    /**
     * @param string $jsonType
     */
    public function setJsonType($jsonType)
    {
        $this->jsonType = $jsonType;
    }

    /**
     * @return string
     */
    public function getInternalType()
    {
        return $this->internalType;
    }

    /**
     * @param string $internalType
     */
    public function setInternalType($internalType)
    {
        $this->internalType = $internalType;
    }

    /**
     * @return bool
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * @param bool $required
     */
    public function setRequired($required)
    {
        $this->required = $required;
    }
}
