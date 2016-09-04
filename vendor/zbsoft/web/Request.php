<?php
/**
 * @link https://github.com/JohnZhang360/zgjian-framework
 */

namespace zbsoft\web;

class Request
{
    public function getQueryParams()
    {
        return $_GET;
    }
}