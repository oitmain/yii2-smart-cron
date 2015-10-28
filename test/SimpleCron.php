<?php

namespace oitmain\smartcron\test;

use oitmain\smartcron\models\base\BaseCron;
use Yii;

class SimpleCron extends BaseCron
{

    protected $_i = 20;

    public function getSchedule()
    {
        return '0/5 * * * *';
    }

    public function getName()
    {
        return 'simple_cron';
    }

    protected function getCacheKey($cronId, $cronDetailId)
    {
        return 'simple_cron_' . $cronId;
    }

    public function eventReset()
    {
        $this->_i = 20;
    }

    public function eventLoop($cronId, $cronDetailId)
    {
        $this->_i--;

        var_dump($this->_i);

        sleep(1);

        return false;

        return $this->_i > 0;
    }

    public function eventFinished($cronId, $cronDetailId)
    {
        Yii::$app->cache->delete($this->getCacheKey($cronId, $cronDetailId));
    }

    public function eventPaused($cronId, $cronDetailId)
    {
        Yii::$app->cache->set($this->getCacheKey($cronId, $cronDetailId), $this->_i);
    }

    public function eventResume($cronId, $cronDetailId)
    {
        $this->_i = Yii::$app->cache->get($this->getCacheKey($cronId, $cronDetailId));
        if (!$this->_i) $this->_i = 0;
    }

    public function cleanupFailed($cronId, $cronDetailId)
    {
        Yii::$app->cache->delete($this->getCacheKey($cronId, $cronDetailId));
    }

    public function cleanupTimedOut($cronId, $cronDetailId)
    {
        Yii::$app->cache->delete($this->getCacheKey($cronId, $cronDetailId));
    }

    public function cleanupDied($cronId, $cronDetailId)
    {
        Yii::$app->cache->delete($this->getCacheKey($cronId, $cronDetailId));
    }

}