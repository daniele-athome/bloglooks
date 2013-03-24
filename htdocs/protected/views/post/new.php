<?php
$this->pageTitle = Yii::t('Post', 'New post');

echo $this->renderPartial('_form', array('model'=>$model)); ?>