<?php
$this->pageTitle=Yii::t('app', 'Login');

$form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>false,
    'htmlOptions' => array('class' => 'form-signin'),
)); ?>

    <h2 class="form-signin-heading"><?php echo Yii::t('app', 'Login'); ?></h2>

    <div class="form-group">
    <?php echo $form->textField($model,'username',array('placeholder' => Yii::t('app', 'Email'), 'class' => 'form-control')); ?>
    <?php echo $form->error($model,'username', array('class' => 'alert alert-error')); ?>
    </div>

    <div class="form-group">
    <?php echo $form->passwordField($model,'password',array('placeholder' => Yii::t('app', 'Password'), 'class' => 'form-control')); ?>
    <?php echo $form->error($model,'password', array('class' => 'alert alert-error')); ?>
    </div>

    <div class="checkbox">
    <label class="checkbox" for="rememberMe">
      <?php echo $form->checkBox($model, 'rememberMe', array('id' => 'rememberMe')); ?> <?php echo Yii::t('app', 'Remember me'); ?>
    </label>
    </div>
    <?php echo CHtml::submitButton(Yii::t('app', 'Login'), array('class' => 'btn btn-lg btn-primary')); ?>
<?php $this->endWidget(); ?>
