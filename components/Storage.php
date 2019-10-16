<?php

namespace app\components;

use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class Storage extends Component
{
    /** @var string абсолютный адрес директории, куда сохранять загружаемые файлы */
    public $storagePath;

    /** @var string адрес, по которому будет доступен файл */
    public $storageUri;

    public function init()
    {
        parent::init();

        if ($this->storagePath === null) {
            $this->storagePath = '@webroot/uploads';
        }

        if ($this->storageUri === null) {
            $this->storageUri= '@web/uploads';
        }
    }

    /**
     * Генерирует название файла и сохраняет его в локальное хранилище. Бросается исключение,
     * если не удалось сохранить файл или файл уже существует. Возвращает сгенерированоое имя файла.
     *
     * @param string $tempFileName
     * @param string $targetExtension
     * @param bool $deleteTempFile
     * @return string
     * @throws Exception
     */
    public function saveFile(string $tempFileName, string $targetExtension, bool $deleteTempFile = true): string
    {
        $fileName = $this->generateFileName($tempFileName, $targetExtension);

        $fileFullPath = $this->getFileFullPath($fileName);
        $fileFullPath = FileHelper::normalizePath($fileFullPath);

        if (file_exists($fileFullPath)) {
            throw new Exception("File $fileFullPath already exists");
        }

        FileHelper::createDirectory(dirname($fileFullPath));

        if (copy($tempFileName, $fileFullPath)) {
            if ($deleteTempFile && !unlink($tempFileName)) {
                Yii::warning("Cannot delete temp file $tempFileName", __METHOD__);
            }
            Yii::info("File $fileFullPath saved", __METHOD__);
            return $fileName;
        }

        throw new Exception('File could not be saved');
    }

    /**
     * Удаляет заданный файл
     *
     * @param string $filename
     * @return bool true, если файл удалился, false - в противном случае
     */
    public function deleteFile(string $filename): bool
    {
        $fileFullPath = $this->getFileFullPath($filename);
        if (file_exists($fileFullPath)) {
            if (unlink($fileFullPath)){
                Yii::info("File $fileFullPath deleted", __METHOD__);
                return true;
            }
            return false;
        }
        Yii::warning("File $fileFullPath does not exist", __METHOD__);
        return true;
    }

    /**
     * Генерирует имя файла на основе md5 хэша временного файла
     *
     * @param string $tempFileName
     * @param string $targetExtension
     * @return string
     */
    public function generateFileName(string $tempFileName, string $targetExtension): string
    {
        $hash = md5_file($tempFileName);
        $fileName = substr_replace($hash, '/', 6, 0);
        $fileName = substr_replace($fileName, '/', 3, 0);
        $fileName .= '.' . $targetExtension;

        return $fileName;
    }

    /**
     * Возвращает абсолютный адрес файла
     *
     * @param string $fileName
     * @return string
     */
    public function getFileFullPath(string $fileName): string
    {
        return rtrim(Yii::getAlias($this->storagePath), '/') . '/' . $fileName;
    }

    /**
     * Возвращает uri адрес файла, доступный для загрузки из веба
     *
     * @param string $fileName
     * @return string
     */
    public function getFileUri(string $fileName): string
    {
        return rtrim(Yii::getAlias($this->storageUri), '/') . '/' . $fileName;
    }
}