<?php
/**
 * Created by PhpStorm.
 * User: viktor
 * Date: 10.04.2019
 * Time: 16:51
 */

namespace svsoft\yii\imagethumb;

use svsoft\yii\imagethumb\thumbs\Thumb;
use svsoft\yii\imagethumb\thumbs\ThumbInterface;
use yii\base\BaseObject;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\FileHelper;

/**
 * Class ThumbManager
 * @package svsoft\yii\imagethumb
 */
class ThumbManager extends BaseObject
{
    /**
     * @var ThumbInterface[]|array
     */
    public $thumbs = [];

    /**
     * Класс который будет подстваляться в элемент массива $thumbs, если не заполнен ключ class
     *
     * @var
     */
    public $defaultThumbClass;

    public $thumbDirPath = '@app/web/resize';

    public $thumbWebDirPath = '@web/resize';

    /**
     * @throws InvalidConfigException
     * @throws \yii\base\Exception
     */
    public function init()
    {
        parent::init();

        $this->thumbDirPath = Yii::getAlias($this->thumbDirPath);

        $this->thumbWebDirPath = Yii::getAlias($this->thumbWebDirPath);

        if (!$this->thumbDirPath)
            throw new InvalidConfigException('Property thumbDirPath must be set');

        if (!$this->thumbWebDirPath)
            throw new InvalidConfigException('Property thumbWebDirPath must be set');

        if (!file_exists($this->thumbDirPath))
        {
            FileHelper::createDirectory($this->thumbDirPath);
        }
        elseif (!is_dir($this->thumbDirPath))
        {
            throw new InvalidConfigException("\"{$this->thumbDirPath}\" in not directory");
        }

        if (!$this->defaultThumbClass)
            $this->defaultThumbClass = Thumb::class;
    }

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
    public function getThumb($id)
    {
        if (empty($this->thumbs[$id]))
            throw new \InvalidArgumentException("Thumb with id \"{$id}\" not found");

        $thumb = $this->thumbs[$id];
        if (!$thumb instanceof ThumbInterface)
        {
            if (empty($thumb['class']))
                $thumb['class'] = $this->defaultThumbClass;

            /** @var ThumbInterface $thumb */
            $thumb = Yii::createObject($thumb);

            $this->thumbs[$id] = $thumb;
        }

        return $thumb;
    }

    /**
     * Возвращает Урл до превьюшки
     *
     * @param $filePath
     * @param string|ThumbInterface $thumb ключ из массива $thumbs, либо объект
     *
     * @return null|string
     * @throws \yii\base\InvalidConfigException
     */
    public function getThumbUrl($filePath, $thumb)
    {
        if (!$thumb instanceof ThumbInterface)
            $thumb = $this->getThumb($thumb);

        if (!$thumbFilePath = $this->getThumbFilePath($filePath, $thumb))
            return null;

        return $this->thumbWebDirPath . DIRECTORY_SEPARATOR . $thumb->generateFileName($filePath);
    }

    /**
     * Возвращает путь до файла превью картинки, создает файл если путь не найден
     *
     * @param $filePath
     * @param string|ThumbInterface $thumb ключ из массива $thumbs, либо объект
     *
     * @return null|string
     * @throws \yii\base\InvalidConfigException
     */
    public function getThumbFilePath($filePath, $thumb)
    {
        if (!$thumb instanceof ThumbInterface)
            $thumb = $this->getThumb($thumb);

        $thumbFilePath = $this->thumbDirPath . DIRECTORY_SEPARATOR . $thumb->generateFileName($filePath);

        if (!file_exists($thumbFilePath))
        {
            try
            {
                $thumb->create($filePath, $this->thumbDirPath);
            }
            catch(\Throwable $exception)
            {
                Yii::error($exception->getMessage());

                return null;
            }
        }

        return $thumbFilePath;
    }
}