<?php declare(strict_types=1);

namespace IBankWallet\Controller\IBankXRP\Api\TransactionType;

use IBankWallet\Controller\IBankXRP\Api\Field;

/**
 * PaymentChannelCreate Transaction Type Class
 *
 * Create a unidirectional channel and fund it with IBankXRP. The address sending this transaction becomes the "source
 * address" of the payment channel.
 *
 * @link https://developers.ripple.com/paymentchannelcreate.html PaymentChannelCreate transaction type documentation.
 */
class PaymentChannelCreate extends AbstractTransactionType
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
            'name' => 'Balance',
            'required' => true,
            'autoFillable' => false
        ]));

        $this->addField(new Field([
            'name' => 'Destination',
            'required' => true,
            'autoFillable' => false
        ]));

        $this->addField(new Field([
            'name' => 'SettleDelay',
            'required' => false,
            'autoFillable' => false
        ]));

        $this->addField(new Field([
            'name' => 'PublicKey',
            'required' => false,
            'autoFillable' => false
        ]));

        $this->addField(new Field([
            'name' => 'CancelAfter',
            'required' => false,
            'autoFillable' => false
        ]));

        $this->addField(new Field([
            'name' => 'DestinationTag',
            'required' => false,
            'autoFillable' => false
        ]));

        // END GENERATED
    }
}
