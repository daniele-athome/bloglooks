<?php
$form=$this->beginWidget('CActiveForm', array(
    'id'=>'comment-form',
    'enableClientValidation'=>false,
    'htmlOptions' => array('class' => 'form-comment'),
));

if (isset($legend)): ?>
<legend><?php echo $legend ?></legend>
<?php endif; ?>

<?php if (Yii::app()->user->isGuest): ?>

<?php echo $form->textField($model,'anon_name',array('class'=>'span12','maxlength'=>50, 'placeholder' => Yii::t('User', 'Name'))); ?>
<?php echo $form->error($model,'anon_name',array('class' => 'alert alert-error')); ?>
<?php echo $form->textField($model,'anon_email',array('class'=>'span12','maxlength'=>100, 'placeholder' => Yii::t('User', 'Email'))); ?>
<?php echo $form->error($model,'anon_email',array('class' => 'alert alert-error')); ?>

<?php endif; ?>

<?php echo $form->textArea($model,'content',array('rows'=>10, 'cols'=>80, 'class' => 'span12')); ?>

<?php $this->widget('application.extensions.recaptcha.EReCaptcha',
   array('model'=>$model, 'attribute'=>'captcha',
         'theme'=>'clean', 'language'=>$this->language,
         'publicKey'=>Yii::app()->params['recaptcha']['publicKey'])) ?>
<?php echo CHtml::error($model, 'captcha', array('class' => 'alert alert-error')); ?>

<?php echo CHtml::submitButton(Yii::t('app', 'Submit'), array('class' => 'btn btn-primary')); ?>
<?php $this->endWidget(); ?>
