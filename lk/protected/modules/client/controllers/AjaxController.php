<?php

class AjaxController extends BaseClientController
{

    public $defaultAction = 'index';

    public function actions()
    {
        return array(
            'getUserBusinessCenters' => array(
                'class' => 'application.components.actions.AjaxGetUserBusinessCentersAction',
            ),
            'getCseCountyInfo' => array(
                'class' => 'application.components.actions.AjaxGetCseCountyInfoAction',
            ),
            'autocompleteCseRegion' => array(
                'class' => 'application.components.actions.AjaxAutocompleteCseRegionAction',
            ),
            'autocompleteCseArea' => array(
                'class' => 'application.components.actions.AjaxAutocompleteCseAreaAction',
            ),
            'autocompleteCseCity' => array(
                'class' => 'application.components.actions.AjaxAutocompleteCseCityAction',
            ),
            'autocompleteBusinessCenter' => array(
                'class' => 'application.components.actions.AjaxAutocompleteBusinessCenterAction',
            ),
        );
    }

    public function actionIndex()
    {
    }

}