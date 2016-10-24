<?php
/**
 * 文件上传
 *
 * @author        zhengchengji <274543025@qq.com>
 * @copyright     Copyright (c) 2013-2014 zhengchengji. All rights reserved.
 * @link          http://www.361dl.com
 * @lasttime      2013-10-9
 * @version       v1.0
 */
namespace app\tools;

use zbsoft\helpers\UploadFile;

class Upload
{
    private $_maxSize = 2 * 1024 * 1024;

    private $_allowExts = ["jpg", "gif", "png", "jpeg", "ico"];

    private $_saveRule = ["rule" => "default", "format" => "Ymd"];

    private $_thumb = false;

    private $_thumbSize = [400, 400];

    private $_saveName = "";

    private $_prefix = "";

    /**
     * @return int
     */
    public function getMaxSize()
    {
        return $this->_maxSize;
    }

    /**
     * @param int $maxSize
     */
    public function setMaxSize($maxSize)
    {
        $this->_maxSize = $maxSize;
    }

    /**
     * @return array
     */
    public function getAllowExts()
    {
        return $this->_allowExts;
    }

    /**
     * @param array $allowExts
     */
    public function setAllowExts($allowExts)
    {
        $this->_allowExts = $allowExts;
    }

    /**
     * @return array
     */
    public function getSaveRule()
    {
        return $this->_saveRule;
    }

    /**
     * @param array $saveRule
     */
    public function setSaveRule($saveRule)
    {
        $this->_saveRule = $saveRule;
    }

    /**
     * @return boolean
     */
    public function isThumb()
    {
        return $this->_thumb;
    }

    /**
     * @param boolean $thumb
     */
    public function setThumb($thumb)
    {
        $this->_thumb = $thumb;
    }

    /**
     * @return array
     */
    public function getThumbSize()
    {
        return $this->_thumbSize;
    }

    /**
     * @param array $thumbSize
     */
    public function setThumbSize($thumbSize)
    {
        $this->_thumbSize = $thumbSize;
    }

    /**
     * @return string
     */
    public function getSaveName()
    {
        return $this->_saveName;
    }

    /**
     * @param string $saveName
     */
    public function setSaveName($saveName)
    {
        $this->_saveName = $saveName;
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->_prefix;
    }

    /**
     * @param string $prefix
     */
    public function setPrefix($prefix)
    {
        $this->_prefix = $prefix;
    }

    /**
     * 单个文件上传
     * @param array $params
     * @return string
     */
    private function _saveRule($params)
    {
        $path = '';
        switch ($params['rule']) {
            case 'custom':
                $path .= $params['string'] . '/';
                break;
            default:
                $format = isset($params['format']) ? $params['format'] : 'Ym';
                $paths = date(implode("/", str_split($format))) . "/";
                $path .= $paths;
                break;
        }
        return 'uploads/' . $path;
    }

    /**
     * 单个文件上传
     * @param $fileFields
     * @return string
     */
    public function upload($fileFields)
    {
        $upload = new UploadFile();
        // 设置上传文件大小
        $upload->maxSize = $this->_maxSize;
        // 设置上传文件类型
        $upload->allowExts = $this->_allowExts;
        // 设置附件上传目录
        $upload->savePath = self::_saveRule($this->_saveRule);
        // 设置需要生成缩略图，仅对图像文件有效
        $upload->thumb = $this->_thumb;
        // 设置需要生成缩略图的文件后缀
        $upload->thumbPrefix = 'thumb_'; // 生产2张缩略图
        // 设置缩略图最大宽度
        $upload->thumbMaxWidth = $this->_thumbSize[0];
        // 设置缩略图最大高度
        $upload->thumbMaxHeight = $this->_thumbSize[1];
        // 设置上传文件规则
        $prefix = empty($this->_prefix) ? "" : $this->_prefix . "-";
        $upload->saveRule = $prefix . ($this->_saveName === "" ? uniqid() : $this->_saveName);
        // 删除原图
        $upload->thumbRemoveOrigin = false;
        $file = $upload->uploadOne($fileFields);

        if (!is_array($file)) {
            return $upload->getErrorMsg();
        } else {
            // 重新整理返回数据
            $fileget['name'] = $file[0]['name'];
            $fileget['type'] = $file[0]['type'];
            $fileget['size'] = $file[0]['size'];
            $fileget['extension'] = $file[0]['extension'];
            $fileget['savepath'] = $file[0]['savepath'];
            $fileget['savename'] = $file[0]['savename'];
            $fileget['hash'] = $file[0]['hash'];
            $fileget['pathname'] = $upload->savePath . $file[0]['savename'];

            // 缩略图返回
            if (true == $upload->thumb) {
                $fileget['thumb'] = $upload->thumbPrefix . $file[0]['savename'];
                $fileget['paththumbname'] = $upload->savePath . $upload->thumbPrefix . $file[0]['savename'];
            }
            return $fileget;
        }
    }
}