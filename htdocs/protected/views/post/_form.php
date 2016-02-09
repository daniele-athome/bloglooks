<?php
/** @var Post $model */
/** @var PostController $this */

if (!$model->isNewRecord) {
    $saving_text = Yii::t('Post', 'Saving...');
    $saved_text = Yii::t('Post', 'Draft saved at %s');
    $error_text = Yii::t('Post', 'Error saving draft.');
    $delay = Yii::app()->params['autosaveDelay'];
    $url = Yii::app()->createUrl('post/draft', array('id' => $model->id, 'language' => $model->language));
    Yii::app()->clientScript->registerCoreScript('typewatch');
    Yii::app()->clientScript->registerCoreScript('moment');
    Yii::app()->clientScript->registerScript('autosave', <<<EOF
function autosave(value) {
    post_status('{$saving_text}');
    var form = this.form;
    var data = $(form).serialize();

    $.ajax({
        type: 'POST',
        url: '{$url}',
        data: data,
        success: function(data) {
            $('#btn-discard-draft').removeClass('hide');
            if (data.length == 0)
                post_status('{$saved_text}'.replace('%s', moment().format('YYYY-MM-DD HH:mm:ss')));
            else
                post_status('{$error_text}');
        },
        error: function(data) {
            post_status('{$error_text}');
        },
        dataType:'json'
    });
}

function post_status(text) {
    $('#post-status').text(text);
}

$('#Post_content').typeWatch({
    callback: autosave,
    wait: {$delay},
    highlight: false,
    captureLength: 4
});
EOF
        , CClientScript::POS_READY);
}

/** @var CActiveForm $form */
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'post-form',
	'enableAjaxValidation'=>false,
    'htmlOptions' => array('role' => 'form'),
));
?>

<fieldset>
    <legend>
    <?php if ($model->isNewRecord): ?>
    <?php echo Yii::t('Post', 'New post'); ?> <?php echo CHtml2::flagImage($model->language); ?>
    <?php else: ?>
    <?php echo Yii::t('Post', 'Post #{n}', array('{n}' => $model->id)); ?> <?php echo CHtml2::flagImage($model->language); ?>
        <small class="text-muted header-small" id="post-status"><?php echo Yii::t('app', 'Last modified:'); ?> <?php echo $model->modified; ?></small>
    <?php endif; ?>
    </legend>

    <div class="form-group">
	<?php echo $form->textField($model,'title',array('class'=>'form-control post-title','maxlength'=>200, 'placeholder' => Yii::t('Post', 'Title'))); ?>
	<?php echo $form->error($model,'title',array('class' => 'alert alert-danger')); ?>
	</div>

	<div class="form-group">
        <small class="text-muted">
            <?php echo Yii::t('app', 'You may use <a target="_blank" href="{url}">Markdown syntax</a>.',
                array('{url}' => Yii::app()->params['markdownUrl'])); ?>
        </small>
	<?php echo $form->textArea($model,'content',array('rows'=>10, 'cols'=>80, 'class' => 'post-content form-control')); ?>
	</div>

	<div class="form-group">
	<?php echo $form->textField($model,'tags',array('class'=>'form-control post-tags','maxlength'=>200, 'placeholder' => Yii::t('Post', 'Tags'))); ?>
	<?php echo $form->error($model,'tags',array('class' => 'alert alert-danger')); ?>
	</div>

    <div class="checkbox">
        <label class="checkbox" for="checkPublished">
            <?php echo $form->checkBox($model,'published',array('id' => 'checkPublished', 'uncheckValue' => '', 'checked' => ($model->published !== null))); ?> <?php echo Yii::t('Post', 'Published'); ?>
        </label>
    </div>

    <?php echo CHtml::submitButton(Yii::t('app', $model->isNewRecord ? 'Create' : 'Save'), array('class' => 'btn btn-primary btn-lg')); ?>
	<?php if (!$model->isNewRecord): ?>
    <?php echo CHtml::link(Yii::t('app', 'Delete'), array('post/delete', 'id' => $model->id, 'language' => $model->language), array(
            'class' => 'btn btn-lg btn-danger',
            'submit'=> array('post/delete', 'id' => $model->id, 'language' => $model->language),
            'confirm' => Yii::t('Post', 'Delete {lang} version of this post?', array('{lang}' => $model->language)))); ?>
        <?php echo CHtml::link(Yii::t('Post', 'Discard draft'), array('post/draft', 'id' => $model->id, 'language' => $model->language), array(
            'class' => 'btn btn-lg btn-default' . (!$model->draft ? ' hide' : ''), 'id' => 'btn-discard-draft', 'confirm' => Yii::t('Post', 'Discard draft?'))); ?>
	<?php endif; ?>

</fieldset>

<?php $this->endWidget(); ?>
