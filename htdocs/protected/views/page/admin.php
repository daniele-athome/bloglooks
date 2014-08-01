<?php
$this->pageTitle = Yii::t('app', 'Pages');
?>
<legend><?php echo Yii::t('app', 'Pages'); ?>&nbsp;<?php
 echo CHtml::link(Yii::t('Page', 'New page'), array('page/new'), array('class' => 'btn btn-default btn-sm pull-right')); ?>
</legend>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'page-grid',
    'summaryText' => false,
    'summaryCssClass' => 'hide',
	'dataProvider'=>$model->byLanguage(),
    'enableSorting' => false,
    'itemsCssClass' => 'table table-striped',
	'columns'=>array(
	    array(
	        'name' => 'name',
	        'htmlOptions'=>array('style'=>'width: 30%'),
	    ),
	    array(
	        'name' => 'title',
	        'htmlOptions'=>array('style'=>'width: 60%'),
	    ),
	    array(
	        'class' => 'CommaSeparatedLinksColumn',
	        'name' => 'language',
	        'type' => 'raw',
	        'value' => 'CHtml::link(CHtml2::flagImage($token), array("page/view", "name" => $data->name, "language" => $token), array("title" => $token))',
	        'header' => Yii::t('app', 'Languages'),
	        'separator' => '&nbsp;',
	        'htmlOptions'=>array('style'=>'width: 10%'),
	    ),
	),
)); ?>
