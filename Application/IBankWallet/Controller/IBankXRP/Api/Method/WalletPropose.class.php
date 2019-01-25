<?php declare(strict_types=1);

namespace Wallet\Controller\XRP\Api\Method;

use Wallet\Controller\XRP\Api\Field;

/**
 * WalletPropose Method Class
 *
 */
class WalletPropose extends AbstractMethod
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
