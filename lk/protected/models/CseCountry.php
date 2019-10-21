<?php

class CseCountry extends BaseCseCountry
{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array(
        );
    }

    public function relations()
    {
        return array(
            'cseRegions' => array(self::HAS_MANY, 'CseRegion', 'country_id'),
            'cseCities' => array(self::HAS_MANY, 'CseCity', 'country_id'),
        );
    }

    public function getStatusLabels()
    {
        return StatusHelper::getLabels();
    }

    public function getStatusLabel()
    {
        return StatusHelper::getLabel($this->status);
    }

    public function scopes()
    {
        $tableAlias = $this->getTableAlias();

        return array(
            'active' => array(
                'condition' => $tableAlias.'.status = :status_active',
                'params' => array(
                    ':status_active' => StatusHelper::STATUS_ENABLED,
                ),
            ),
        );
    }

    public function defaultScope()
    {
        $tableAlias = $this->getTableAlias(false, false);
        return array(
            'order' => $tableAlias.'.caption ASC',
        );
    }

    public function getRegionsCount()
    {
        return CseRegion::model()->active()->byCountryId($this->id)->count();
    }

    public function getAreasCount()
    {
        return CseCity::model()->active()->typeArea()->byCountryId($this->id)->count();
    }

    public function search()
    {
        $criteria=new CDbCriteria;

        $criteria->compare('caption', $this->caption, true);
        $criteria->compare('status', $this->status);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

}