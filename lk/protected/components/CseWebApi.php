<?php
Yii::import('application.vendor.autoload', true);

use SleepingOwl\Apist\Apist;

class CseWebApi extends Apist
{
    const STATUS_SIGNED = 1;
    const STATUS_NOT_SIGNED = 0;

    protected $status = self::STATUS_NOT_SIGNED;

    protected $auth;

    protected $baseUrl;

    protected $webPaths = array();

    protected $cookie;

    public function __construct($url, $login, $password, $webPaths)
    {
        $this->status = self::STATUS_NOT_SIGNED;
        $this->baseUrl = $url;
        $this->webPaths = $webPaths;
        $this->setBaseUrl($url);
        $this->cookie = new \GuzzleHttp\Cookie\CookieJar();
        $options['cookies'] = $this->cookie;
        $options['timeout'] = 10;
        $options['connect_timeout'] = 10;
        $options['allow_redirects'] = true;
        parent::__construct($options);
        $this->login($login, $password);
    }

    protected function login($login, $password)
    {
        $this->auth = array($login, $password);

        $data = $this->get('/', array(
            'loginUrl' => Apist::filter('#login-form')->attr('action'),
            'token' => Apist::filter('#login-form input[name="_token"]')->attr('value'),
        ), array('cookies' => $this->cookie));

        if (is_array($data) && isset($data['loginUrl']) && isset($data['token']) && $data['loginUrl'] && $data['token']) {
            $guzzle = $this->getGuzzle();
            $guzzle->post($data['loginUrl'], array(
                'cookies' => $this->cookie,
                'body' => array(
                    '_token' => $data['token'],
                    'username' => $login,
                    'password' => $password
                ),
            ));

            $data = $this->get('/', array(
                'user' => Apist::filter('#header .user-description')->text(),
            ), array('cookies' => $this->cookie));

            if (isset($data['user']) && mb_strtolower($data['user']) == mb_strtolower($login))
                $this->status = self::STATUS_SIGNED;
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function isLogined()
    {
        if ($this->status == self::STATUS_SIGNED)
            return true;

        return false;
    }

    public function saveOrder(array $params)
    {
        $url = isset($this->webPaths['createOrder']) ? $this->webPaths['createOrder'] : '/';
        $data = $this->get($url, array(
            'submitUrl' => Apist::filter('form#order')->attr('action'),
            'token' => Apist::filter('form#order input[name="_token"]')->attr('value'),
        ), array('cookies' => $this->cookie));

        if (is_array($data) && isset($data['submitUrl']) && isset($data['token']) && $data['submitUrl'] && $data['token']) {
            $params = array_merge(array(
                '_token' => $data['token'],
            ), $params);
            Yii::log('request '.print_r($params, true), 'info', 'components.cseApi');
            $guzzle = $this->getGuzzle();
            $response = $guzzle->post($data['submitUrl'], array(
                'cookies' => $this->cookie,
                'body' => $params,
            ));

            return $response->getBody();
        }

        return false;
    }

    public function getCompanies()
    {
        $url = isset($this->webPaths['createOrder']) ? $this->webPaths['createOrder'] : '/';
        $data = $this->get($url, array(
            'companies' => Apist::filter('form#order select[id="general[company]"] > option')->each(array(
                'id'        => Apist::current()->attr('value'),
                'name'      => Apist::current()->text(),
            )),
        ), array('cookies' => $this->cookie));

        $result = array();
        if (is_array($data) && isset($data['companies']) && is_array($data['companies'])) {
            foreach ($data['companies'] as $company) {
                if (isset($company['id']) && isset($company['name']) && $company['id'] && $company['name'])
                    $result[$company['id']] = $company['name'];
            }
        }

        return $result;
    }

    public function getContactPersons()
    {
        $url = isset($this->webPaths['createOrder']) ? $this->webPaths['createOrder'] : '/';
        $data = $this->get($url, array(
            'persons' => Apist::filter('form#order select[id="customer[official]"] > option')->each(array(
                'id'        => Apist::current()->attr('value'),
                'name'      => Apist::current()->text(),
            )),
        ), array('cookies' => $this->cookie));

        $result = array();
        if (is_array($data) && isset($data['persons']) && is_array($data['persons'])) {
            foreach ($data['persons'] as $person) {
                if (isset($person['id']) && isset($person['name']) && $person['id'] && $person['name'])
                    $result[$person['id']] = $person['name'];
            }
        }

        return $result;
    }

    public function getWithdraw($cseId)
    {
        $url = isset($this->webPaths['viewOrder']) ? $this->webPaths['viewOrder'].$cseId : '/';
        $data = $this->get($url, array(
            'header' => Apist::filter('#content h1')->text(),
            'printBtnDataPrintSettings' => Apist::filter('#content .order-print.print-waybills-from-order')->attr('data-print-settings'),
            'printBtnDataRoutePrint' => Apist::filter('#content .order-print.print-waybills-from-order')->attr('data-route-print'),
        ), array('cookies' => $this->cookie));

        if (isset($data['header']) && mb_strpos($data['header'], $cseId)!==false && isset($data['printBtnDataPrintSettings']) && isset($data['printBtnDataRoutePrint'])) {
            if ($data['printBtnDataRoutePrint'] && $data['printBtnDataPrintSettings']) {
                $guzzle = $this->getGuzzle();
                $response = $guzzle->post($data['printBtnDataRoutePrint'], array(
                    'cookies' => $this->cookie,
                    'body' => array(
                        'order_numbers' => $cseId,
                        'settings[documentType]' => 'order',
                        'settings[htmlTemplate]' => 'waybill',
                    ),
                ));
                $response = $response->getBody();
                $data = function_exists('json_decode') ? json_decode($response,true) : CJSON::decode($response,true);
                if (isset($data[0]['url'])) {
                    $data = $guzzle->get($data[0]['url'], array(
                        'cookies' => $this->cookie,
                    ));
                    return $data->getBody();
                }
            }
        }
        return false;
    }


} 