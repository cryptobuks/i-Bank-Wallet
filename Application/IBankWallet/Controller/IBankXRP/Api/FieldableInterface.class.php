<?php declare(strict_types=1);

namespace Wallet\Controller\XRP\Api;

/**
 * Fieldable provides methods to build fieldable objects which can be validated.
 */
interface FieldableInterface
{
    public function __construct(array $params = null);
    public function setFields();
    public function validateParams(array $params);
    public function getRequiredFields();
    public function getFields();
    public function addField(Field $field);
    public function getField($name);
}
