<?php
/**
 * Created by PhpStorm.
 * User: viktor
 * Date: 10.04.2019
 * Time: 16:51
 */

namespace svsoft\yii\imagethumb;

use svsoft\yii\imagethumb\exceptions\FileNotFoundException;
use svsoft\yii\imagethumb\exceptions\ImageProcessingErrorException;
use svsoft\yii\imagethumb\thumbs\ThumbInterface;
use yii\base\ErrorException;
use yii\base\InvalidConfigException;

/**
 * Class ThumbManager
 * @package svsoft\yii\imagethumb
 */
interface ImageThumbInterface
{
    /**
     * Возвращает путь до файла превью картинки, создает файл если путь не найден
     *
     * @param $filePath
     * @param string|ThumbInterface $thumb ключ из массива $thumbs, либо объект
     *
     * @return string
     * @throws InvalidConfigException
     * @throws ImageProcessingErrorException
     * @throws FileNotFoundException
     */
    public function create($filePath, $thumb);

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