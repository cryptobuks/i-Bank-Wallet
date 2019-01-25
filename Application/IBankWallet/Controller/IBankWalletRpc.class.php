<?php

namespace IBankWallet\Controller;

use IBankWallet\Model\IBankWalletModel;
use IBankWallet\Controller\IBankXRP\Client;
use Defuse\Crypto\Crypto;

class IBankWalletRpc
{
    protected $client;
    protected $store;

    public function __construct($address)
    {
        $this->client = new Client($address);
        $this->store = new IBankWalletModel;
    }

    /**
     * 加密
     *
     * @param string $seed, $password
     * @return string encrypted seed
     * @throws \Exception
     */
    function encryptedSeed($seed, $password)
    {
        return Crypto::encryptWithPassword($seed, $password);
    }

    /**
     * 解密
     *
     * @param string $ciphertext, $password
     * @return string seed
     * @throws \Exception
     */
    function decryptSeed($ciphertext, $password)
    {
        return Crypto::decryptWithPassword($ciphertext, $password);
    }

    /**
     * 账户是否已经存在
     *
     * @param string $account
     * @return bool
     * @throws \Exception
     */
    function accountExists($account)
    {
        $map["account_id"] = $account;
        $r = $this->store->where($map)->find();
        if ($r)
        {
            return true;
        }
        return false;
    }

    /**
     * 获取账户对应的私钥，数据库中存储的是加密后的私钥，需要密码解密
     *
     * @param string $account, $password
     * @return string secret
     * @throws \Exception
     */
    function getSecret($account, $password)
    {
        $map["account_id"] = $account;
        $r = $this->store->where($map)->find();
        if ($r)
        {
            return $this->decryptSeed($r["master_seed"], $password);
        }
        return null;
    }

    /**
     * 账户和加密后的私钥存入数据库
     *
     * @param string $account, $master_seed, $password
     * @return string array
     * @throws \Exception
     */
    public function walletUpdate($account, $master_seed, $password)
    {
        if ($this->accountExists($account))
        {
            return new IBankResult(1, "Account already exists", array());
        }
        $encryptedSeed = $this->encryptedSeed($master_seed, $password);
        $data = array('account_id'=>$account,'master_seed'=>$encryptedSeed);
        try
        {
            var_dump($data);
            $result = $this->store->data($data)->add();
            var_dump($result);
            if ($result)
            {
                $dst = array("account" => $account, "stored" => true);
                return new IBankResult(0, "", $dst);
            } else
            {
                return new IBankResult(1, "Store account failed" . $result, array());
            }
        }
        catch (PDOException $ex)
        {
            return new IBankResult(1, "Store account exception" . $ex , array());
        }
    }


    /**
     * 生成账户
     *
     * @param string $passphrase
     * @return array
     * @throws \Exception
     */
    public function genIBankWallet($passphrase)
    {
        /*
        {
    "result": {
        "account_id": "rHb9CJAWyB4rj91VRWn96DkukG4bwdtyTh",
        "key_type": "secp256k1",
        "master_key": "I IRE BOND BOW TRIO LAID SEAT GOAL HEN IBIS IBIS DARE",
        "master_seed": "snoPBrXtMeMyMHUVTgbuqAfg1SUTb",
        "master_seed_hex": "DEDCE9CE67B451D852FD4E846FCDE31C",
        "public_key": "aBQG8RQAzjs1eTKFEAQXr2gS4utcDiEC9wmi7pfUPTi27VCahwgw",
        "public_key_hex": "0330E7FC9D56BB25D6893BA3F317AE5BCF33B3291BD63DB32654A313222F7FD020",
        "status": "success"
         }
        }
         */
        $response = $this->client->send('wallet_propose', ["passphrase" => $passphrase]);
        if ($response->isSuccess())
        {
            $data = $response->getIBankResult();
            $rst = array("account_id" => $data["account_id"],
                "master_seed" => $data["master_seed"]);
            return new IBankResult(0, "", $rst);
        }

        return new IBankResult($response->getErrorCode(), $response->getErrorMessage(), array());
    }

