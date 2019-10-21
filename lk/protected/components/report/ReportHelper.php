<?php

class ReportHelper
{

    protected $cache = null;

    protected $key = '';
    protected $params = false;

    protected $percent = null;
    protected $nextStep = null;

    protected $cacheLifetime = 86000;
    protected $cachePrefix = 'report_';

    protected $exportPath = 'report';

    public function __construct($params)
    {
        if (is_array($params)) {
            $this->key = md5(serialize($params));
            $this->params = $this->initParams($params);
            $this->getCache()->set($this->cachePrefix.$this->key, $this->params, $this->cacheLifetime);
        } else {
            $this->params = $this->getCache()->get($this->cachePrefix.$params);

            //print_r($this->params);
            $this->key = $params;
        }
    }

    protected function getCache()
    {
        if (!$this->cache) {
            $this->cache = Yii::createComponent(array(
                'class' => 'system.caching.CFileCache',
                'cachePath' => Yii::getPathOfAlias('application.runtime.report.cache'),

            ));
            $this->cache->init();
        }
        return $this->cache;
    }

    protected function initParams($params)
    {
        if (isset($params['report'])) {
            $modelName = '';
            switch ($params['report']) {
                case ReportForm::REPORT_CSE_DELIVERY_COUNT:
                case ReportForm::REPORT_CSE_DELIVERY_MONEY:
                    $modelName = 'CseDelivery';
                    break;
                case ReportForm::REPORT_POST_DELIVERY:
                    $modelName = 'PostDelivery';
                    break;
                case ReportForm::REPORT_APPEAL:
                    $modelName = 'Appeal';
                    break;
            }
            if ($modelName)
                $params['model'] = $modelName;
        }

        if (isset($params['period'])) {
            $start = '';
            $end = '';
            switch ($params['period']) {
                case ReportForm::PERIOD_DAILY:
                    if (isset($params['startDate']) && isset($params['endDate']))
                        list($start, $end) = $this->getTimestampsFromDate($params['startDate'], $params['endDate'], true);
                    break;
                case ReportForm::PERIOD_MONTHLY:
                    if (isset($params['startMonth']) && isset($params['endMonth']))
                        list($start, $end) = $this->getTimestampsFromDate($params['startMonth'], $params['endMonth'], false);
                    break;
            }
            if ($start && $end) {
                $params['startTimestamp'] = $start;
                $params['endTimestamp'] = $end;
            }
        }

        return $params;
    }

    protected function getTimestampsFromDate($start, $end, $day = true)
    {
        $startTimestamp = 0;
        $endTimestamp = 0;
        if ($day) {
            $startTimestamp = CDateTimeParser::parse($start.' 00:00:00','dd.MM.yyyy hh:mm:ss');
            $endTimestamp = CDateTimeParser::parse($end.' 23:59:59','dd.MM.yyyy hh:mm:ss');
        } else {
            $endVals = explode('.', $end);
            if (count($endVals) == 2) {
                $next = $end;
                if ($endVals[0] >= 12) {
                    $next = '01.'.($endVals[1]+1);
                } else {
                    $next = str_pad(($endVals[0]+1), 2, '0', STR_PAD_LEFT).'.'.$endVals[1];
                }
                $startTimestamp = CDateTimeParser::parse('01.'.$start.' 00:00:00','dd.MM.yyyy hh:mm:ss');
                $endTimestamp = CDateTimeParser::parse('01.'.$next.' 00:00:00','dd.MM.yyyy hh:mm:ss')-1;
            }
        }
        return array($startTimestamp, $endTimestamp);
    }

    public function getKey()
    {
        return $this->key;
    }

    public function isLoaded()
    {
        if (!$this->params)
            return false;

        return true;
    }

    /**
     * @return CActiveDataProvider
     */
    protected function getDataProvider()
    {
        if (isset($this->params['model']) && isset($this->params['startTimestamp']) && isset($this->params['endTimestamp'])) {
            $criteria = new CDbCriteria();
            $criteria->scopes = array(
                'client'
            );
            $criteria->with = array(
                'businessCenters' => array(
                    'select' => array('id', 'caption'),
                ),
            );

            return new CActiveDataProvider('User', array(
                'criteria' => $criteria,
                'pagination'=>array(
                    'pageSize'=>20,
                ),
            ));
        }

        return null;
    }

    public function reportStep($step = -1)
    {
        $nextStep = false;

        $dataProvider = $this->getDataProvider();
        if ($dataProvider) {
            $pagination = $dataProvider->getPagination();
            $pagination->setItemCount($dataProvider->getTotalItemCount());

            $totalPages = $pagination->getPageCount();
            $pagination->setCurrentPage($step);

            $creater = $this->getReporter();
            $nextStep = true;
            if ($step < 0) {
                $creater->start();
            } elseif ($step < $totalPages) {
                $creater->step($dataProvider->getData());
            } else {
                $creater->end();
                $nextStep = false;
            }

            if ($nextStep) {
                $this->nextStep = $step+1;
            }

            if ($totalPages > 0) {
                $this->percent = round($step / $totalPages * 100);
                if ($this->percent > 100)
                    $this->percent = 100;
                elseif ($this->percent < 0)
                    $this->percent = 0;
            } else
                $this->percent = 0;

        }

        return $nextStep;
    }

    protected function getReporter()
    {
        $reporter = 'ReportCreater';
        if (isset($this->params['model']) && class_exists($this->params['model'].'ReportCreater'))
            $reporter = $this->params['model'].'ReportCreater';

        return new $reporter($this->key, $this->params);
    }

    public function check()
    {
        return $this->getReporter()->check();
    }

    public function getResult()
    {
        return $this->getReporter()->getResult();
    }

    public function getPercent()
    {
        return $this->percent;
    }

    public function getNextStep()
    {
        return $this->nextStep;
    }

}