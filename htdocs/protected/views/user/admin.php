<?php
/* @var $this UserController */
/* @var $model User */
$this->pageTitle = Yii::t('app', 'Users');
?>

<legend><?php echo Yii::t('app', 'Users'); ?>&nbsp;<?php
 echo CHtml::link(Yii::t('User', 'Create user'), array('user/create'), array('class' => 'btn btn-default btn-sm pull-right')); ?>
</legend>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'user-grid',
    'summaryText' => false,
    'summaryCssClass' => 'hide',
	'dataProvider'=>$model->search(),
    'enableSorting' => false,
    'itemsCssClass' => 'table table-striped',
	'columns'=>array(
		'id',
		'login',
		'name',
		'role',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
