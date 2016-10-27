<?php

namespace app\models;

/**
 * @link https://github.com/JohnZhang360/Simple-Yii2.git
 */

use \zbsoft\base\Object;

/**
 * 文章列表过滤条件
 *
 * @property integer $year
 * @property integer $month
 * @property string $search
 * @property string $tags
 */
class PostFilter extends Object
{
    private $_year;
    private $_month;
    private $_search;
    private $_tags;

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->_tags;
    }

    /**
     * @param mixed $tags
     */
    public function setTags($tags)
    {
        $this->_tags = $tags;
    }

    /**
     * @return mixed
     */
    public function getYear()
    {
        return $this->_year;
    }

    /**
     * @param mixed $year
     */
    public function setYear($year)
    {
        $this->_year = $year;
    }

    /**
     * @return mixed
     */
    public function getMonth()
    {
        return $this->_month;
    }

    /**
     * @param mixed $month
     */
    public function setMonth($month)
    {
        $this->_month = $month;
    }

    /**
     * @return mixed
     */
    public function getSearch()
    {
        return $this->_search;
    }

    /**
     * @param mixed $search
     */
    public function setSearch($search)
    {
        $this->_search = $search;
    }
}