    /**
     * 签名
     *
     * @param string $from, to , secret, amount, fee, sourceTag, destinationTag
     * @return array {"data" => array("blob_id" => ""), "errorCode" => 0, "errorMessage" => ""}
     * @throws \Exception
     */
    function sign( $from, $to, $secret, $amount, $fee, $sourceTag, $destinationTag)
    {
        if ( !isset($fee) || $fee == 0 ||  $fee == "" )
        {
            $fee = 12;
        }
        $response = $this->client->send('sign', [
            "secret" => $secret,"tx_json" => array( "Account" => $from,
            "Fee" => $fee,
            "Amount" => $amount,
            "Destination" => $to,
            "SourceTag" => $sourceTag,
            "DestinationTag" => $destinationTag,
            "TransactionType" => "Payment")]);
        if ($response->isSuccess())
        {
            $data = $response->getIBankResult();
            $rst = array("blob_id" => $data["tx_blob"]);
            return new IBankResult(0, "", $rst);
        }

        return new IBankResult($response->getErrorCode(), $response->getErrorMessage(), null);
    }

    /**
     * 提交交易
     *
     * @param string $blob_id
     * @return array {"data" => array("tx_id" => ""), "errorCode" => 0, "errorMessage" => ""}
     * @throws \Exception
     */
    function submit($blob_id)
    {
        $response= $this->client->send('submit', [
            "tx_blob" => $blob_id
        ]);
        if ($response->isSuccess())
        {
            $data = $response->getIBankResult();
            $rst = array("tx_id" => $data["tx_json"]["hash"]);
            return new IBankResult(0, "", $rst);
        }

        return new IBankResult($response->getErrorCode(), $response->getErrorMessage(), null);
    }

    /**
     * 提交必须启用目的标签约束任务，如果提交成功，则向account的转账必须指定destinationTag，否则转账失败
     *
     * @param string $blob_id
     * @return array
     * @throws \Exception
     */
    function submitRequireDest($account, $secret, $fee)
    {
        if (!isset($account) || !isset($secret))
        {
            return new IBankResult(1, "account or secret required", null);
        }
        if (!isset($fee) )
        {
            $fee = 1500;
        }
        $response= $this->client->send('submit', [
            "secret" => $secret,
            "tx_json" => array("Account" => $account, "Fee" => $fee, "Flags" => 0, "SetFlag" => 1,
            "TransactionType" => "AccountSet")
        ]);
        if ($response->isSuccess())
        {
            $data = $response->getIBankResult();
            return new IBankResult(0, "", $data);
        }

        return new IBankResult($response->getErrorCode(), $response->getErrorMessage(), null);
    }

    /**
     * 获取账户余额
     *
     * @param string $account, $destinationTag
     * @return array {"data" => array{"Balance" => 1, "Account" => ""}, "errorCode" => 0, "errorMessage" => ""}
     * @throws \Exception
     */
    public function getBalance($account, $destinationTag)
    {
        $response = $this->client->send('account_info', [
            'account' => $account
        ]);
        if ($response->isSuccess())
        {
            $data = $response->getIBankResult();
            $rst = array("Balance" => $data["account_data"]["Balance"], "Account" => $data["account_data"]["Account"], "destinationTag" => $destinationTag);
            return new IBankResult(0, "", $rst);
        }

        return new IBankResult($response->getErrorCode(), $response->getErrorMessage(), null);
    }

