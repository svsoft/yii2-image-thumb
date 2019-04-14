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
use yii\base\InvalidConfigException;

/**
 * Class ThumbManager
 * @package svsoft\yii\imagethumb
 */
interface ThumbStorageInterface
{
    /**
     * Создает превью и возвращает путь до файла
     *
     * @param $filePath
     * @param ThumbInterface $thumb
     *
     * @return string
     * @throws InvalidConfigException
     * @throws ImageProcessingErrorException
     * @throws FileNotFoundException
     */
    public function create($filePath, ThumbInterface $thumb);

}