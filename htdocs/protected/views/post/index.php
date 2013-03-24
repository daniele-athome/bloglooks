<?php
$this->pageTitle = Yii::t('app', 'Recent posts');

if ($tag):
?>
<div class="nav muted">
<?php echo Yii::t('Post', 'Posts tagged with: <span class="label label-info">{tag}</span>', array('{tag}' => $tag)); ?>
</div>
<?php
endif;

if ($author):
?>
<div class="nav muted">
<?php echo Yii::t('Post', 'Posts by: <span class="label label-success">{name}</span>', array('{name}' => $author->name)); ?>
</div>
<?php
endif;

echo $this->renderPartial('_list', array('dataProvider'=>$dataProvider));