    /**
     * setRegularKey 可以设置常规秘钥对，也可以修改常规秘钥对，如果是创建，则secret必须是主账户私钥，
     * 如果是修改，则secret既可以是主账户私钥， 也可以是常规秘钥对的私钥
     *
     * @param string $main_account, $secret, $regular_key
     * @return array {"data" => array("blob_id" => ""), "errorCode" => 0, "errorMessage" => ""}
     * @throws \Exception
     */
    public function setRegularKey($main_account, $secret, $regular_key)
    {
        $response = $this->client->send('sign', [
            "secret" => $secret,"tx_json" => array( "Account" => $main_account,
            "RegularKey" => $regular_key,
            "TransactionType" => "SetRegularKey"
        )]);

        if ($response->isSuccess())
        {
            $data = $response->getIBankResult();
            $rst = array("blob_id" => $data["tx_blob"]);
            return new IBankResult(0, "", $rst);
        }

        return new IBankResult($response->getErrorCode(), $response->getErrorMessage(), null);
    }


    /**
     * removeRegularKey 删除常规秘钥对，tj_json中不需要常规秘钥对账户
     *
     * @param string $main_account, $secret
     * @return array {"data" => {"blob_id" => "aaaaabbbbb"}, "errorCode" => 0, "errorMessage" => ""}
     * @throws \Exception
     */
    public function removeRegularKey($main_account, $secret)
    {
        $response = $this->client->send('sign', [
            "secret" => $secret,"tx_json" => array( "Account" => $main_account,
            "TransactionType" => "SetRegularKey"
        )]);
        if ($response->isSuccess())
        {
            $data = $response->getIBankResult();
            $rst = array("blob_id" => $data["tx_blob"]);
            return new IBankResult(0, "", $rst);
        }

        return new IBankResult($response->getErrorCode(), $response->getErrorMessage(), null);
    }

    /**
     *
     * @param null
     * @return array {"data" => {"random" => ""}, "errorCode" => 0, "errorMessage" => ""}
     * @throws \Exception
     */
    public function random()
    {
        $response = $this->client->send('random', [ "id" => 1]);
        if ($response->isSuccess())
        {
            $data = $response->getIBankResult();
            $rst = array("random" => $data["random"]);
            return new IBankResult(0, "", $rst);
        }

        return new IBankResult($response->getErrorCode(), $response->getErrorMessage(), null);
    }

    /**
     * 查看账户指定标签下的交易历史, 此方法是直接从瑞波账本上获取数据
     * @param string account destinationTag limit
     * @return array
     * @throws \Exception
     */
    public function accountTransaction($account, $destinationTag, $limit)
    {
        if (!isset($destinationTag) )
        {
            $destinationTag = 0;
        }
        $response = $this->client->send('account_tx', ["account" => $account,
            "ledger_index_min"=>-1, "limit" => $limit,
            "binary" => false, "forward" => false, "ledger_index_max" => -1]);
        if ($response->isSuccess()) {
            $data = $response->getIBankResult();
            $rst = array("account" => $data["account"], "ledger_index_max" => $data["ledger_index_max"],
                "ledger_index_min" => $data["ledger_index_min"], "status" => $data["status"]);
            $transaction = array();
            foreach ($data["transactions"] as $value) {
                $value = $value["tx"];
                if ($value["TransactionType"] == "Payment" && $value["DestinationTag"] == $destinationTag)
                {
                    $temp_array = array("Account" => $value["Account"], "Amount" => $value["Amount"],
                        "Destination" => $value["Destination"], "DestinationTag" => $value["DestinationTag"],
                        "SourceTag" => $value["SourceTag"],"ledger_index" => $value["ledger_index"],
                        "hash" => $value["hash"], "Fee" => $value["Fee"], "date" => $value["date"]
                    );
                    $transaction []= $temp_array;
                }
            }
            $rst["transactions"] = $transaction;
            return new IBankResult(0, "", $rst);
        }

        return new IBankResult($response->getErrorCode(), $response->getErrorMessage(), null);
    }

