<?php

class CseApi extends CApplicationComponent
{

    const TYPE_COUNTRY = 0;
    const TYPE_REGION = 1;
    const TYPE_AREA = 2;
    const TYPE_CITY = 3;
    const TYPE_VILLAGE = 4;
    const TYPE_STREET = 5;
    const TYPE_OTHER = 6;

    public $apiUrl = 'https://service.cse-cargo.ru/JSONWebService.asmx/';

    public $webApiUrl = 'http://lk.cse.ru';
    public $webPaths = array(
        'createOrder' => '/order/create',
        'viewOrder' => '/order/view/',
    );

    public $login = '';

    public $password = '';

    protected $_curl = null;

    protected $webApi = null;

    public function init()
    {
        if (!$this->login && !$this->password) {
            if (isset(Yii::app()->params['cseLogin']) && isset(Yii::app()->params['csePassword'])) {
                $this->login = Yii::app()->params['cseLogin'];
                $this->password = Yii::app()->params['csePassword'];
            }
        }
    }

    protected function getCurl()
    {
        if (!$this->_curl)
            $this->_curl = new Curl();

        return $this->_curl;
    }

    protected function parseJSON($data)
    {
        return function_exists('json_decode') ? json_decode($data, true) : CJSON::decode($data, true);
    }

    protected function createUrl($function, $data)
    {
        $elements = array();
        if (is_array($data)) {
            if (!isset($data['login']))
                $data['login'] = $this->login;
            if (!isset($data['password']))
                $data['password'] = $this->password;

            foreach ($data as $key => $value) {
                $elements[] = urlencode($key).'='.urlencode($value);
            }
        }

        return $this->apiUrl.$function.'?'.implode('&',$elements);
    }

    protected function request($url, $decode = true)
    {
        $curl = $this->getCurl();
        $response = $curl->url($url)->run();
        if ($decode)
            $response = $this->parseJSON($response);

        return $response;
    }

    public function getGeographyData($id = '', $search = '')
    {
        $url = $this->createUrl('GetReference', array(
            'reference' => 'Geography',
            'inGroup' => $id,
            'search' => $search,
        ));

        return $this->request($url);
    }

    public function getCountries($search = '')
    {
        $response = $this->getGeographyData('', $search);

        $data = array();
        if (is_array($response) && isset($response['items'])) {
            foreach ($response['items'] as $item) {
                if (isset($item['code']) && isset($item['name']) && isset($item['type']) && $item['type'] == self::TYPE_COUNTRY) {
                    $data[$item['code']] = $item['name'];
                }
            }
        }

        return $data;
    }

    public function getRegions($id, $search = '')
    {
        $response = $this->getGeographyData($id, $search);

        $data = array();
        if (is_array($response) && isset($response['items'])) {
            foreach ($response['items'] as $item) {
                if (isset($item['code']) && isset($item['name']) && isset($item['type']) && $item['type'] == self::TYPE_REGION) {
                    $data[$item['code']] = $item['name'];
                }
            }
        }

        return $data;
    }

    public function getCities($id, $search = '', $types = array(self::TYPE_CITY, self::TYPE_VILLAGE))
    {
        $response = $this->getGeographyData($id, $search);

        $data = array();
        if (is_array($response) && isset($response['items'])) {
            foreach ($response['items'] as $item) {
                if (isset($item['code']) && isset($item['name']) && isset($item['type']) && in_array($item['type'], $types)) {
                    $data[$item['code']] = $item['name'];
                }
            }
        }

        return $data;
    }

    public function getCitiesEx($id, $search = '', $types = array(self::TYPE_CITY, self::TYPE_VILLAGE))
    {
        $response = $this->getGeographyData($id, $search);

        $data = array();
        if (is_array($response) && isset($response['items'])) {
            foreach ($response['items'] as $item) {
                if (isset($item['code']) && isset($item['name']) && isset($item['type']) && in_array($item['type'], $types)) {
                    $data[$item['code']] = array(
                        'name' => $item['name'],
                        'type' => $item['type'],
                    );
                }
            }
        }

        return $data;
    }

