<?php

class WDateRangeFilter extends CInputWidget
{

    public $view = 'default';

    public $options = array();

    public function run()
    {
        $this->options = CMap::mergeArray(array(
            'locale' => array(
                'format' => 'DD.MM.YYYY'
            ),
            'opens' => 'left',
            'autoApply' => true,
        ), $this->options);

        $this->render($this->view);
    }

}