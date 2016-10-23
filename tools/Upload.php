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
    private $_maxSize = 2 * 1024 * 24;

    private $_allowExts = ["jpg", "gif", "png", "jpeg"];

    private $_saveRule = ["rule" => "default", "format" => "Ymd"];

    private $_thumb = false;

    private $_thumbSize = [400, 400];

    /**
     * 单个文件上传
     * @param array $params
     * @return string
     */
    private function _saveRule($params = array('rule' => 'default', 'format' => 'Ymd'))
    {
        $path = '';
        switch ($params['rule']) {
            case 'custom':
                $path .= $params['string'] . '/';
                break;
            default:
                $paths = isset($params['format']) ? date($params['format']) . '/' : date('Ym') . '/';
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
        $upload->saveRule = uniqid();
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