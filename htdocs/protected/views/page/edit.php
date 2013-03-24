<?php
$this->pageTitle = 'Edit page - ' . $model->title;

$this->breadcrumbs=array(
	'Pages'=>array('index'),
	$model->name=>array('view','name'=>$model->name),
	'Update',
);

?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>