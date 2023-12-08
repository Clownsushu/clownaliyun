<?php

if(!function_exists('curlPost')){
    /**
     * curlpost请求
     * @param $url string 请求地址
     * @param $data array 发送内容
     * @param $type string 请求类型
     * @param bool $is_return 是否直接返回
     * @return array|mixed
     */
    function curlPost($url = '', $data = [], $type = 'json', $is_return = false)
    {
        $chr = curl_init();
        curl_setopt($chr, CURLOPT_URL, $url);
        curl_setopt($chr, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($chr, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($chr, CURLOPT_POST, true);

        if($type=='json'){
            curl_setopt($chr, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        }else{
            curl_setopt($chr, CURLOPT_HTTPHEADER, [
                'Content-Type: multipart/form-data',
            ]);
        }
        curl_setopt($chr, CURLOPT_POSTFIELDS, $data);
        curl_setopt($chr, CURLOPT_RETURNTRANSFER, true); //true回调结果，false直接echo输出
        curl_setopt($chr, CURLOPT_CONNECTTIMEOUT, 3); //在发起连接前等待的时间
        curl_setopt($chr, CURLOPT_TIMEOUT, 10); //允许最大执行时间
        $output = curl_exec($chr);

        curl_close($chr);

        if($is_return) return $output;

        try{
            return json_decode($output, true);
        }catch (exception $e){
            return (array) $output;
        }
    }
}

if(!function_exists('curlFormData')){
    /**
     * curl表单上传
     * @param $url string 要上传的地址
     * @param $formData array 要提交的内容
     * @return bool|string
     */
    function curlFormData($url, $formData) {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $formData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: multipart/form-data',
        ]);

        $response = curl_exec($ch);

        if(curl_errno($ch)){
            curl_close($ch);
            return 'curl:Error: ' . curl_error($ch);
        }

        curl_close($ch);

        try{
            return json_decode($response, true);
        }catch (exception $e){
            return (array) $response;
        }
    }
}

if(!function_exists('writeLogToFileAppend')){
    /**
     * 写入日志到文件 追加
     * @param $filename string 文件名称
     * @param $data mixed 要写入的数据
     * @return void
     */
    function writeLogToFileAppend($filename, $data)
    {
        if(!empty($filename) && !empty($data)){
            if(!is_string($data)){
                $data = json_encode($data, JSON_UNESCAPED_UNICODE);
            }
            $dir = './templog';
            if(!is_dir($dir)){
                mkdir($dir, 0777, true);
            }

            $date = date('Y-m-d H:i:s');

            $data = "[{$date}]  {$data}  ." . PHP_EOL;

            $file_path = $dir . '/' . $filename;

            file_put_contents($file_path, $data, FILE_APPEND);
        }
    }
}

if(!function_exists('getBaseFileName')){
    /**
     * 获取文件名称 不包含后缀
     * @param $file_path string 文件路径
     * @param bool $is_ext 是否返回文件后缀
     * @return false|string
     */
    function getBaseFileName($file_path = '', $is_ext = true)
    {
        $arr = array_filter(explode('/', $file_path));

        $string = date('Ymd');

        if(!empty($arr)){
            $string = end($arr);
            if(!$is_ext){
                $arr = array_filter(explode('.', $string));
                if(!empty($arr)){
                    $string = reset($arr);
                }
            }
        }
        return $string;
    }
}