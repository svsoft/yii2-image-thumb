<?php
/**
 * Created by PhpStorm.
 * User: viktor
 * Date: 08.04.2019
 * Time: 11:59
 */

namespace svsoft\yii\imagethumb\thumbs;

use Imagine\Image\ImageInterface;
use svsoft\yii\imagethumb\exceptions\DirectoryNotFoundException;
use svsoft\yii\imagethumb\exceptions\ImageProcessingErrorException;
use svsoft\yii\imagethumb\exceptions\FileNotFoundException;
use yii\base\BaseObject;

/**
 *
 * Class Thumb
 * Абстрактный класс основанный на созлании превьеюшек используя библиотеку imagine/imagine
 * от него наследуются классы для создания превью с произвольной логикой
 *
 * @package svsoft\yii\imagethumb\thumbs
 * @property float $size
 */
abstract class AbstractThumb  extends BaseObject implements ThumbInterface
{
    /**
     * @var float
     */
    public $width;

    /**
     * @var float
     */
    public $height;

    /**
     * @var
     */
    static protected $imagine;

    /**
     * Сеттер заполняет свойства width и height, если они пустые
     * @param $size
     */
    public function setSize($size)
    {
        if (empty($this->width))
            $this->width = $size;

        if (empty($this->height))
            $this->height = $size;
    }

    /**
     * @param $filePath
     * @param $thumbDirPath
     *
     * @return string
     * @throws DirectoryNotFoundException
     * @throws FileNotFoundException
     * @throws ImageProcessingErrorException
     */
    function create($filePath, $thumbDirPath)
    {
        if (!is_file($filePath))
            throw new FileNotFoundException('"' . $filePath . '" is not a file');

        if (!is_dir($thumbDirPath))
            throw new DirectoryNotFoundException('"'.$thumbDirPath . '" is not a directory');

        $imagine = new \Imagine\Gd\Imagine();

        try
        {
            $image = $imagine->open($filePath);
        }
        catch(\RuntimeException $exception)
        {
            throw new ImageProcessingErrorException("\"{$filePath}\" is not image");
        }

        $image = $this->process($image);

        $thumbFilePath = $thumbDirPath . DIRECTORY_SEPARATOR . $this->generateFilename($filePath);

        $image->save($thumbFilePath);

        return $thumbFilePath;
    }

    /**
     * Обрабатывает $image своим обработчиком
     *
     * @param ImageInterface $image
     *
     * @return mixed
     * @throws ImageProcessingErrorException
     */
    abstract protected function process(ImageInterface $image);


    /**
     * @return \Imagine\Gd\Imagine
     */
    static function getImagine()
    {
        if (self::$imagine === null)
            self::$imagine = new \Imagine\Gd\Imagine();

        return self::$imagine;
    }
}