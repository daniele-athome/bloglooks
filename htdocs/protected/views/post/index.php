<?php
$this->pageTitle = Yii::t('app', 'Recent posts');

if ($tag):
?>
<div class="nav text-muted">
<?php echo Yii::t('Post', 'Posts tagged with: <span class="label label-primary">{tag}</span>', array('{tag}' => $tag)); ?>
</div>
<?php
endif;

if ($author):
?>
<div class="nav text-muted">
<?php echo Yii::t('Post', 'Posts by: <span class="label label-success">{name}</span>', array('{name}' => $author->name)); ?>
</div>
<?php
endif;

echo $this->renderPartial('_list', array('dataProvider'=>$dataProvider, 'comments_disabled' => $comments_disabled));
