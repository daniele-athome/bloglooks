<?php
$form=$this->beginWidget('CActiveForm', array(
    'id'=>'comment-form',
    'enableClientValidation'=>false,
    'htmlOptions' => array('class' => 'form-comment'),
));
?>

<a name="comment-form"></a>

<?php if (isset($legend)): ?>
<legend><?php echo $legend ?></legend>
<?php endif; ?>

<?php if (Yii::app()->user->isGuest): ?>

<div class="form-group">
<?php echo $form->textField($model,'anon_name',array('class'=>'form-control','maxlength'=>50, 'placeholder' => Yii::t('User', 'Name'))); ?>
<?php echo $form->error($model,'anon_name',array('class' => 'alert alert-error')); ?>
</div>

<div class="form-group">
<?php echo $form->textField($model,'anon_email',array('class'=>'form-control','maxlength'=>100, 'placeholder' => Yii::t('User', 'Email'))); ?>
<?php echo $form->error($model,'anon_email',array('class' => 'alert alert-error')); ?>
</div>

<?php endif; ?>

<?php echo $form->hiddenField($model, 'reply_to', array('id' => 'comment_reply_to')); ?>
<?php echo $form->textArea($model,'content',array('rows'=>10, 'cols'=>80, 'class' => 'col-md-12 form-control', 'id' => 'comment_text')); ?>

<div class="container-fluid">
<div class="row">
<div id="recaptcha_form" class="col-md-12 recaptcha_form" style="display: none">

    <div id="recaptcha_image" class="recaptcha_image"></div>

    <div class="recaptcha_input">
        <input type="text" class="col-md-12 form-control" id="recaptcha_response_field" name="recaptcha_response_field">
    </div>

    <ul class="recaptcha_options">
        <li>
        <a href="javascript:Recaptcha.reload()">
        <i class="icon-refresh"></i>
        <span class="captcha_hide">Get another CAPTCHA</span>
        </a>
        </li>
        <li class="recaptcha_only_if_image">
        <a href="javascript:Recaptcha.switch_type('audio')">
        <i class="icon-volume-up"></i><span class="captcha_hide"> Get an audio CAPTCHA</span>
        </a>
        </li>
        <li class="recaptcha_only_if_audio">
        <a href="javascript:Recaptcha.switch_type('image')">
        <i class="icon-picture"></i><span class="captcha_hide"> Get an image CAPTCHA</span>
        </a>
        </li>
        <li>
        <a href="javascript:Recaptcha.showhelp()">
        <i class="icon-question-sign"></i><span class="captcha_hide"> Help</span>
        </a>
        </li>
    </ul>

</div>
</div>
</div>

<?php $this->widget('application.extensions.recaptcha.EReCaptcha',
   array('model'=>$model, 'attribute'=>'captcha',
         'theme'=>'custom', 'language'=>$this->language,
         'customThemeWidget' => 'recaptcha_form',
         'publicKey'=>Yii::app()->params['recaptcha']['publicKey'])) ?>
<?php echo CHtml::error($model, 'captcha', array('class' => 'alert alert-error')); ?>

<?php echo CHtml::submitButton(Yii::t('app', 'Submit'), array('class' => 'btn btn-primary')); ?>
<?php $this->endWidget(); ?>
