<?php declare(strict_types=1);

namespace IBankWallet\Controller\IBankXRP\Api\Method;

use IBankWallet\Controller\IBankXRP\Api\Field;

/**
 * IBankWalletPropose Method Class
 *
 */
class IBankWalletPropose extends AbstractMethod
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
            'name' => 'seed',
            'required' => false
        ]));

        $this->addField(new Field([
            'name' => 'passphrase',
            'required' => true
        ]));

        $this->addField(new Field([
            'name' => 'seed_hex',
            'required' => false
        ]));

        $this->addField(new Field([
            'name' => 'key_type',
            'required' => false
        ]));

        // END GENERATED
    }
}
