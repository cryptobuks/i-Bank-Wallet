<?php declare(strict_types=1);

namespace Wallet\Controller\XRP\Api\Method;

use Wallet\Controller\XRP\Api\Field;

/**
 * ServerInfo Method Class
 *
 * The server_info command asks the server for a human-readable version of various information about the rippled server
 * being queried.
 *
 * @link https://developers.ripple.com/server_info.html ServerInfo method documentation.
 */
class ServerInfo extends AbstractMethod
{
    /**
     * {@inheritDoc}
     *
     * @throws \Wallet\Controller\XRP\Exception\FieldException
     */
    public function setFields()
    {
        parent::setFields();

        // GENERATED CODE FROM bin/generate.php types
        // BEGIN GENERATED
        // END GENERATED
    }
}
