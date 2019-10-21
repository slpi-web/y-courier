<?php

class CseCity extends BaseCseCity
{

    const TYPE_AREA = 0;
    const TYPE_CITY = 1;
    const TYPE_VILLAGE = 2;

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
            'region' => array(self::BELONGS_TO, 'CseRegion', 'region_id'),
            'area' => array(self::BELONGS_TO, 'CseCity', 'parent_city_id', 'on' => 'area.type = '.self::TYPE_AREA),
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
            'typeArea' => array(
                'condition' => $tableAlias.'.type = :type_area',
                'params' => array(
                    ':type_area' => self::TYPE_AREA,
                ),
            ),
            'typeLocality' => array(
                'condition' => '('.$tableAlias.'.type = :type_city OR '.$tableAlias.'.type = :type_village)',
                'params' => array(
                    ':type_city' => self::TYPE_CITY,
                    'type_village' => self::TYPE_VILLAGE,
                ),
            ),
            'orderType' => array(
                'order' => $tableAlias.'.type ASC',
            )

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

    public function byRegionId($regionId)
    {
        $tableAlias = $this->getTableAlias();

        $this->getDbCriteria()->mergeWith(array(
            'condition' => $tableAlias.'.region_id = :selected_region_id',
            'params' => array(
                ':selected_region_id' => $regionId,
            )
        ));

        return $this;
    }

    public function byAreaId($areaId)
    {
        $tableAlias = $this->getTableAlias();

        $this->getDbCriteria()->mergeWith(array(
            'condition' => $tableAlias.'.parent_city_id = :selected_area_id',
            'params' => array(
                ':selected_area_id' => $areaId,
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
        $criteria->compare('country_id', $this->country_id);
        $criteria->compare('region_id', $this->region_id);
        $criteria->compare('parent_city_id',$this->parent_city_id);
        $criteria->compare('status', $this->status);
        $criteria->compare('type',$this->type);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public static function getFindAreaCriteria($countryId, $regionId = null, $query = false)
    {
        $city = self::model();

        $criteria = new CDbCriteria();

        $criteria->scopes = array(
            'active',
            'typeArea',
            'byCountryId' => array($countryId),
        );

        if ($regionId)
            $criteria->scopes['byRegionId'] = array($regionId);

        if ($query !== false) {
            $criteria->addCondition($city->getTableAlias() . '.caption LIKE :like_caption');
            $criteria->params[':like_caption'] = $query . '%';
        }

        return $criteria;
    }

    public static function getFindCityCriteria($countryId, $regionId = null, $areaId = null, $query = false, $withRelated = false)
    {
        $city = self::model();

        $criteria = new CDbCriteria();

        $criteria->scopes = array(
            'active',
            'typeLocality',
            'orderType',
            'byCountryId' => array($countryId),
        );

        if ($withRelated) {
            $criteria->with = array(
                'region' => array(
                    'select' => array('id', 'caption'),
                ),
                'area' => array(
                    'select' => array('id', 'caption'),
                )
            );
        }

        if ($regionId)
            $criteria->scopes['byRegionId'] = array($regionId);

        if ($areaId)
            $criteria->scopes['byAreaId'] = array($areaId);

        if ($query !== false) {
            $criteria->addCondition($city->getTableAlias() . '.caption LIKE :like_caption');
            $criteria->params[':like_caption'] = $query . '%';
        }

        return $criteria;
    }

}