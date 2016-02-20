<?php

namespace app\modules\cloud\components;

use app\modules\cloud\Cloud;
use Yii;
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
     * @param $name
     * @param bool|true $removeCacheDir
     * @return bool
     */
    public function moveFileTo($dir, $name = null, $removeCacheDir = true)
    {
        if ($name !== null) {
            $this->setName($name);
        }

        $this->buildId();

        $path = $this->getPath();
        $files = FileHelper::findFiles($path);

        if ($files) {
            foreach ($files as $file) {
                rename($file, Yii::getAlias($dir) . '/' . basename($file));
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
     * @return string
     */
    public function getPath()
    {
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

}