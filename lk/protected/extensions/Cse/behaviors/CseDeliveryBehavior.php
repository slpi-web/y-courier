<?php

class CseDeliveryBehavior extends CActiveRecordBehavior
{

    public $atributesMap = array(
        'cargo[description]' => 'cargo_description',
        'cargo[height][]' => 'cargo_height',
        'cargo[length][]' => 'cargo_length',
        'cargo[qty]' => 'cargo_seats_number',
        'cargo[type]' => 'cargo_type',
        'cargo[weight]' => 'cargo_weight',
        'cargo[width][] ' => 'cargo_width',
        'customer[department]' => '',
        'customer[official]' => array(
            'value' => '$this->getCseContactPerson()'
        ),
        'customer[project]' => '',
        'general[client_number]' => '',
        'general[company]' => array(
            'value' => '$this->getCseCompany()'
        ),
        'recipients[addrcl_area_guid][]' => '',
        'recipients[addrcl_region_guid][]' => '',
        'recipients[addrcl_settlement_guid][]' => '',
        'recipients[address][]' => 'recipient_address',
        'recipients[address_book_guid][]' => '',
        'recipients[company_name][]' => 'recipient',
        'recipients[contact_person][]' => 'recipient_contact',
        'recipients[country][]' => array(
            'value' => '$this->getCseRecipientCity("country_id")',
            'attribute' => 'recipient_city',
        ),
        'recipients[email][]' => 'recipient_email',
        'recipients[index][]' => 'recipient_post_index',
        'recipients[information][]' => 'recipient_info',
        'recipients[phone][]' => 'recipient_phone',
        'recipients[settlement_guid][]' => array(
            'value' => '$this->getCseRecipientCity("id")',
            'attribute' => 'recipient_city',
        ),
        'recipients[settlement_name][]' => array(
            'value' => '$this->getCseRecipientCity("caption")',
            'attribute' => 'recipient_city',
        ),
        'sender[addrcl_area_guid]' => '',
        'sender[addrcl_region_guid]' => '',
        'sender[addrcl_settlement_guid]' => '',
        'sender[address]' => 'sender_address',
        'sender[address_book_guid]' => '',
        'sender[company_name]' => 'sender',
        'sender[contact_person]' => 'sender_contact',
        'sender[country]' => array(
            'value' => '$this->getCseSenderCity("country_id")',
            'attribute' => 'sender_city',
        ),
        'sender[email]' => 'sender_email',
        'sender[index]' => 'sender_post_index',
        'sender[information]' => 'sender_info',
        'sender[phone]' => 'sender_phone',
        'sender[settlement_guid]' => array(
            'value' => '$this->getCseSenderCity("id")',
            'attribute' => 'sender_city',
        ),
        'sender[settlement_name]' => array(
            'value' => '$this->getCseSenderCity("caption")',
            'attribute' => 'sender_city',
        ),
        'service[email]' => '',
        'service[sms_notification][area_code]' => array(
            'value' => '$this->getCseNotifyPhone("code")',
            'attribute' => 'notify_phone',
        ),
        'service[sms_notification][subscriber_number]' => array(
            'value' => '$this->getCseNotifyPhone("phone")',
            'attribute' => 'notify_phone',
        ),
        'template' => array(
            'value' => 0
        ),
        'terms[comment]' => 'comment',
        'terms[delivery_methods]' => 'delivery_method',
        'terms[payer]' => 'payer',
        'terms[take_date]' => array(
            'value' => '$this->formatDateToCse($this->owner->take_date)',
            'attribute' => 'take_date',
        ),
        'terms[take_time_from]' => 'take_time_from',
        'terms[take_time_to]' => 'take_time_to',
        'terms[urgency]' => 'urgency_id',
        'terms[way_of_payment]' => 'payment_method',
    );

    protected $cseSenderCity = null;
    protected $cseRecipientCity = null;
    protected $parsedCsePhone = null;

