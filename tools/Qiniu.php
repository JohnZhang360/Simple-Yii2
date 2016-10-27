<?php
namespace app\tools;

use Zb;
use Qiniu\Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;

/**
 * Class Qiniu
 * @package app\tools
 */
class Qiniu
{
    private static $instance;
    private $_accessKey;
    private $_secretKey;

    /**
     * 获取实例
     * @param null $accessKey
     * @param null $secretKey
     * @return Qiniu
     */
    public static function getInstance($accessKey = null, $secretKey = null)
    {
        if (!isset(self::$instance)) {
            self::$instance = self::newInstance($accessKey, $secretKey);
        }
        return self::$instance;
    }

    /**
     * 获取新实例
     * @param null $accessKey
     * @param null $secretKey
     * @return mixed
     */
    public static function newInstance($accessKey = null, $secretKey = null)
    {
        $c = __CLASS__;
        self::$instance = new $c;
        self::$instance->_accessKey = $accessKey === null ? Zb::$app->params["cdn"]["accessKey"] : $accessKey;
        self::$instance->_secretKey = $secretKey === null ? Zb::$app->params["cdn"]["secretKey"] : $secretKey;
        return self::$instance;
    }

    /**
     * 获取授权
     * @return Auth
     */
    private function getAuth()
    {
        return new Auth($this->_accessKey, $this->_secretKey);
    }

    /**
     * 获取Bucket对象
     * @return BucketManager
     */
    private function getBucketManager()
    {
        return new BucketManager($this->getAuth());
    }

    /**
     * 获取上传凭证
     * @param $bucket
     * @return string
     */
    private function getUploadToken($bucket)
    {
        return $this->getAuth()->uploadToken($bucket);
    }

    /**
     * 获取指定bucket文件列表
     * @param string $listBucket 要列取的空间名称
     * @param string $prefix 要列取文件的公共前缀
     * @param string $marker 上次列举返回的位置标记，作为本次列举的起点信息。
     * @param integer $pageSize 本次列举的条目数
     * @return array
     */
    public function listFile($listBucket, $marker = "", $pageSize = 10, $prefix = "")
    {
        $bucketMgr = $this->getBucketManager();
        list($iterms, $marker, $err) = $bucketMgr->listFiles($listBucket, $prefix, $marker, $pageSize);
        if ($err !== null) {
            return ["flag" => false, "error" => $err];
        } else {
            return ["flag" => true, "items" => $iterms, "marker" => $marker];
        }
    }

    /**
     * 上传文件到空间
     * @param string $filePath 要上传文件的本地路径
     * @param string $bucket 要上传的空间
     * @param string $prefix 文件前缀
     * @return array
     */
    public function upload($filePath, $bucket, $prefix = "")
    {
        if (!file_exists($filePath)) {
            return ["flag" => false, "error" => "不存在的文件"];
        }

        $token = $this->getUploadToken($bucket);
        // 上传到七牛后保存的文件名
        !empty($prefix) && $prefix = $prefix . "/";
        $key = $prefix.basename($filePath);
        // 初始化 UploadManager 对象并进行文件的上传。
        $uploadMgr = new UploadManager();
        // 调用 UploadManager 的 putFile 方法进行文件的上传。
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
        if ($err !== null) {
            return ["flag" => false, "error" => $err];
        } else {
            return ["flag" => true, "ret" => $ret];
        }
    }

    /**
     * 删除空间文件
     * @param $key
     * @param $bucket
     * @return array
     */
    public function delete($key, $bucket)
    {
        //初始化BucketManager
        $bucketMgr = $this->getBucketManager();
        //你要测试的空间， 并且这个key在你空间中存在
        //删除$bucket 中的文件 $key
        $err = $bucketMgr->delete($bucket, $key);
        if ($err !== null) {
            return ["flag" => false, "error" => $err];
        } else {
            return ["flag" => true];
        }
    }
}