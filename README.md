# xrp addr ok

ripple address need 20 xrp to active

比特币最小单位是聪，瑞波币最小单位是滴

```
1xrp = 1000000 drop
```

# ripple币支持

## 说明

正常的顺序，随机种子、生成钱包、存储钱包、设置常规秘钥对、签名交易、提交交易、查询交易

鉴于每个XRP账户必须保留20个XRP才能激活地址、所以不建议每个用户分配一个账户，Ripple官方也不建议

所以,采用多个用户共享XRP地址的方式，通过Tag来区分用户，用户和Tag一一对应，每笔交易都需要指定Tag

所以，获取余额和获取交易历史等接口都需要传入地址和Tag两个参数来唯一标识用户

# 瑞波币的配置文件和验证文件目录
/opt/rippled/etc 
/opt/rippled/bin

# 依赖的php扩展模块
pdo mysql xmlwriter
