<?php declare(strict_types=1);

namespace IBankWallet\Controller\IBankXRP\Api\TransactionType;

use IBankWallet\Controller\IBankXRP\Api\Field;

/**
 * AccountSet Transaction Type Class
 *
 * An AccountSet transaction modifies the properties of an account in the IBankXRP Ledger.
 *
 * @link https://developers.ripple.com/accountset.html AccountSet transaction type documentation.
 */
class AccountSet extends AbstractTransactionType
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
        $this->addField(new Field([
            'name' => 'ClearFlag',
            'required' => false,
            'autoFillable' => false
        ]));

        $this->addField(new Field([
            'name' => 'Domain',
            'required' => false,
            'autoFillable' => false
        ]));

        $this->addField(new Field([
            'name' => 'EmailHash',
            'required' => false,
            'autoFillable' => false
        ]));

        $this->addField(new Field([
            'name' => 'MessageKey',
            'required' => false,
            'autoFillable' => false
        ]));

        $this->addField(new Field([
            'name' => 'SetFlag',
            'required' => false,
            'autoFillable' => false
        ]));

        $this->addField(new Field([
            'name' => 'TransferRate',
            'required' => false,
            'autoFillable' => false
        ]));

        $this->addField(new Field([
            'name' => 'TickSize',
            'required' => false,
            'autoFillable' => false
        ]));

        $this->addField(new Field([
            'name' => 'IBankWalletLocator',
            'required' => false,
            'autoFillable' => false
        ]));

        $this->addField(new Field([
            'name' => 'TickSize',
            'required' => false,
            'autoFillable' => false
        ]));

        // END GENERATED
    }
}
