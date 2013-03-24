<?php
$this->pageTitle=Yii::t('app', 'Login');

$form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>false,
    'htmlOptions' => array('class' => 'form-signin'),
)); ?>

    <h2 class="form-signin-heading"><?php echo Yii::t('app', 'Login'); ?></h2>

    <?php echo $form->textField($model,'username',array('placeholder' => Yii::t('app', 'Email'), 'class' => 'input-block-level')); ?>
    <?php echo $form->error($model,'username', array('class' => 'alert alert-error')); ?>

    <?php echo $form->passwordField($model,'password',array('placeholder' => Yii::t('app', 'Password'), 'class' => 'input-block-level')); ?>
    <?php echo $form->error($model,'password', array('class' => 'alert alert-error')); ?>

    <label class="checkbox" for="rememberMe">
      <?php echo $form->checkBox($model, 'rememberMe', array('id' => 'rememberMe')); ?> <?php echo Yii::t('app', 'Remember me'); ?>
    </label>
    <?php echo CHtml::submitButton(Yii::t('app', 'Login'), array('class' => 'btn btn-large btn-primary')); ?>
<?php $this->endWidget(); ?>
