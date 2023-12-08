<?php
namespace clown\aliyun;

use OSS\Core\OssException;
use OSS\OssClient;

/**
 * 阿里云oss
 */
class Oss
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
     * @var mixed|string 空间名称
     */
    protected $bucket = '';

    /**
     * @var mixed|string 端点地址
     */
    protected $endpoint = 'oss-cn-chengdu.aliyuncs.com';

    /**
     * @var OssClient 当前链接
     */
    protected $oss_client;

    /**
     * @var string 错误信息
     */
    public $error_msg = '';

    public function __construct($accessKeyId, $accessKeySecret, $bucket,  $endpoint = 'oss-cn-chengdu.aliyuncs.com')
    {
        if(!$accessKeyId) throw new \Exception('请传入accessKeyId参数');

        if(!$accessKeySecret) throw new \Exception('请传入accessKeySecret参数');

        if(!$bucket) throw new \Exception('请传入bucket参数');

        $this->accessKeyId = $accessKeyId;

        $this->accessKeySecret = $accessKeySecret;

        $this->bucket = $bucket;

        $this->endpoint = $endpoint;

        $this->oss_client = new OssClient($this->accessKeyId, $this->accessKeySecret, $this->endpoint);
    }

    /**
     * 文件上传
     * @param $local_file_path string 本地文件路径
     * @param $upload_file_path string 文件保存的路径名称和类型 不能以/开头
     * @return false|array
     */
    public function uploadFile($local_file_path = '', $upload_file_path = '')
    {

        try{
            $result = $this->oss_client->uploadFile($this->bucket, $upload_file_path, $local_file_path);
        }catch (OssException $e){
            //记录日志
            $this->error_msg = $e->getMessage();
            return false;
        }

        return $result;
    }

    /**
     * 字符串上传
     * @param $content mixed 要上传的内容
     * @param $upload_file_path string 文件保存的路径名称和类型 不能以/开头
     * @return false|null
     */
    public function uploadString($content, $upload_file_path = '')
    {
        if(empty($content)) return false;

        if(!is_string($content)){
            $content = json_encode($content, JSON_UNESCAPED_UNICODE);
        }

        try{
            $result = $this->oss_client->putObject($this->bucket, $upload_file_path, $content);
        }catch (OssException $e){
            //记录日志
            $this->error_msg = $e->getMessage();
            return false;
        }

        return $result;
    }
}