    public function getUrgencies($search = '')
    {
        $url = $this->createUrl('GetReference', array(
            'reference' => 'Urgencies',
            'inGroup' => '',
            'search' => $search,
        ));

        $response = $this->request($url);

        $data = array();
        if (is_array($response) && isset($response['items'])) {
            foreach ($response['items'] as $item) {
                if (isset($item['code']) && isset($item['name'])) {
                    $data[$item['code']] = $item['name'];
                }
            }
        }

        return $data;
    }

    protected function getWebApi()
    {
        if (!$this->webApi)
            $this->webApi = new CseWebApi($this->webApiUrl, $this->login, $this->password, $this->webPaths);

        return $this->webApi;
    }

    public function saveOrder(array $params)
    {
        $webApi = $this->getWebApi();
        if ($webApi->isLogined()) {
            $data = $webApi->saveOrder($params);
            Yii::log('return raw '.$data, 'info', 'components.cseApi');
            $data = $this->parseJSON($data);
            Yii::log('return '.is_array($data) ? print_r($data, true) : $data, 'info', 'components.cseApi');
            return $data;
            if (is_array($data)) {

                if (isset($data[0]) && isset($data[0]['documentsNumbers']) && isset($data[0]['documentsNumbers'][0])) {
                    return array(
                        'documentNumber' => $data[0]['documentsNumbers'][0],
                    );
                }

                return $data;
            }
        } else {
			Yii::log('Could not connect to CSE', 'info', 'components.cseApi');
		}
        return false;
    }

    public function getCompanies()
    {
        $result = array();

        $webApi = $this->getWebApi();
        if ($webApi->isLogined()) {
            $result = $webApi->getCompanies();
        }
        return $result;
    }

    public function getContactPersons()
    {
        $result = array();

        $webApi = $this->getWebApi();
        if ($webApi->isLogined()) {
            $result = $webApi->getContactPersons();
        }
        return $result;
    }

    public function printDocument($cseId, $documentType = 'waybill')
    {
        $url = $this->createUrl('PrintDocument', array(
            'documentType' => $documentType,
            'number' => $cseId,
            'numberType' => 'InternalNumber',
            'formName' => 'Waybill',
        ));

        $data = $this->request($url);

        $file = '';
        if (is_array($data) && isset($data['bData']) && isset($data['format']) && is_array($data['bData'])) {
            foreach ($data['bData'] as $dataPart) {
                $file .= pack('C', $dataPart);
            }
            return $file;
        }

        return false;
    }

    public function tracking($cseId, $documentType = 'order')
    {
        $url = $this->createUrl('Tracking', array(
            'documentType' => $documentType,
            'number' => $cseId,
            'numberType' => 'InternalNumber',
        ));

        $data = $this->request($url);
        if (isset($data['documents']) && isset($data['documents'][0]))
            $data = $data['documents'][0];
        else
            return false;

        return $data;
    }

    public function calc($from, $to, $cargoType, $weight, $urgency = false, $service = false, $numberOfSeats = 1)
    {
        $params = array(
            'from' => $from,
            'to' => $to,
            'typeOfCargo' => $cargoType,
            'weight' => $weight,
            'agent' => '',
            'urgency' => '',
            'service' => '',
            'qty' => '',
        );
        $params['login'] = 'СПОРТЦЕНТР';
        $params['password'] = 'hJSS4LEXDc9V';
        if ($urgency)
            $params['urgency'] = $urgency;
        if ($service)
            $params['service'] = $service;
        if ($numberOfSeats)
            $params['qty'] = $numberOfSeats;
        $url = $this->createUrl('Calc', $params);

        $data = $this->request($url);

        return $data;
    }

    public function printDocumentWeb($cseId)
    {
		$result = false;
        $webApi = $this->getWebApi();
        if ($webApi->isLogined()) {
            $result = $webApi->getWithdraw($cseId);
        }
		//Yii::app()->end();
        return $result;
    }

}