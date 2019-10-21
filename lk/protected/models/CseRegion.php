<?php

class CseRegion extends BaseCseRegion
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
            'country' => array(self::BELONGS_TO, 'CseCountry', 'country_id'),
            'cseCities' => array(self::HAS_MANY, 'CseCity', 'area_id'),
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

    public function byCountryId($countryId)
    {
        $tableAlias = $this->getTableAlias();

        $this->getDbCriteria()->mergeWith(array(
            'condition' => $tableAlias.'.country_id = :selected_country_id',
            'params' => array(
                ':selected_country_id' => $countryId,
            )
        ));

        return $this;
    }

    public function defaultScope()
    {
        $tableAlias = $this->getTableAlias(false, false);
        return array(
            'order' => $tableAlias.'.caption ASC',
        );
    }

    public function search()
    {
        $criteria=new CDbCriteria;

        $criteria->compare('caption', $this->caption, true);
        $criteria->compare('country_id',$this->country_id);
        $criteria->compare('status', $this->status);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public static function getFindCriteria($countryId, $query = false)
    {
        $region = self::model();

        $criteria = new CDbCriteria();

        $criteria->scopes = array(
            'active',
            'byCountryId' => array($countryId),
        );

        if ($query !== false) {
            $criteria->addCondition($region->getTableAlias() . '.caption LIKE :like_caption');
            $criteria->params[':like_caption'] = $query . '%';
        }

        return $criteria;
    }

}