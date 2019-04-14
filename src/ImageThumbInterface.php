<?php
/**
 * Created by PhpStorm.
 * User: viktor
 * Date: 10.04.2019
 * Time: 16:51
 */

namespace svsoft\yii\imagethumb;

use yii\base\ErrorException;

/**
 * Class ThumbManager
 * @package svsoft\yii\imagethumb
 */
interface ImageThumbInterface
{
    /**
     * Обертка для метода getThumbUrl, с перехватом исключений
     *
     * @param $filePath
     * @param $thumb
     *
     * @return null|string
     * @throws ErrorException
     */
    function thumb($filePath, $thumb);
}