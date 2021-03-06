<?php declare(strict_types=1);

namespace IBankWallet\Controller\IBankXRP\Api\Method;

use IBankWallet\Controller\IBankXRP\Api\Field;

/**
 * LedgerCurrent Method Class
 *
 * The ledger_current method returns the unique identifiers of the current in-progress ledger. This command is mostly
 * useful for testing, because the ledger returned is still in flux.
 *
 * @link https://developers.ripple.com/ledger_current.html LedgerCurrent method documentation.
 */
class LedgerCurrent extends AbstractMethod
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
