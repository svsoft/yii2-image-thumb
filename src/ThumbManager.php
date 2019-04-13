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

/**
 * Class ThumbManager
 * @package svsoft\yii\imagethumb
 */
class ThumbManager extends BaseObject implements ThumbManagerInterface
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

    public function init()
    {
        parent::init();

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
}