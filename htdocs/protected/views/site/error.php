<?php
$this->pageTitle=Yii::t('app', 'Error');
?>

<h2><?php echo Yii::t('app', 'Error {code}', array('{code}' => $code)); ?></h2>

<div class="error page-content">
<?php echo CHtml::encode($message); ?>
</div>