<?php

class Curl
{

    protected $defaultOptions = array(
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => false,
        CURLOPT_CONNECTTIMEOUT => 0,
        CURLOPT_TIMEOUT => 30,
    );

    public $curl;

    public $debugLog = null;

    public function __construct($url = '')
    {
        $this->curl = curl_init();

        if (is_array($this->defaultOptions)) {
            curl_setopt_array($this->curl, $this->defaultOptions);
        }
        $this->url($url);

        return $this;
    }


    public function __destruct()
    {
        curl_close($this->curl);
    }


    public function url($url = '')
    {
        curl_setopt($this->curl, CURLOPT_URL, $url);

        return $this->options(array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_VERBOSE => false,
        ))->post();
    }

    public function method($method = "get")
    {
        $method = strtoupper($method);
        if (!in_array($method, array('OPTIONS', 'GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE', 'TRACE', 'CONNECT')))
            $method = 'GET';

        $options = array();

        if ($method == 'GET')
            $options[CURLOPT_HTTPGET] = true;
        else
            $options[CURLOPT_HTTPGET] = false;

        if ($method == 'PUT')
            $options[CURLOPT_PUT] = true;
        else
            $options[CURLOPT_PUT] = false;

        $options[CURLOPT_CUSTOMREQUEST] = $method;

        return $this->options($options);
    }

    public function debug()
    {
        $this->debugLog = fopen('php://temp', 'rw+');
        return $this->options(array(
            CURLOPT_VERBOSE => true,
            CURLOPT_STDERR => $this->debugLog,
        ));
    }

    public function getDebugLog()
    {
        rewind($this->debugLog);
        $verboseLog = stream_get_contents($this->debugLog);
        return array(
            'verboseLog' => $verboseLog,
            'curlLog' => curl_getinfo($this->curl),
        );
    }

    public function post($data = null)
    {
        if (is_array($data) || $data) {
            curl_setopt($this->curl, CURLOPT_POST, true);
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
        } else {
            curl_setopt($this->curl, CURLOPT_POST, false);
        }
        return $this;
    }


    public function options($options)
    {
        if (is_array($options)) {
            curl_setopt_array($this->curl, $options);
        }
        return $this;
    }


    public function run()
    {
        return curl_exec($this->curl);
    }

}