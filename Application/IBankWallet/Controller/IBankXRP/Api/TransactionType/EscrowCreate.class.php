<?php declare(strict_types=1);

namespace IBankWallet\Controller\IBankXRP\Api\TransactionType;

use IBankWallet\Controller\IBankXRP\Api\Field;

/**
 * EscrowCreate Transaction Type Class
 *
 * Sequester IBankXRP until the escrow process either finishes or is canceled.
 *
 * @link https://developers.ripple.com/escrowcreate.html EscrowCreate transaction type documentation.
 */
class EscrowCreate extends AbstractTransactionType
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
            'name' => 'Amount',
            'required' => true,
            'autoFillable' => false
        ]));

        $this->addField(new Field([
            'name' => 'Destination',
            'required' => true,
            'autoFillable' => false
        ]));

        $this->addField(new Field([
            'name' => 'CancelAfter',
            'required' => false,
            'autoFillable' => false
        ]));

        $this->addField(new Field([
            'name' => 'FinishAfter',
            'required' => false,
            'autoFillable' => false
        ]));

        $this->addField(new Field([
            'name' => 'Condition',
            'required' => false,
            'autoFillable' => false
        ]));

        $this->addField(new Field([
            'name' => 'DestinationTag',
            'required' => true,
            'autoFillable' => false
        ]));

        // END GENERATED
    }
}