    public function getCseSenderCity($attribute = null)
    {
        $result = null;
        if ($this->owner->sender_city) {
            if (!$this->cseSenderCity || $this->cseSenderCity->id != $this->owner->sender_city) {
                $this->cseSenderCity = CseCity::model()->typeLocality()->findByPk($this->owner->sender_city);
            }
            if ($this->cseSenderCity && $this->cseSenderCity->id == $this->owner->sender_city) {
                $result = $this->cseSenderCity;

                if ($attribute)
                    $result = $this->cseSenderCity->{$attribute};
            }
        }
        return $result;
    }

    public function getCseRecipientCity($attribute = null)
    {
        $result = null;
        if ($this->owner->recipient_city) {
            if (!$this->cseRecipientCity || $this->cseRecipientCity->id != $this->owner->recipient_city) {
                $this->cseRecipientCity = CseCity::model()->typeLocality()->findByPk($this->owner->recipient_city);
            }
            if ($this->cseRecipientCity && $this->cseRecipientCity->id == $this->owner->recipient_city) {
                $result = $this->cseRecipientCity;

                if ($attribute)
                    $result = $this->cseRecipientCity->{$attribute};
            }
        }
        return $result;
    }

    public function getCseCompany()
    {
        $company = '';
        if (isset(Yii::app()->params['cseCompany']))
            $company = Yii::app()->params['cseCompany'];

        return $company;
    }

    public function getCseContactPerson()
    {
        $contactPerson = '';
        if (isset(Yii::app()->params['cseContactPerson']))
            $contactPerson = Yii::app()->params['cseContactPerson'];

        return $contactPerson;
    }

    public function getCseNotifyPhone($part = 'full')
    {
        $result = '';
        if ($this->owner->notify_phone) {
            if (!is_array($this->parsedCsePhone)) {
                $matches = null;
                $this->parsedCsePhone = array();
                if (preg_match('/^\+7[ ]\((\d{3})\)[ ][\d]{3}[\-][\d]{2}[\-][\d]{2}$/', $this->owner->notify_phone, $matches) == 1) {
                    if (is_array($matches) && isset($matches[0]) && $matches[1]) {
                        $this->parsedCsePhone = array(
                            'full' => '+7 ('.$matches[0].') '.$matches[1],
                            'code' => $matches[0],
                            'phone' => $matches[1],
                        );
                    }
                }
            }

            if (is_array($this->parsedCsePhone) && isset($this->parsedCsePhone[$part]))
                $result = $this->parsedCsePhone[$part];
        }
        return $result;
    }

    public function formatDateToCse($date)
    {
        if (strpos('.' , $date) !== false)
            return $date;
        else
            return date('d.m.Y', strtotime($date));
    }

    public function getCseParams()
    {
        $data = $this->atributesMap;
        foreach ($data as $key => &$value) {
            if (is_string($value) || is_numeric($value)) {
                if (isset($this->owner->{$value}))
                    $value = $this->owner->{$value};
                else
                    $value = $value;
            } elseif (is_array($value)) {
                if (isset($value['value'])) {
                    if (is_string($value['value']))
                        $value = $this->evaluateExpression($value['value']);
                    else
                        $value = $value['value'];
                } else
                    $value = '';
            }
        }
        return $data;
    }

    public function getCseIdFromResponse($response)
    {
        if (is_array($response)) {
            if (isset($response[0]) && isset($response[0]['documentsNumbers']) && isset($response[0]['documentsNumbers'][0])) {
                return $response[0]['documentsNumbers'][0];
            }
        }

        return false;
    }

    public function addCseErrors($response)
    {
        if (is_array($response)) {
            foreach ($response as $responseRow) {
                if (isset($responseRow['message']) && isset($responseRow['selector'])) {
                    foreach ($this->atributesMap as $key => $value) {
                        if (mb_strpos($responseRow['selector'], $key) !== false) {
                            if (is_string($value) || is_numeric($value)) {
                                if (isset($this->owner->{$value}))
                                    $this->owner->addError($value, $responseRow['message']);
                            } elseif (is_array($value)) {
                                if (isset($value['attribute'])) {
                                    $this->owner->addError($value['attribute'], $responseRow['message']);
                                }
                            }
                        }
                    }
                }
            }
        }
    }


}