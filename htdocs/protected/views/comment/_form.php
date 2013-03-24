<?php
$form=$this->beginWidget('CActiveForm', array(
    'id'=>'comment-form',
    'enableClientValidation'=>false,
    'htmlOptions' => array('class' => 'form-comment'),
));

if (isset($legend)): ?>
<legend><?php echo $legend ?></legend>
<?php endif; ?>

<?php echo $form->textArea($model,'content',array('rows'=>10, 'cols'=>80, 'class' => 'span12')); ?>

<?php echo CHtml::submitButton(Yii::t('app', 'Submit'), array('class' => 'btn btn-primary')); ?>
<?php $this->endWidget(); ?>
