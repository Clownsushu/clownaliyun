<?php
namespace clown\aliyun;

use AlibabaCloud\SDK\Dysmsapi\V20170525\Dysmsapi;
use AlibabaCloud\Tea\Exception\TeaError;
use AlibabaCloud\Tea\Utils\Utils;

use Darabonba\OpenApi\Models\Config;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\SendSmsRequest;
use AlibabaCloud\Tea\Utils\Utils\RuntimeOptions;

/**
 * 阿里云短信
 */
class Sms
{
    /**
     * @var string 阿里云AccessKey ID
     */
    protected $accessKeyId = '';

    /**
     * @var string 阿里云AccessKey Secret
     */
    protected $accessKeySecret = '';

    /**
     * @var string 阿里云域名
     */
    protected $endpoint = '';

    /**
     * @var Dysmsapi 客户端
     */
    protected $client;

    /**
     * @var string 短信签名名称
     */
    protected $signName = '';

    /**
     * @var string 返回错误信息
     */
    public $error_msg = '';

    /**
     * 构造函数
     * @param $accessKeyId string 阿里云AccessKey ID
     * @param $accessKeySecret string 阿里云AccessKey Secret
     * @param string $signName 短信签名名称
     * @param $endpoint string 阿里云域名
     * @throws \Exception
     */
    public function __construct($accessKeyId, $accessKeySecret, $signName, $endpoint = 'dysmsapi.aliyuncs.com')
    {
        if(!$accessKeyId) throw new \Exception('请传入accessKeyId参数');

        if(!$accessKeySecret) throw new \Exception('请传入accessKeySecret参数');

        if(!$signName) throw new \Exception('请传入签名signName参数');

        $this->accessKeyId = $accessKeyId;

        $this->accessKeySecret = $accessKeySecret;

        $this->endpoint = $endpoint;

        $this->signName = $signName;

        $this->createClient();
    }

    /**
     * 创建客户端
     * @return Dysmsapi
     */
    public function createClient()
    {
        $config = new Config([
            // 必填，您的 AccessKey ID
            "accessKeyId" => $this->accessKeyId,
            // 必填，您的 AccessKey Secret
            "accessKeySecret" => $this->accessKeySecret
        ]);

        $config->endpoint = $this->endpoint;

        $this->client = new Dysmsapi($config);
    }

    /**
     * 短信发送
     * @param $phone string 要发送的手机号码
     * @param $template_code string 模板id
     * @param $params array 要替换的参数 例如:短信模板是[
     * 您好您的验证码为: ${code}, 打死也不要告诉别人哦!] 可以传参为['code' => 111111]
     * @return bool
     */
    public function sendSms($phone = '', $template_code = '', $params = []) :bool
    {
        if(empty($phone)) return false;

        if(empty($template_code)) return false;
        //组装发送的参数
        $config = [
            'phoneNumbers' => $phone,
            'signName' => $this->signName,
            'templateCode' => $template_code,
            'templateParam' => json_encode($params, JSON_UNESCAPED_UNICODE),
        ];

        $sendSmsRequest = new SendSmsRequest($config);
        try{
            $res = $this->client->sendSmsWithOptions($sendSmsRequest, new RuntimeOptions([]));
            if($res->body->message != "OK" || $res->body->code != "OK"){
                $this->error_msg = $res->body->message;
                return false;
            }
        }catch (\Exception $error){
            if (!($error instanceof TeaError)) {
                $error = new TeaError([], $error->getMessage(), $error->getCode(), $error);
            }
            // 如有需要，请打印 error
            Utils::assertAsString($error->message);
            $this->error_msg = $error->message;
            return false;
        }

        return true;
    }
}