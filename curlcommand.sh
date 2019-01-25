#!/bin/bash
### 1 获取账户交易历史
#curl -X POST \
#  http://127.0.0.1:80/Wallet/Rippled/accountTransaction \
#  -H 'content-type: application/json' \
#  -d '    {"account": "rhaWGhN6dKPJ5ep7FwRpw5jrs7DWywox2g", "limit":24, "destinationTag":12}
#'
#{"data":{"account":"rhaWGhN6dKPJ5ep7FwRpw5jrs7DWywox2g","ledger_index_max":16165029,"ledger_index_min":15662540,"status":"success","transactions":[{"Account":"rJUekseQPCcZD4aqaUtCJbEtavoiEB5Zin","Amount":"10000","Destination":"rhaWGhN6dKPJ5ep7FwRpw5jrs7DWywox2g","DestinationTag":12,"hash":"ECF444A86D5E244A6835E3410259C0A456078D7FBED3EB5F8C926F0C21B99C63","Fee":"2000000","date":600664590}]},"errorCode":0,"errorMessage":""}
### 生成账户
#curl -X POST \
#  http://127.0.0.1:80/Wallet/Rippled/genWallet\
#  -H 'content-type: application/json' \
#  -d '{"passphrase":"8502AC5B8BC6019F86B68E1E23C5A23097A2BD416AE06CA6AF822F065FBF899D"}'
#
#{"data":{"account_id":"rLCjNkhcWjzV87JdojX8C1i2Vuk7zggjZq","master_seed":"shESXN4TtFPuMoGp6egyQ3aszKfen"},"errorCode":0,"errorMessage":""}

### 随机种子
#curl -X POST \
#  http://127.0.0.1:80/rippled_random\
#  -H 'content-type: application/json' \
#  -d '{}'
#{"raw":"{\"random\":\"3EB20981EE54ABD06803D5454FF2F508DF56E1B5505119373529F9B0A88D3EAF\"}","errorCode":0,"errorMessage":""}

### 获取余额
#curl -X POST \
#  http://127.0.0.1:80/rippled_balance\
#  -H 'content-type: application/json' \
#  -d '{"account": "rJUekseQPCcZD4aqaUtCJbEtavoiEB5Zin"}'
###{"raw":"{\"Balance\":\"10023970080\",\"Account\":\"rJUekseQPCcZD4aqaUtCJbEtavoiEB5Zin\"}","errorCode":0,"errorMessage":""}

### 查询交易
#curl -X POST \
#  http://127.0.0.1:80/rippled_transaction\
#  -H 'content-type: application/json' \
#  -d '{"tx_id": "63ADE4539F81C1E1534E730A45DC82C54CD2D2F29D40ADB65D0273923052C4E9"}'
###{"raw":"{\"Account\":\"rJUekseQPCcZD4aqaUtCJbEtavoiEB5Zin\",\"Amount\":\"2000000\",\"Destination\":\"rhaWGhN6dKPJ5ep7FwRpw5jrs7DWywox2g\",\"DestinationTag\":1}","errorCode":0,"errorMessage":""}

###  签名和提交交易
#result = `curl --silent -X POST \
#  http://127.0.0.1:80/Wallet/Rippled/sign \
#  -H 'content-type: application/json' \
#  -d '        {"from" : "rJUekseQPCcZD4aqaUtCJbEtavoiEB5Zin",
#        "to" : "rLCjNkhcWjzV87JdojX8C1i2Vuk7zggjZq",
#        "password" : "123456",
#        "amount" : "3000000",
#        "fee" : 100,
#        "destinationTag": 1234567
#        }'`
#{"data":{"blob_id":"1200002280000024000000102E0000000C6140000000001E84806840000000000027107321036BC6206A680439B8DF89938CFC3485D45E96DB6F94F6EF27E3DBF9C96A1C944774463044022006A825D51EF731B622FBACB43BD335BC9847EA94BC0E588FC5668B43F0BACAF702204BCF61E5C120CD02125C7C9D45127E22741B35250AE2A0820CEBF9F8A82852C68114BCAE382FA67D6F85F01A49C23EAEB7229BB01E73831421F2E1BF9ABF571587FE485C601B8940B08AF9D3"},"errorCode":0,"errorMessage":""}

### 提交交易
#curl -X POST \
#  http://127.0.0.1:80/Wallet/Rippled/submit\
#  -H 'content-type: application/json' \
#  -d '    {"blob_id": "120000228000000024000000122E0000000C6140000000002DC6C06840000000000027107321036BC6206A680439B8DF89938CFC3485D45E96DB6F94F6EF27E3DBF9C96A1C9447744730450221008072D7C12E1FD4BD6C8D68933652050719B364B0F248F10398BB84CC2BF6B22702205A666AEB0EA3D7B113101A9A1CB553F9F2BBA3AA5BF4439AD6ED11868195AD1E8114BCAE382FA67D6F85F01A49C23EAEB7229BB01E73831421F2E1BF9ABF571587FE485C601B8940B08AF9D3"}
#'
### {"data":{"tx_id":"21FA89F0DAA07D3B6A9FB8CA6B6D37A21B15E7C71CB14DE066E2C84ACB491614"},"errorCode":0,"errorMessage":""}

