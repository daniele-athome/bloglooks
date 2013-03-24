<?php
$this->pageTitle = Yii::t('app', 'Posts');
?>
<legend><?php echo Yii::t('app', 'Posts'); ?>&nbsp;<?php
 echo CHtml::link(Yii::t('Post', 'New post'), array('post/new'), array('class' => 'btn btn-small pull-right btn-new')); ?>
</legend>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'post-grid',
    'summaryText' => false,
    'summaryCssClass' => 'hide',
	'dataProvider'=>$model->byLanguage(),
    'enableSorting' => false,
    'itemsCssClass' => 'table table-striped',
	'columns'=>array(
	    array(
	        'name' => 'id',
	        'htmlOptions'=>array('style'=>'width: 10%'),
	    ),
	    array(
	        'name' => 'title',
	        'htmlOptions'=>array('style'=>'width: 60%'),
	    ),
	    array(
	        'name' => 'published',
	        'htmlOptions'=>array('style'=>'width: 20%'),
	    ),
	    array(
	        'class' => 'CommaSeparatedLinksColumn',
	        'name' => 'language',
	        'type' => 'raw',
	        'value' => 'CHtml::link(CHtml2::flagImage($token), array("post/view", "id" => $data->id, "language" => $token), array("title" => $token))',
	        'header' => Yii::t('app', 'Languages'),
	        'separator' => '&nbsp;',
	        'htmlOptions'=>array('style'=>'width: 10%'),
	    ),
	),
));
