<?php

class CseDeliveryReportCreater extends ReportCreater
{

    protected $dataFile = null;

    protected $resultFile = null;

    protected $config = array();

    protected $delimiter = ';';

    protected $enclosure = '"';

    protected $escape = '\\';

    protected $path = 'application.runtime.report';

    protected function init()
    {
        $this->dataFile = Yii::getPathOfAlias($this->path). DIRECTORY_SEPARATOR. $this->key . '_data.csv';
        $this->resultFile = Yii::getPathOfAlias($this->path). DIRECTORY_SEPARATOR. $this->key . '_result.zip';
        $this->config = array(
            'organization' => Yii::t('cse_delivery_report_creater', 'organization'),
        );

        if (isset($this->params['startTimestamp']) && isset($this->params['endTimestamp']) && isset($this->params['period'])) {
            $timestampInterval = 24*60*60;
            $dateFormat = 'd.m.Y';
            switch ($this->params['period']) {
                case ReportForm::PERIOD_DAILY:
                    $dateFormat = 'd.m.Y';
                    break;
                case ReportForm::PERIOD_MONTHLY:
                    $dateFormat = 'm.Y';
                    break;
            }
            $this->params['dateFormat'] = $dateFormat;
            for ($i = $this->params['startTimestamp']; $i < $this->params['endTimestamp']; $i += $timestampInterval) {
                $key = date($dateFormat, $i);
                if (!isset($this->config[$key])) {
                    $this->config[$key] = $key;
                }
            }
        }
    }

    public function check()
    {
        if (file_exists($this->resultFile)) {
            return filemtime($this->resultFile);
        }

        return false;
    }

    public function start()
    {
        if (file_exists($this->dataFile))
            @unlink($this->dataFile);
        if (file_exists($this->resultFile))
            @unlink($this->resultFile);
        $fh = fopen($this->dataFile, 'a');
        if ($fh) {
            fputcsv($fh, array_values($this->config), $this->delimiter, $this->enclosure);
            fclose($fh);
        }
    }

    public function step($data)
    {
        $fh = fopen($this->dataFile, 'a');
        if ($fh) {
            foreach ($data as $item) {
                $itemData = $this->config;
                foreach ($itemData as $key => &$value) {
                    $value = 0;
                }
                $itemData['organization'] = $item->organization;
                $model = new $this->params['model'];
                $where = 'user_id = :selected_user_id AND timestamp >= :start_timestamp AND timestamp <= :end_timestamp';
                $whereParams = array(
                    ':selected_user_id' => $item->id,
                    ':start_timestamp' => $this->params['startTimestamp'],
                    ':end_timestamp' => $this->params['endTimestamp'],
                );
                $values = Yii::app()->db->createCommand();
                if ($this->params['report'] == ReportForm::REPORT_CSE_DELIVERY_COUNT)
                    $values = $values->select('COUNT(id) AS value, DATE(FROM_UNIXTIME(timestamp)) AS date');
                elseif ($this->params['report'] == ReportForm::REPORT_CSE_DELIVERY_MONEY) {
                    $values = $values->select('SUM(price) AS value, DATE(FROM_UNIXTIME(timestamp)) AS date');
                    if ($this->params['cseDeliveryPaymentMethod'] == ReportForm::CSE_DELIVERY_PAYMENT_METHOD_CASHLESS) {
                        $where .= ' AND payment_method = :payment_method_cashless';
                        $whereParams[':payment_method_cashless'] = CseDelivery::PAYMENT_METHOD_CASHLESS;
                    } else {
                        $where .= ' AND payment_method = :payment_method_cash';
                        $whereParams[':payment_method_cash'] = CseDelivery::PAYMENT_METHOD_CASH;
                    }
                }
                $values = $values->from($model->tableName())
                    ->where($where, $whereParams)
                    ->group('date')
                    ->queryAll();

                foreach($values as $record) {
                    $key = date($this->params['dateFormat'], strtotime($record['date']));
                    $itemData[$key] += $record['value'];
                }

                fputcsv($fh, $itemData, $this->delimiter, $this->enclosure);
            }
            fclose($fh);
        }
    }

    public function end()
    {
        if (isset($this->params['format'])) {
            if ($this->params['format'] == ReportForm::FORMAT_CSV_WIN) {
                $file = file_get_contents($this->dataFile);
                $file = iconv("utf-8", "windows-1251", $file);
                file_put_contents($this->dataFile, $file);
            }
        }

        if (file_exists($this->dataFile)) {
            Yii::app()->zip->makeZip(array($this->dataFile), $this->resultFile);
        }
        if (file_exists($this->resultFile))
            return filemtime($this->resultFile);

        return false;
    }

    public function getResult()
    {
        if (file_exists($this->resultFile)) {
            $filename = $this->key . '.zip';

            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=\"".$filename."\"");
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: ".filesize($this->resultFile));

            @readfile($this->resultFile);
        } else
            parent::getResult();
    }

}