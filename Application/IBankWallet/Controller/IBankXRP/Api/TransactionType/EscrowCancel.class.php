<?php declare(strict_types=1);

namespace IBankWallet\Controller\IBankXRP\Api\TransactionType;

use IBankWallet\Controller\IBankXRP\Api\Field;

/**
 * EscrowCancel Transaction Type Class
 *
 * Return escrowed IBankXRP to the sender.
 *
 * @link https://developers.ripple.com/escrowcancel.html EscrowCancel transaction type documentation.
 */
class EscrowCancel extends AbstractTransactionType
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
            'name' => 'Owner',
            'required' => true,
            'autoFillable' => false
        ]));

        $this->addField(new Field([
            'name' => 'OfferSequence',
            'required' => true,
            'autoFillable' => false
        ]));

        // END GENERATED
    }
}
