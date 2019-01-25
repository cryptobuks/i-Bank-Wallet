<?php declare(strict_types=1);

namespace IBankWallet\Controller\IBankXRP\Api\Method;

use IBankWallet\Controller\IBankXRP\Api\Field;

/**
 * ServerState Method Class
 *
 * The server_state command asks the server for various machine-readable information about the rippled server's current
 * state. The response is almost the same as the server_info method, but uses units that are easier to process instead
 * of easier to read. (For example, IBankXRP values are given in integer drops instead of scientific notation or decimal
 * values, and time is given in milliseconds instead of seconds.)
 *
 * @link https://developers.ripple.com/server_state.html ServerState method documentation.
 */
class ServerState extends AbstractMethod
{
    /**
     * {@inheritDoc}
     *
     * @throws \IBankWallet\Controller\IBankXRP\Exception\FieldException
     */
    public function setFields()
    {
        parent::setFields();

        // GENERATED CODE FROM bin/generate.php types
        // BEGIN GENERATED
        // END GENERATED
    }
}
