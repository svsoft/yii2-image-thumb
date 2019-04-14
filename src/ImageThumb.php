<?php
/**
 * Created by PhpStorm.
 * User: viktor
 * Date: 10.04.2019
 * Time: 16:51
 */

namespace svsoft\yii\imagethumb;

use svsoft\yii\imagethumb\thumbs\ThumbInterface;
use yii\base\BaseObject;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

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
     * @var ThumbStorageInterface
     */
    protected $thumbStorage;

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

        $this->thumbStorage = new ThumbStorage($this->thumbDirPath);
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
            $thumbFilePath = $this->thumbStorage->create($filePath, $thumb);
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