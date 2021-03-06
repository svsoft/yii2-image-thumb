<?php
/**
 * Created by PhpStorm.
 * User: viktor
 * Date: 08.04.2019
 * Time: 11:59
 */

namespace svsoft\yii\imagethumb\thumbs;

use Imagine\Image\ImageInterface;

class ThumbFields  extends AbstractThumb
{
    public $color = '#FFFFFF';
    /**
     * @var float 0..1
     */
    public $opacity = 0;

    /**
     * @param $filePath
     *
     * @return string
     */
    public function generateFileName($filePath)
    {
        $info = pathinfo($filePath);

        return "{$info['filename']}-fields-{$this->width}x{$this->height}.{$info['extension']}";
    }

    /**
     * @param ImageInterface $image
     *
     * @return ImageInterface|mixed
     */
    protected function process(ImageInterface $image)
    {
        $imagine = $this::getImagine();
        $size    = new \Imagine\Image\Box($this->width, $this->height);

        /** @var \Imagine\Image\ImageInterface $image */
        $image = $image->thumbnail($size, ImageInterface::THUMBNAIL_INSET);
        $width = $image->getSize()->getWidth();
        $height = $image->getSize()->getHeight();
        $color = (new \Imagine\Image\Palette\RGB())->color($this->color, intval($this->opacity * 100));
        $image = $imagine->create($size, $color)->paste($image, new \Imagine\Image\Point(($size->getWidth() - $width)/2, ($size->getHeight() - $height)/2));

        return $image;
    }

}