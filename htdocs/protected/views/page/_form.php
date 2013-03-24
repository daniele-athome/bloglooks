<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'page-form',
	'enableAjaxValidation'=>false,
)); ?>

<fieldset>
    <legend>
    <?php if ($model->isNewRecord): ?>
    <?php echo Yii::t('Page', 'New page'); ?> <?php echo CHtml2::flagImage($model->language); ?>
    <?php else: ?>
    <?php echo Yii::t('Page', 'Page: {title}', array('{title}' => $model->name)); ?> <?php echo CHtml2::flagImage($model->language); ?>
    <?php endif; ?>
    </legend>

    <?php if ($model->isNewRecord): ?>
	<?php echo $form->textField($model,'name',array('class'=>'span12','maxlength'=>100, 'placeholder' => Yii::t('Page', 'Name'))); ?>
	<?php echo $form->error($model,'name',array('class' => 'alert alert-error')); ?>
	<?php endif; ?>

	<?php echo $form->textField($model,'title',array('class'=>'span12','maxlength'=>200, 'placeholder' => Yii::t('Page', 'Title'))); ?>
	<?php echo $form->error($model,'title',array('class' => 'alert alert-error')); ?>

	<label class="checkbox" for="checkPublished">
	<?php echo $form->checkBox($model,'published',array('id' => 'checkPublished', 'uncheckValue' => '', 'checked' => ($model->published !== null))); ?> <?php echo Yii::t('Page', 'Published'); ?>
	</label>

	<!--
	TODO sto coso qua...
	<?php echo $form->labelEx($model,'order'); ?>
	<?php echo $form->textField($model,'order',array('class'=>'span2','maxlength'=>5)); ?>
	<?php echo $form->error($model,'order'); ?>
	 -->

	<small class="muted">
	<?php echo Yii::t('app', 'You may use <a target="_blank" href="{url}">Markdown syntax</a>.',
	    array('{url}' => Yii::app()->params['markdownUrl'])); ?>
    </small>

	<div>
	<?php echo $form->textArea($model,'content',array('rows'=>10, 'cols'=>80, 'class' => 'post-content span12')); ?>
	</div>

	<?php echo CHtml::submitButton(Yii::t('app', $model->isNewRecord ? 'Create' : 'Save'), array('class' => 'btn btn-primary btn-large')); ?>
	<?php if (!$model->isNewRecord): ?>
    <?php echo CHtml::link(Yii::t('app', 'Delete'), array('page/delete', 'name' => $model->name, 'language' => $model->language), array('class' => 'btn btn-large btn-danger', 'confirm' => Yii::t('Page', 'Delete {lang} version of this page?', array('{lang}' => $model->language)))); ?>
	<?php endif; ?>

</fieldset>

<?php $this->endWidget(); ?>

</div><!-- form -->
