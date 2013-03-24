<?php
/* @var $this UserController */
/* @var $model User */
?>

<div class="page-title">
<h2><?php echo Yii::t('User', 'User #{n}', array('{n}' => $model->id)); ?></h2>
</div>

<?php $this->widget('zii.widgets.CDetailView', array(
    'htmlOptions' => array('class' => 'table table-striped'),
	'data'=>$model,
	'attributes'=>array(
		'login',
	    /*
		array(
            'name' => 'password',
            'type' => 'raw',
            'value' => CHtml::link('Change password', array('user/changepasswd', 'id' => $model->id)),
        ),
        */
		'name',
		'role',
	),
)); ?>

<?php
if (Yii::app()->user->id == $model->id || Yii::app()->user->checkAccess('admin')) {
    echo CHtml::link(Yii::t('app', 'Edit'), array('user/update', 'id' => $model->id), array('class' => 'btn btn-large btn-primary'));
}
?>

<?php
if (Yii::app()->user->checkAccess('admin')) {
    echo CHtml::link(Yii::t('app', 'Delete'), array('user/delete', 'id' => $model->id), array('class' => 'btn btn-large btn-danger',
        'submit' => array('user/delete', 'id' => $model->id),
        'confirm' => Yii::t('User', 'Delete this user?')));
}
?>