<?php

class PresetAttributesActiveRecordBehavior extends CActiveRecordBehavior
{

    public $safePresetAttributes = array();

    public function presetAttributes($data = array())
    {
        $model = $this->getOwner();
        if (is_array($data)) {
            foreach ($data as $attribute => $value) {
                if (in_array($attribute, $this->safePresetAttributes))
                    $model->{$attribute} = $value;
            }
        }
    }

}