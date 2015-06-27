<?php
$this->pageTitle = Yii::t('app', 'Archives');
$month = Yii::app()->locale->dateFormatter->format(Yii::t('Post', 'MMMM yyyy'), sprintf('%d-%02d-01', $year, $month));

?>
<div class="nav muted">
<?php echo Yii::t('Post', 'Posts in <strong>{month}</strong>', array('{month}' => $month)); ?>
</div>
<?php

echo $this->renderPartial('_list', array('dataProvider'=>$dataProvider, 'comments_disabled' => $comments_disabled));
