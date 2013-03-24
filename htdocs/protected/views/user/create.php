<?php
/* @var $this UserController */
/* @var $model User */

$this->pageTitle = Yii::t('User', 'Create user');

echo $this->renderPartial('_form', array('model'=>$model)); ?>