### 设置常规秘钥
#curl -X POST \
#  http://127.0.0.1:80/Wallet/Rippled/setRegularkey \
#  -H 'content-type: application/json' \
#  -d '{
#   "main_account": "rJUekseQPCcZD4aqaUtCJbEtavoiEB5Zin",
#   "secret":"shAtJ3FxtCnMTYWLb3S9TBzYKRaZP",
#   "regular_key": "rGhqgX5SzAYnbXCQVwJmxPCQBeK4iPCE2K"
#}'
#{"data":{"blob_id":"12000522800000240000000868400000000000000A7321036BC6206A680439B8DF89938CFC3485D45E96DB6F94F6EF27E3DBF9C96A1C944774473045022100B66B2D27057AB933BD1FBC071DE17F40DAA2DDD2FA1E667BBB673E531590DA1902200C23CE988CE7AA8DDE5212211D511197E14C2810F57AD6D9283D6F7F14C04B818114BCAE382FA67D6F85F01A49C23EAEB7229BB01E738814A543C59E6CF9707152C81C4BA5EA3800DDB72F45"},"errorCode":0,"errorMessage":""}

### 验证常规秘钥
#curl -X POST \
#  http://127.0.0.1:80/Wallet/Rippled/verifyRegularkey \
#  -H 'content-type: application/json' \
#  -d '{
#   "main_account": "rJUekseQPCcZD4aqaUtCJbEtavoiEB5Zin",
#   "regular_secret": "sskc46wqHEN4eTMZpYpB7MLUA9wxo"
#}'
#{"data":{"blob_id":"1200032280000000240000001168400000000000000A732102670751E44040154E165A37EFACBDD6239B08518189B838D326C95807A4AB488E74473045022100F9B764F1782D05B46E0C9ADFBE223E067FC98BBA01E401D2D542D20DE8873BA5022061998FD5E38B5F5940B9F1F8D09E686C1852496FEE691E1048353A31F08F585C8114BCAE382FA67D6F85F01A49C23EAEB7229BB01E73"},"errorCode":0,"errorMessage":""}
###{"raw":"{\"tx_id\":\"81D20EE44EFCFAD400C054E47ADBBFF508F897EC6801B8F8B699E6F78C508721\"}","errorCode":0,"errorMessage":""}

#curl -X POST \
#  http://127.0.0.1:80/Wallet/Rippled/storeWallet\
#  -H 'content-type: application/json' \
#  -d '{"password":"123456", "account_id":"rLCjNkhcWjzV87JdojX8C1i2Vuk7zggjZq","master_seed":"shESXN4TtFPuMoGp6egyQ3aszKfen"}'
#{"data":{"account":"rLCjNkhcWjzV87JdojX8C1i2Vuk7zggjZq","stored":true},"errorCode":0,"errorMessage":""}

#echo curl -X POST http://127.0.0.1:80/Wallet/Rippled/submitRequireDest -H \'content-type: application/json\' -d \'{\"secret\": \"shESXN4TtFPuMoGp6egyQ3aszKfen\", \"account\":\"rLCjNkhcWjzV87JdojX8C1i2Vuk7zggjZq\", \"fee\": 1500}\'|bash
#{"data":{"deprecated":"Signing support in the 'submit' command has been deprecated and will be removed in a future version of the server. Please migrate to a standalone signing tool.","engine_result":"tesSUCCESS","engine_result_code":0,"engine_result_message":"The transaction was applied. Only final in a validated ledger.","status":"success","tx_blob":"120003220000000024000000012021000000016840000000000005DC73210384C5793EA445EF6263A86E288C53D83FF392582498FA0C4AE0B8354F9F4466A574473045022100A0895029C8F4AF77CEDB4EEDEF972435D8324BFB8E4B33DDC1823DF8CDE67B370220556D6443DF2BA71BB83A48E9B14A8CC7AB7DB51E3440711F175671D6A68AAA2B8114D7BD743D535D3341CB48A42F11874F62A7437680","tx_json":{"Account":"rLCjNkhcWjzV87JdojX8C1i2Vuk7zggjZq","Fee":"1500","Flags":0,"Sequence":1,"SetFlag":1,"SigningPubKey":"0384C5793EA445EF6263A86E288C53D83FF392582498FA0C4AE0B8354F9F4466A5","TransactionType":"AccountSet","TxnSignature":"3045022100A0895029C8F4AF77CEDB4EEDEF972435D8324BFB8E4B33DDC1823DF8CDE67B370220556D6443DF2BA71BB83A48E9B14A8CC7AB7DB51E3440711F175671D6A68AAA2B","hash":"03C17EC97B5D63AB398D2CE448DC5A08F64EE0B2CE267D3DAEFC9261D00BD094"}},"errorCode":0,"errorMessage":""}

