<?php
/**
 * Created by PhpStorm.
 * User: viktor
 * Date: 10.04.2019
 * Time: 16:51
 */

namespace svsoft\yii\imagethumb;

use svsoft\yii\imagethumb\exceptions\DirectoryNotFoundException;
use svsoft\yii\imagethumb\exceptions\FileNotFoundException;
use svsoft\yii\imagethumb\exceptions\ImageProcessingErrorException;
use svsoft\yii\imagethumb\thumbs\ThumbInterface;
use yii\helpers\FileHelper;

/**
 * Class ImageThumb
 * @package svsoft\yii\imagethumb
 *
 * @property-read \svsoft\yii\imagethumb\ThumbManager|\svsoft\yii\imagethumb\ThumbManagerInterface $thumbManager
 */
class ThumbStorage implements ThumbStorageInterface
{
    public $dirPath = '@app/web/resize';

    public function __construct($dirPath)
    {
        if (!$dirPath)
            throw new \InvalidArgumentException('Argument dirPath must be set');

        if (!file_exists($dirPath))
        {
            FileHelper::createDirectory($dirPath);
        }
        elseif (!is_dir($dirPath))
        {
            throw new \InvalidArgumentException("\"$dirPath\" in not directory");
        }

        $this->dirPath = $dirPath;
    }

    /**
     * Возвращает путь до файла превью картинки, создает файл если путь не найден
     *
     * @param $filePath
     * @param string|ThumbInterface $thumb ключ из массива $thumbs, либо объект
     *
     * @return string
     * @throws ImageProcessingErrorException
     * @throws FileNotFoundException
     */
    public function create($filePath, ThumbInterface $thumb)
    {
        $thumbFilePath = $this->dirPath . DIRECTORY_SEPARATOR . $thumb->generateFileName($filePath);

        if (!file_exists($thumbFilePath))
        {
            try
            {
                $thumb->create($filePath, $this->dirPath);
            }
            catch(DirectoryNotFoundException $exception) { }
        }

        return $thumbFilePath;
    }
}