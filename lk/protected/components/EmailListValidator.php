<?php

class EmailListValidator extends CValidator
{

    public $pattern='/^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/';

    public $fullPattern='/^[^@]*<[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?>$/';

    public $trimEach = true;

    public $allowName=false;

    public $checkMX=false;

    public $checkPort=false;

    public $allowEmpty=true;

    public $validateIDN=false;

    public $stringDelimiter = "\n";

    protected $validator = null;

    protected function validateAttribute($object, $attribute)
    {
        $value=$object->$attribute;
        if($this->allowEmpty && $this->isEmpty($value))
            return;

        $values = explode($this->stringDelimiter, $value);
        foreach ($values as $val) {
            if ($this->trimEach)
                $val = trim($val);
            if (!$this->getValidator()->validateValue($val)) {
                $message = Yii::t('email-validator', 'Email {email} in {attribute} is not valid email address.');
                $this->addError($object, $attribute, $message, array('{email}' => $val));
            }
        }
    }

    protected function getValidator()
    {
        if (!$this->validator) {
            $this->validator = new CEmailValidator();
            foreach (array('pattern', 'fullPattern', 'allowName', 'checkMX', 'checkPort', 'validateIDN') as $attr) {
                $this->validator->{$attr} = $this->{$attr};
            }
        }
        return $this->validator;
    }

}