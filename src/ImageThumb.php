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
use svsoft\yii\imagethumb\thumbs\Thumb;
use svsoft\yii\imagethumb\thumbs\ThumbInterface;
use yii\base\BaseObject;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;

/**
 * Class ImageThumb
 * @package svsoft\yii\imagethumb
 *
 * @property-read \svsoft\yii\imagethumb\ThumbManager|\svsoft\yii\imagethumb\ThumbManagerInterface $thumbManager
 */
class ImageThumb extends BaseObject implements ImageThumbInterface
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

    public $blankFilePath;

    /**
     * @var ThumbManagerInterface|array
     */
    public $thumbManager;

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


        $this->thumbManager = ArrayHelper::merge([
            'class' => ThumbManager::class,
            'thumbs' => $this->thumbs,
            'defaultThumbClass' => $this->defaultThumbClass,
        ], (array)$this->thumbManager);

        $this->thumbManager = Yii::createObject($this->thumbManager);

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
    public function create($filePath, $thumb)
    {
        $thumb = $this->getInstanceThumb($thumb);

        $thumbFilePath = $this->thumbDirPath . DIRECTORY_SEPARATOR . $thumb->generateFileName($filePath);

        if (!file_exists($thumbFilePath))
        {
            try
            {
                $thumb->create($filePath, $this->thumbDirPath);
            }
            catch(DirectoryNotFoundException $exception) { }
        }

        return $thumbFilePath;
    }

    /**
     * Возвращает урл до файла превью
     *
     * @param $filePath
     * @param $thumb
     *
     * @return null|string
     * @throws InvalidConfigException
     */
    function thumb($filePath, $thumb)
    {
        if (!$filePath && $this->blankFilePath)
            $filePath = $this->blankFilePath;

        if (!$filePath)
            return null;

        try
        {
            $thumbFilePath = $this->create($filePath, $thumb);
            $thumbFileName =  pathinfo($thumbFilePath, PATHINFO_BASENAME);
        }
        catch(\Throwable $exception)
        {
            \Yii::error($exception->getMessage());

            $thumb = $this->getInstanceThumb($thumb);
            $thumbFileName = $thumb->generateFileName($filePath);
        }

        $url = $this->thumbWebDirPath . DIRECTORY_SEPARATOR . $thumbFileName;

        return $url;
    }

    /**
     * @param $thumb
     *
     * @return ThumbInterface
     * @throws InvalidConfigException
     */
    protected function getInstanceThumb($thumb)
    {
        if (!$thumb instanceof ThumbInterface)
            $thumb = $this->thumbManager->getThumb($thumb);

        return $thumb;
    }
}