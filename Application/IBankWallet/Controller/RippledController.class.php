<?php

namespace Wallet\Controller;

use think\Controller;

class RippledController
{
    protected $walletRpc;

    public function __construct() {
        $this->walletRpc = new WalletRpc('http://127.0.0.1:5005');
    }

    function getJsonBody() 
    {
        $put=file_get_contents('php://input');
        $put=json_decode($put,1);
        foreach ($put as $key => $value) {
            $_POST[$key]=$value;   
        }
    }

    public function verifyRegularKey()
    {
        $this->getJsonBody();
        $regular_secret = I('post.regular_secret');
        $main_account = I('post.main_account');
        $response = $this->walletRpc->verifyRegularKey($main_account, $regular_secret);
        echo json_encode($response);
    }


    public function removeRegularKey()
    {
        $this->getJsonBody();
        $secret = I('post.secret');
        $main_account = I('post.main_account');
        $response = $this->walletRpc->removeRegularKey($main_account, $secret);
        echo json_encode($response);
    }

    public function sign()
    {
        $this->getJsonBody();
        // 自动读取账户from的加密私钥，用password解密
        $password = I('post.password');
        $from = I('post.from');
        $to = I('post.to');
        $fee = I('post.fee');
        $amount = I('post.amount');
        $sourceTag = I('post.sourceTag');
        $destinationTag = I('post.destinationTag');
        $seed = $this->walletRpc->getSecret($from, $password);
        if (is_null($seed)) {
            echo "account not exists";
        }
        $response = $this->walletRpc->sign($from, $to, $seed, $amount, $fee, $sourceTag, $destinationTag);
        //if ($response->isSuccess()) {
        //    $data = $response->getData();
        //    $response = $this->submit_tx($data["blob_id"]);
        //    echo json_encode($response);
        //}
        echo json_encode($response);
    }



    public function accountTransaction()
    {
        $this->getJsonBody();
        $account = I('post.account');
        $limit = I('post.limit');
        $destinationTag = I('post.destinationTag');

        $response = $this->walletRpc->accountTransaction($account, $destinationTag, $limit);
        echo json_encode($response);

    }

    public function accountTransactions()
    {
        $this->getJsonBody();
        $account = I('post.account');
        $ledger_index_min = I('post.ledger_index_min');
        $ledger_index_max = I('post.ledger_index_max');

        $response = $this->walletRpc->accountTransactions($account, $ledger_index_min, $ledger_index_max);
        echo json_encode($response);

    }

    public function storeWallet()
    {
        $this->getJsonBody();
        $account_id = I('post.account_id');
        $password = I('post.password');
        $master_seed = I('post.master_seed');
        $response = $this->walletRpc->walletUpdate($account_id, $master_seed, $password);
        echo json_encode($response);
    }

    public function transaction()
    {
        $this->getJsonBody();
        $tx_id = I('post.tx_id');

        $response = $this->walletRpc->getTransaction($tx_id);
        echo json_encode($response);

    }

    public function genWallet()
    {
        $this->getJsonBody();
        $passphrase = I('post.passphrase');
        $response = $this->walletRpc->genWallet($passphrase);
        echo json_encode($response);
    }

    public function submit() {
        $this->getJsonBody();
        $blob_id = I('post.blob_id');
        $response = $this->walletRpc->submit($blob_id);
        echo json_encode($response);
    }

    public function submitRequireDest() {
        $this->getJsonBody();
        $secret = I('post.secret');
        $account = I('post.account');
        $fee = I('post.fee');
        $response = $this->walletRpc->submitRequireDest($account, $secret, $fee);
        echo json_encode($response);
    }

    // getBalance get balance of account
    public function getBalance()
    {
        $this->getJsonBody();
        $account = I('post.account');
        $destinationTag = I('post.destinationTag');
        $response = $this->walletRpc->getBalance($account, $destinationTag);
        echo json_encode($response);
    }

    public function randomAccount()
    {
        $r = $this->walletRpc->random();
        echo json_encode($r);
    }

    public function setRegularKey()
    {
        $this->getJsonBody();
        $secret = I('post.secret');
        $main_account = I('post.main_account');
        $regular_key = I('post.regular_key');
        $response = $this->walletRpc->setRegularKey($main_account, $secret, $regular_key);
        echo json($response);
    }

}
