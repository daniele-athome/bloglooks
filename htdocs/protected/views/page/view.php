<?php
$this->pageTitle = $model->title;

// publish status (pubblicato, bozza, ecc.)
if ($model->published) {
    $status = Yii::t('Page', 'Published:');
    $stamp = $model->published;
}
else {
    $status = Yii::t('Page', 'Draft:');
    $stamp = $model->modified;
}

?>

<div class="blog-post">

<h2 class="blog-post-title"><?php echo CHtml::encode($model->title); ?></h2>

<p class="blog-post-meta">
<?php echo $status; ?> <?php echo $stamp; ?> <?php echo Yii::t('app', 'by'); ?> <?php echo CHtml::link($model->author->name, array('post/index', 'author' => $model->author->id)); ?>
</p>

<div class="page-content">
<?php
$this->beginWidget('CMarkdown');
echo $model->content;
$this->endWidget();
?>
</div>

<?php if ($model->languages and count($model->languages) > 1): ?>
<small class="nav post-footer">
<b><?php echo Yii::t('app', 'Available in:'); ?></b>
<?php
    foreach ($model->languages as $lang):
        echo CHtml::link(CHtml2::flagImage($lang->language),
            array('page/view', 'name' => $model->name, "language" => $lang->language), array('title' => $lang->language));
        echo '&nbsp;';
    endforeach;
endif;
?>
</small>


<small class="nav muted post-footer blog-post-meta">
<?php if (Yii::app()->user->checkAccess('editor') || Yii::app()->user->checkAccess('admin')): ?>
<?php echo CHtml::link(Yii::t('app', 'Edit'), array('page/edit', 'name' => $model->name, 'language' => $model->language)); ?>&nbsp;|
<?php echo CHtml::link(Yii::t('app', 'Delete'), array('page/delete', 'name' => $model->name, 'language' => $model->language), array(
    'submit'=> array('page/delete', 'name' => $model->name, 'language' => $model->language),
    'confirm' => Yii::t('Page', 'Delete {lang} version of this page?', array('{lang}' => $model->language)))); ?>&nbsp;|
<?php echo Yii::t('app', 'Last modified:'); ?> <?php echo $model->modified; ?>
<?php endif; ?>

</small>

</div>
