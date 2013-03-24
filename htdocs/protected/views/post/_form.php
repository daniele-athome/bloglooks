<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'post-form',
	'enableAjaxValidation'=>false,
)); ?>

<fieldset>
    <legend>
    <?php if ($model->isNewRecord): ?>
    <?php echo Yii::t('Post', 'New post'); ?> <?php echo CHtml2::flagImage($model->language); ?>
    <?php else: ?>
    <?php echo Yii::t('Post', 'Post #{n}', array('{n}' => $model->id)); ?> <?php echo CHtml2::flagImage($model->language); ?>
    <?php endif; ?>
    </legend>

	<?php echo $form->textField($model,'title',array('class'=>'span12','maxlength'=>200, 'placeholder' => Yii::t('Post', 'Title'))); ?>
	<?php echo $form->error($model,'title',array('class' => 'alert alert-error')); ?>

	<label class="checkbox" for="checkPublished">
	<?php echo $form->checkBox($model,'published',array('id' => 'checkPublished', 'uncheckValue' => '', 'checked' => ($model->published !== null))); ?> <?php echo Yii::t('Post', 'Published'); ?>
	</label>

	<small class="muted">
	<?php echo Yii::t('app', 'You may use <a target="_blank" href="{url}">Markdown syntax</a>.',
	    array('{url}' => Yii::app()->params['markdownUrl'])); ?>
    </small>

	<div>
	<?php echo $form->textArea($model,'content',array('rows'=>10, 'cols'=>80, 'class' => 'post-content span12')); ?>
	</div>

	<?php echo $form->textField($model,'tags',array('class'=>'span12','maxlength'=>200, 'placeholder' => Yii::t('Post', 'Tags'))); ?>
	<?php echo $form->error($model,'tags',array('class' => 'alert alert-error')); ?>

	<?php echo CHtml::submitButton(Yii::t('app', $model->isNewRecord ? 'Create' : 'Save'), array('class' => 'btn btn-primary btn-large')); ?>
	<?php if (!$model->isNewRecord): ?>
    <?php echo CHtml::link(Yii::t('app', 'Delete'), array('post/delete', 'id' => $model->id, 'language' => $model->language), array('class' => 'btn btn-large btn-danger', 'confirm' => Yii::t('Post', 'Delete {lang} version of this post?', array('{lang}' => $model->language)))); ?>
	<?php endif; ?>

</fieldset>

<?php $this->endWidget(); ?>

</div><!-- form -->
