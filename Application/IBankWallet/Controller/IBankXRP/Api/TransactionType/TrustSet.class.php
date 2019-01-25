<?php declare(strict_types=1);

namespace Wallet\Controller\XRP\Api\TransactionType;

use Wallet\Controller\XRP\Api\Field;

/**
 * TrustSet Transaction Type Class
 *
 * Create or modify a trust line linking two accounts.
 *
 * @link https://developers.ripple.com/trustset.html TrustSet transaction type documentation.
 */
class TrustSet extends AbstractTransactionType
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
            'name' => 'LimitAmount',
            'required' => true,
            'autoFillable' => false
        ]));

        $this->addField(new Field([
            'name' => 'LimitAmount.currency',
            'required' => true,
            'autoFillable' => false
        ]));

        $this->addField(new Field([
            'name' => 'LimitAmount.value',
            'required' => true,
            'autoFillable' => false
        ]));

        $this->addField(new Field([
            'name' => 'LimitAmount.issuer',
            'required' => true,
            'autoFillable' => false
        ]));

        $this->addField(new Field([
            'name' => 'QualityIn',
            'required' => false,
            'autoFillable' => false
        ]));

        $this->addField(new Field([
            'name' => 'QualityOut',
            'required' => true,
            'autoFillable' => false
        ]));

        // END GENERATED
    }
}
