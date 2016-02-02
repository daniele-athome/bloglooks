<?php

/** CButtonColumn extension for Bootstrap 3 glyphicon buttons. */
class CBSButtonColumn extends CButtonColumn
{
    public $viewButtonLabel = '<span class="glyphicon glyphicon-search"></span>';
    public $viewButtonImageUrl = false;

    public $updateButtonLabel = '<span class="glyphicon glyphicon-pencil"></span>';
    public $updateButtonImageUrl = false;

    public $deleteButtonLabel = '<span class="glyphicon glyphicon-remove"></span>';
    public $deleteButtonImageUrl = false;

    protected function initDefaultButtons()
    {
        $this->viewButtonOptions['title'] = Yii::t('zii', 'View');
        $this->updateButtonOptions['title'] = Yii::t('zii', 'Update');
        $this->deleteButtonOptions['title'] = Yii::t('zii', 'Delete');
        parent::initDefaultButtons();
    }
}
