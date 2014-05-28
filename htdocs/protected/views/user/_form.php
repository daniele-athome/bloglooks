<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
)); ?>

<fieldset>
    <legend>
    <?php if ($model->isNewRecord): ?>
    <?php echo Yii::t('User', 'Create user'); ?>
    <?php else: ?>
    <?php echo Yii::t('User', 'User #{n}', array('{n}' => $model->id)); ?>
    <?php endif; ?>
    </legend>

    <div class="span10">

    <div class="input-prepend">
        <span class="add-on">@</span>
        <?php echo $form->textField($model,'login',array('maxlength'=>140,'class'=>'span12','placeholder' => Yii::t('User', 'Login'))); ?>
    </div>
	<?php echo $form->error($model,'login', array('class' => 'alert alert-error')); ?>

    <div class="input-prepend">
        <span class="add-on"><i class="icon-lock"></i></span>
	    <?php echo $form->passwordField($model,'password',array('value'=>'','maxlength'=>40,'class'=>'span12','placeholder' => Yii::t('User', 'Password'))); ?>
    </div>
	<?php echo $form->error($model,'password', array('class' => 'alert alert-error')); ?>

    <div class="input-prepend">
        <span class="add-on"><i class="icon-user"></i></span>
	    <?php echo $form->textField($model,'name',array('maxlength'=>255,'class'=>'span12','placeholder' => Yii::t('User', 'Name'))); ?>
	</div>
	<?php echo $form->error($model,'name', array('class' => 'alert alert-error')); ?>

    <div class="input-prepend">
        <span class="add-on"><i class="icon-flag"></i></span>
	    <?php echo $form->textField($model,'role',array('maxlength'=>20,'class'=>'span12','placeholder' => Yii::t('User', 'Role'))); ?>
	</div>
	<?php echo $form->error($model,'role', array('class' => 'alert alert-error')); ?>

	<div>
	<?php echo CHtml::submitButton(Yii::t('app', $model->isNewRecord ? 'Create' : 'Save'), array('class' => 'btn btn-primary btn-large')); ?>
	<?php if (!$model->isNewRecord): ?>
    <?php echo CHtml::link(Yii::t('app', 'Delete'), array('user/delete', 'id' => $model->id), array('class' => 'btn btn-large btn-danger',
        'submit' => array('user/delete', 'id' => $model->id),
        'confirm' => Yii::t('Page', 'Delete this user?'))); ?>
	<?php endif; ?>
	</div>

	</div>
</fieldset>

<?php $this->endWidget(); ?>

</div><!-- form -->