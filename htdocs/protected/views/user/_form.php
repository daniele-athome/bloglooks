<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
    'htmlOptions' => array('role' => 'form'),
)); ?>

<fieldset>
    <legend>
    <?php if ($model->isNewRecord): ?>
    <?php echo Yii::t('User', 'Create user'); ?>
    <?php else: ?>
    <?php echo Yii::t('User', 'User #{n}', array('{n}' => $model->id)); ?>
    <?php endif; ?>
    </legend>

    <div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">@</span>
        <?php echo $form->textField($model,'login',array('maxlength'=>140,'class'=>'form-control','placeholder' => Yii::t('User', 'Login'))); ?>
    </div>
	<?php echo $form->error($model,'login', array('class' => 'alert alert-danger')); ?>
	</div>

    <div class="form-group">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
	    <?php echo $form->passwordField($model,'password',array('value'=>'','maxlength'=>40,'class'=>'form-control','placeholder' => Yii::t('User', 'Password'))); ?>
    </div>
	<?php echo $form->error($model,'password', array('class' => 'alert alert-danger')); ?>
	</div>

    <div class="form-group">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
	    <?php echo $form->textField($model,'name',array('maxlength'=>255,'class'=>'form-control','placeholder' => Yii::t('User', 'Name'))); ?>
	</div>
	<?php echo $form->error($model,'name', array('class' => 'alert alert-danger')); ?>
	</div>

    <div class="form-group">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-flag"></i></span>
	    <?php echo $form->textField($model,'role',array('maxlength'=>20,'class'=>'form-control','placeholder' => Yii::t('User', 'Role'))); ?>
	</div>
	<?php echo $form->error($model,'role', array('class' => 'alert alert-danger')); ?>
	</div>

	<div>
	<?php echo CHtml::submitButton(Yii::t('app', $model->isNewRecord ? 'Create' : 'Save'), array('class' => 'btn btn-primary btn-lg')); ?>
	<?php if (!$model->isNewRecord): ?>
    <?php echo CHtml::link(Yii::t('app', 'Delete'), array('user/delete', 'id' => $model->id), array('class' => 'btn btn-lg btn-danger',
        'submit' => array('user/delete', 'id' => $model->id),
        'confirm' => Yii::t('Page', 'Delete this user?'))); ?>
	<?php endif; ?>
	</div>

</fieldset>

<?php $this->endWidget(); ?>
