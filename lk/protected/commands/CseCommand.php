<?php


class CseCommand extends CConsoleCommand
{

    public function actionUpdateGeography()
    {
        $countries = Yii::app()->cseApi->getCountries();
        if (is_array($countries)) {
            $countriesIds = array();
            $existingCounriesIds = Yii::app()->db->createCommand()->select('id')
                ->from('cse_country')
                ->queryColumn();
            foreach ($countries as $countryId => $countryCaption) {
                echo "\nworking with ".iconv('UTF-8',855,$countryCaption).' country';

                $countriesIds[] = $countryId;
                if (in_array($countryId, $existingCounriesIds)) {
                    //update
                    Yii::app()->db->createCommand()->update('cse_country', array(
                        'status' => StatusHelper::STATUS_ENABLED,
                        'caption' => $countryCaption,
                    ), 'id = :id', array(':id' => $countryId));
                } else {
                    //add
                    Yii::app()->db->createCommand()->insert('cse_country', array(
                        'id' => $countryId,
                        'status' => StatusHelper::STATUS_ENABLED,
                        'caption' => $countryCaption,
                    ));
                }

                $this->updateCountryEmbeddedData($countryId);

            }
            Yii::app()->db->createCommand()->update('cse_country', array(
                'status' => StatusHelper::STATUS_DISABLED,
            ), array('not in', 'id', $countriesIds));
        }
        echo "\n";
    }

    public function actionUpdateCountry($countryId)
    {
        $country = CseCountry::model()->findByPk($countryId);
        if ($country) {
            $this->updateCountryEmbeddedData($countryId);
        } else {
            echo "\nCountry with id ".$countryId.' not found.';
        }
        echo "\n";
    }

    protected function updateCountryEmbeddedData($countryId)
    {
        $regions = $countries = Yii::app()->cseApi->getRegions($countryId);
        if (is_array($regions)) {
            echo "\n regions count: ".count($regions);
            if (count($regions)) {
                $regionsIds = array();
                $existingRegions = Yii::app()->db->createCommand()->select('id')
                    ->from('cse_region')
                    ->where('country_id = :country_id', array(':country_id' => $countryId))
                    ->queryColumn();
                foreach ($regions as $regionId => $regionCaption) {
                    echo "\nworking with ".iconv('UTF-8',855,$regionCaption).' region';
                    $regionsIds[] = $regionId;
                    if (in_array($regionId, $existingRegions)) {
                        //update
                        Yii::app()->db->createCommand()->update('cse_region', array(
                            'country_id' => $countryId,
                            'status' => StatusHelper::STATUS_ENABLED,
                            'caption' => $regionCaption,
                        ), 'id = :id', array(':id' => $regionId));
                    } else {
                        //insert
                        Yii::app()->db->createCommand()->insert('cse_region', array(
                            'id' => $regionId,
                            'country_id' => $countryId,
                            'status' => StatusHelper::STATUS_ENABLED,
                            'caption' => $regionCaption,
                        ));
                    }

                    $this->updateCities($countryId, $regionId);
                }

                Yii::app()->db->createCommand()->update('cse_region', array(
                    'status' => StatusHelper::STATUS_DISABLED,
                ), array('and', 'country_id = :country_id', array('not in', 'id', $regionsIds)), array(':country_id' => $countryId));
            }
        }
        $this->updateCities($countryId);
    }

    protected function convertCityTypeToModel($cseApiType)
    {
        switch ($cseApiType) {
            case CseApi::TYPE_CITY:
                return CseCity::TYPE_CITY;
                break;
            case CseApi::TYPE_VILLAGE:
                return CseCity::TYPE_VILLAGE;
                break;
            case CseApi::TYPE_AREA:
                return CseCity::TYPE_AREA;
                break;
        }
        return null;
    }

    public function actionUpdateRegion($regionId)
    {
        $region = CseRegion::model()->findByPk($regionId);
        if ($region) {
            $this->updateCities($region->country_id, $region->id);
        } else {
            echo "\nRegion with id ".$regionId.' not found.';
        }
        echo "\n";
    }

