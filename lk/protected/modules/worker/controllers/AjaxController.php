<?php

class AjaxController extends BaseWorkerController
{

    public $defaultAction = 'index';

    public function actions()
    {
        return array(
            'getUserData' => array(
                'class' => 'application.components.actions.AjaxGetUserDataAction',
            ),
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
            'autocompleteUserClient' => array(
                'class' => 'application.components.actions.AjaxAutocompleteUserAction',
                'scopes' => array('active', 'client'),
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