<?php

namespace app\modules\cloud\components;

use app\modules\cloud\Cloud;
use Yii;
use yii\base\InvalidParamException;
use yii\helpers\FileHelper;

/**
 * Class Storage
 * @property $transport
 */
class Storage extends \yii\base\Component
{
    public $prefix = 'cloud_';

    private $id;
    private $name;
    private $session;

    public function init()
    {
        parent::init();
    }

    /**
     * @param bool|true $autoGenerate
     * @return mixed
     */
    public function getName($autoGenerate = true)
    {
        if ($autoGenerate && $this->name === null) {
            $name = Yii::$app->request->get('name');
            $this->setName($name);
        }
        return $this->name;
    }

    /**
     * @param $value
     */
    public function setName($value)
    {
        $this->name = $this->prefix . $value;
    }

    /**
     * @param $dir
     * @param null $name
     */
    public function moveFileAndClear($dir, $name = null)
    {
        $this->moveFileTo($dir, $name);
        FileHelper::removeDirectory($this->getPath());
        $this->clear($name);
    }


    /**
     * @param $dir
     * @param null $name
     * @param bool $removeCacheDir
     * @param bool $generateNewName
     * @return bool
     * @throws \yii\base\ErrorException
     */
    public function moveFileTo($dir, $name = null, $removeCacheDir = true, $generateNewName = true)
    {
        $dir = Yii::getAlias($dir);
        FileHelper::createDirectory($dir);
        if ($name !== null) {
            $this->setName($name);
        }

        $this->buildId();

        $path = $this->getPath();
        $files = FileHelper::findFiles($path);

        if ($files) {
            if ($generateNewName) {
                foreach ($files as $file) {
                    $ext = pathinfo($file, PATHINFO_EXTENSION);
                    $newFileName = strtr(uniqid(hash('crc32b', rand()), true), ['.' => '']) . '.' . $ext;
                    copy($file, $dir . '/' . $newFileName);
                }
            } else {
                foreach ($files as $file) {
                    copy($file, $dir . '/' . basename($file));
                }
            }
            if ($removeCacheDir) {
                FileHelper::removeDirectory($path);
            }
            return true;
        }
        return false;
    }

    /**
     * @param null $name
     * @return array
     */
    public function getCloudFiles($name = null)
    {
        if ($name !== null) {
            $this->setName($name);
        }

        $this->buildId();

        $path = $this->getPath();
        $files = FileHelper::findFiles($path);
        return $files;
    }

    /**
     * @param null $name
     * @return array
     */
    public function getWebCloudFiles($name = null)
    {
        $files = $this->getCloudFiles($name);
        $path = $this->getPath();
        $webPath = $this->getWebPath();
        foreach ($files as $i => $file) {
            $files[$i] = str_replace($path, $webPath, $file);
        }
        return $files;
    }

    /**
     * @param null $name
     * @param bool|true $clearSession
     */
    public function clear($name = null, $clearSession = true)
    {
        if ($name !== null) {
            $this->setName($name);
        }
        $this->id = null;

        $name = $this->getName(false);
        echo $name;
        if ($clearSession) {
            $this->getSession()->remove($name);
        }
    }

    /**
     * @param $value
     * @return mixed
     */
    public function setId($value)
    {
        return $this->id = $value;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        if ($this->id === null) {
            $this->buildId();
        }
        return $this->id;
    }

    /**
     * @param null $name
     * @return string
     * @throws \yii\base\Exception
     */
    public function getPath($name = null)
    {
        if ($name) {
            $this->setName($name);
        }

        $path = Cloud::getInst()->cloudPath . $this->getId() . '/';
        FileHelper::createDirectory($path);
        return $path;
    }

    /**
     * @return string
     */
    public function getWebPath()
    {
        return Cloud::getInst()->webCloudPath . $this->getId() . '/';
    }

    /**
     * @return mixed|string
     */
    public function buildId()
    {
        $name = $this->getName();
        $id = $this->getSession()->get($name);
        if ($id === null){
            $id = $this->hash(uniqid());
        }
        $this->setId($id);
        $this->getSession()->set($name, $id);
        return $id;
    }

    /**
     * @return mixed|\yii\web\Session
     */
    public function getSession()
    {
        return Yii::$app->session;
    }

    /**
     * @param $path
     * @return mixed|string
     */
    public function hash($path)
    {
        return sprintf('%x', crc32($path . Yii::getVersion()));
    }


    public function removeOldDir($remove_after = null)
    {
        $module = Cloud::getInst();
        $dir = $module->cloudPath;
        if ($remove_after === null) {
            $remove_after = $module->remove_after;
        }


        $handle = opendir($dir);
        if ($handle === false) {
            throw new InvalidParamException("Unable to open directory: $dir");
        }
        $now   = time();
        while (($file = readdir($handle)) !== false) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            if (is_dir($path)) {
                if ($now - filemtime($path) >= $remove_after) { // 60 * 60 * 24 * 2 == 2 days
                    FileHelper::removeDirectory($path);
                }
            }
        }
        closedir($handle);
    }

}