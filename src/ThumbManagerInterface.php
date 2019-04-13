<?php
/**
 * Created by PhpStorm.
 * User: viktor
 * Date: 10.04.2019
 * Time: 16:51
 */

namespace svsoft\yii\imagethumb;

use svsoft\yii\imagethumb\thumbs\ThumbInterface;

/**
 * Class ThumbManager
 * @package svsoft\yii\imagethumb
 */
interface ThumbManagerInterface
{
    /**
     *
     * Поключу получает элемент из массива $thumbs,
     * если элемент массив конфигурация создает соответствующий обект реализующий интерфест ThumbInterface
     *
     * @param $id
     *
     * @return ThumbInterface
     * @throws \yii\base\InvalidConfigException
     */
    public function getThumb($id);
}