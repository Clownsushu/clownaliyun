#### Oss使用

```php
// 1. OSS使用
$oss = new Oss('AccessKey ID', 'AccessKey Secret', '空间名称', 'oss-cn-chengdu.aliyuncs.com');

// 使用文件上传 
// 参数1: 本地文件路径, 
// 参数2: 文件保存的路径名称和类型 不能以/开头, 
// 参数3: bool 类型是否直接返回访问地址默认 false
$oss->uploadFile('本地文件路径', '文件保存的路径名称和类型 不能以/开头', true);
// 使用字符串上传 
// 参数1: 要上传的内容, 
// 参数2: 文件保存的路径名称和类型 不能以/开头, 
// 参数3: bool 类型是否直接返回访问地址默认 false
$oss->uploadString('要上传的内容', '文件保存的路径名称和类型 不能以/开头', true);
```

#### Sms使用

```php
// 1. sms使用
$sms = new Sms('AccessKey ID', 'AccessKey Secret', '短信签名名称', 'oss-cn-chengdu.aliyuncs.com');

// 短信发送 
// 参数1:  接收者手机号码
// 参数2:  短信模板id
// 参数3:  要替换的参数 例如:短信模板是[您好您的验证码为: ${code}, 打死也不要告诉别人哦!] 可以传参为['code' => 111111]
$sms->sendSms('接收者手机号码', '短信模板id', ['code' => 111111]);
```

