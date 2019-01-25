<?php declare(strict_types=1);

namespace Wallet\Controller\XRP\Api\Method;

use Wallet\Controller\XRP\Api\Field;

/**
 * LedgerClosed Method Class
 *
 * The ledger_closed method returns the unique identifiers of the most recently closed ledger. (This ledger is not
 * necessarily validated and immutable yet.)
 *
 * @link https://developers.ripple.com/ledger_closed.html LedgerClosed method documentation.
 */
class LedgerClosed extends AbstractMethod
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
        $this->addField(new Field([
            'name' => 'ledger_hash',
            'required' => false
        ]));

        $this->addField(new Field([
            'name' => 'ledger_index',
            'required' => false
        ]));

        // END GENERATED
    }
}