    /**
     * 查看账户的交易历史，使用此方法把不同区块高度的交易都写入数据库，在数据库里通过sql语言计算用户余额
     * @param string account ledger_index_min ledger_index_max
     * @return array
     * @throws \Exception
     */
    public function accountTransactions($account, $ledger_index_min, $ledger_index_max)
    {
        if (!isset($ledger_index_min) || !isset($ledger_index_max))
        {
            return new IBankResult(1, "need ledger_index_min or ledger_index_max", null);
        }

        $response = $this->client->send('account_tx', ["account" => $account,
            #"limit" => -1,
            "ledger_index_min"=>$ledger_index_min,
            "binary" => false, "forward" => false, "ledger_index_max" => $ledger_index_max]);
        if ($response->isSuccess()) {
            $data = $response->getIBankResult();
            $rst = array("account" => $data["account"], "ledger_index_max" => $data["ledger_index_max"],
                "ledger_index_min" => $data["ledger_index_min"], "status" => $data["status"]);
            $transaction = array();
            foreach ($data["transactions"] as $value) {
                $value = $value["tx"];
                if ($value["TransactionType"] == "Payment")
                {
                    $temp_array = array("Account" => $value["Account"], "Amount" => $value["Amount"],
                        "Destination" => $value["Destination"], "DestinationTag" => $value["DestinationTag"],
                        "SourceTag" => $value["SourceTag"],"ledger_index" => $value["ledger_index"],
                        "hash" => $value["hash"], "Fee" => $value["Fee"], "date" => $value["date"]
                    );
                    $transaction []= $temp_array;
                }
            }
            $rst["transactions"] = $transaction;
            return new IBankResult(0, "", $rst);
        }

        return new IBankResult($response->getErrorCode(), $response->getErrorMessage(), null);
    }

    /**
     * 获取交易详情，只返回了部分交易数据，如果想要更详细的信息，可以直接调用client->send方法
     *
     * @param string tx_id
     * @return array
     * @throws \Exception
     */
    public function getTransaction($tx_id)
    {
        $response = $this->client->send('tx', ["transaction" => $tx_id]);
        if ($response->isSuccess()) {
            $data = $response->getIBankResult();
            $rst = array();
            if ($data["TransactionType"] == "Payment" ) {
                $rst = array("Account" => $data["delivered_amount"], "Amount"=> $data["Amount"],
                    "Destination" => $data["Destination"],
                    "SourceTag" => $data["SourceTag"], "date" => $data["date"],
                    "DestinationTag" => $data["DestinationTag"], "date" => $data["date"],
                    "hash" => $data["hash"], "TransactionType" => $data["TransactionType"],
                    "Fee" => $data["Fee"], "ledger_index" => $data["ledger_index"],
                    "TransactionIBankResult" => $data["meta"]["TransactionIBankResult"] );
            } else if ($data["TransactionType"] == "AccountSet" ) {
                $rst = array("Account" => $data["Account"], "date" => $data["date"],
                    "hash" => $data["hash"], "TransactionType" => $data["TransactionType"],
                    "validated" => $data["validated"], "status" => $data["status"],
                    "TransactionIBankResult" => $data["meta"]["TransactionIBankResult"]);
            } else if ($data["TransactionType"] == "SetRegularKey" ) {
                $rst = array("Account" => $data["Account"], "RegularKey" => $data["RegularKey"],
                    "date" => $data["date"], "hash" => $data["hash"],
                    "TransactionType" => $data["TransactionType"] );
            }

            return new IBankResult(0, "", $rst);
        }

        return new IBankResult($response->getErrorCode(), $response->getErrorMessage(), null);
    }


    /**
     * 验证常规秘钥对，确认是否设置成功
     *
     * @param string main_account, regular_secret
     * @return array {"data" => {}, "errorCode" => 0, "errorMessage" => ""}
     * @throws \Exception
     */

    public function verifyRegularKey($main_account, $regular_secret)
    {
        $response = $this->client->send('sign', [
            "secret" => $regular_secret,"tx_json" => array( "Account" => $main_account,
            "TransactionType" => "AccountSet"
        )]);
        if ($response->isSuccess())
        {
            $data = $response->getIBankResult();
            $rst = array("blob_id" => $data["tx_blob"]);
            return new IBankResult(0, "", $rst);
        }
    }

}
