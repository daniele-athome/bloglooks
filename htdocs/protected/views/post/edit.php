<?php
$this->pageTitle = Yii::t('Post', 'Edit post - {title}', array('{title}' => $model->title));

echo $this->renderPartial('_form', array('model'=>$model)); ?>