    protected function updateCities($countryId, $regionId = null, $parentCityId = null)
    {
        $queryId = $countryId;
        if ($regionId)
            $queryId = $regionId;
        if ($parentCityId)
            $queryId = $parentCityId;

        $cities = Yii::app()->cseApi->getCitiesEx($queryId, '', array(
            CseApi::TYPE_AREA,
            CseApi::TYPE_CITY,
            CseApi::TYPE_VILLAGE,
        ));
        if (is_array($cities)) {
            echo "\n cities count: ".count($cities);
            $whereCondition = 'country_id = :country_id';
            $whereParams = array(
                ':country_id' => $countryId,
            );

            if ($regionId) {
                $whereCondition .= ' AND region_id = :region_id';
                $whereParams[':region_id'] = $regionId;
            } else
                $whereCondition .= ' AND region_id IS NULL';

            if ($parentCityId) {
                $whereCondition .= ' AND parent_city_id = :parent_city_id';
                $whereParams[':parent_city_id'] = $parentCityId;
            } else
                $whereCondition .= ' AND parent_city_id IS NULL';

            $existingCities = Yii::app()->db->createCommand()->select('id')
                ->from('cse_city')
                ->where($whereCondition, $whereParams)
                ->queryColumn();

            $citiesIds = array();
            foreach ($cities as $cityId => $city) {
                echo "\nworking with ".iconv('UTF-8',855,$city['name']).' city';
                $modelType = $this->convertCityTypeToModel($city['type']);
                if ($modelType !== null) {
                    $citiesIds[] = $cityId;
                    if (in_array($cityId, $existingCities)) {
                        //update
                        Yii::app()->db->createCommand()->update('cse_city', array(
                            'country_id' => $countryId,
                            'region_id' => ($regionId) ? $regionId : new CDbExpression('NULL'),
                            'parent_city_id' => ($parentCityId) ? $parentCityId : new CDbExpression('NULL'),
                            'status' => StatusHelper::STATUS_ENABLED,
                            'type' => $modelType,
                            'caption' => $city['name'],
                        ), 'id = :id', array(':id' => $cityId));
                    } else {
                        //insert
                        Yii::app()->db->createCommand()->insert('cse_city', array(
                            'id' => $cityId,
                            'country_id' => $countryId,
                            'region_id' => ($regionId) ? $regionId : new CDbExpression('NULL'),
                            'parent_city_id' => ($parentCityId) ? $parentCityId : new CDbExpression('NULL'),
                            'status' => StatusHelper::STATUS_ENABLED,
                            'type' => $modelType,
                            'caption' => $city['name'],
                        ));
                    }

                    $this->updateCities($countryId, $regionId, $cityId);
                }
            }

            Yii::app()->db->createCommand()->update('cse_city', array(
                'status' => StatusHelper::STATUS_DISABLED,
            ), array('and', $whereCondition, array('not in', 'id', $citiesIds)), $whereParams);
        }
    }

    public function actionUpdateUrgency()
    {
        $urgencies = Yii::app()->cseApi->getUrgencies();
        if (is_array($urgencies)) {
            $urgenciesIds = array();
            $existingUrgenciesIds = Yii::app()->db->createCommand()->select('id')
                ->from('cse_urgency')
                ->queryColumn();
            foreach ($urgencies as $urgencyId => $urgencyCaption) {
                $urgenciesIds[] = $urgencyId;
                if (in_array($urgencyId, $existingUrgenciesIds)) {
                    //update
                    Yii::app()->db->createCommand()->update('cse_urgency', array(
                        'status' => StatusHelper::STATUS_ENABLED,
                        'caption' => $urgencyCaption,
                    ), 'id = :id', array(':id' => $urgencyId));
                } else {
                    //add
                    Yii::app()->db->createCommand()->insert('cse_urgency', array(
                        'id' => $urgencyId,
                        'status' => StatusHelper::STATUS_ENABLED,
                        'caption' => $urgencyCaption,
                    ));
                }
            }
            Yii::app()->db->createCommand()->update('cse_urgency', array(
                'status' => StatusHelper::STATUS_DISABLED,
            ), array('not in', 'id', $urgenciesIds));
        }
    }

    public function actionCheckDeliveryStatus($count = 0)
    {
        $criteria = new CDbCriteria();
        $criteria->condition = 'status = :status_synced AND client_status != :client_status_delivered AND cse_id != :empty_cse_id';
        $criteria->params = array(
            ':status_synced' => CseDelivery::STATUS_SYNCED,
            ':client_status_delivered' => CseDelivery::CLIENT_STATUS_DELIVERED,
            ':empty_cse_id' => '',
        );
        if ($count)
            $criteria->limit = $count;
        $cseDelivery = CseDelivery::model()->findAll($criteria);
        foreach ($cseDelivery as $model) {
            echo "\nchecking model ".$model->id.' with cseId '.$model->cse_id.' ';
            $track = Yii::app()->cseApi->tracking($model->cse_id);
            if (is_array($track) && isset($track)) {
                if (isset($track['deliveryDateTimeISO']) && $track['deliveryDateTimeISO']) {
                    $model->saveAttributes(array(
                        'client_status' => CseDelivery::CLIENT_STATUS_DELIVERED,
                    ));
                    echo 'delivered';
                } else
                    echo 'not delivered';
            }
        }
        echo "\n";
    }

}