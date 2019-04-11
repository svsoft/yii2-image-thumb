<?php
namespace svsoft\yii\imagethumb\thumbs;

use svsoft\yii\imagethumb\exceptions\DirectoryNotFoundException;
use svsoft\yii\imagethumb\exceptions\ImageProcessingErrorException;
use svsoft\yii\imagethumb\exceptions\FileNotFoundException;

interface ThumbInterface
{

    /**
     * @param $filePath
     * @param $thumbDirPath
     *
     * @return string
     * @throws DirectoryNotFoundException
     * @throws FileNotFoundException
     * @throws ImageProcessingErrorException
     */
    public function create($filePath, $thumbDirPath);

    /**
     * @param $filePath
     *
     * @return string
     */
    public function generateFileName($filePath);
}