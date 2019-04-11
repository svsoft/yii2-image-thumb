<?php
/**
 * Created by PhpStorm.
 * User: viktor
 * Date: 08.04.2019
 * Time: 11:59
 */

namespace svsoft\yii\imagethumb\thumbs;

use Imagine\Image\ImageInterface;

/**
 * Class Thumb
 * @package svsoft\yii\imagethumb\thumbs
 */
class Thumb extends AbstractThumb
{
    public $mode;

    const MODE_INSET = ImageInterface::THUMBNAIL_INSET;

    const MODE_OUTBOUND  = ImageInterface::THUMBNAIL_OUTBOUND;

    public function init()
    {
        if (empty($this->mode))
            $this->mode = self::MODE_INSET;

        parent::init();
    }

    /**
     * @param $filePath
     *
     * @return string
     */
    public function generateFilename($filePath)
    {
        $info = pathinfo($filePath);

        return "{$info['filename']}-basic-{$this->width}x{$this->height}-{$this->mode}.{$info['extension']}";
    }

    /**
     * @param ImageInterface $image
     *
     * @return ImageInterface
     */
    protected function process(ImageInterface $image)
    {
        $size    = new \Imagine\Image\Box($this->width, $this->height);

        $image = $image->thumbnail($size, $this->mode);

        return $image;
    }

}