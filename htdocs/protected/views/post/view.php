<?php
$this->pageTitle = $model->title;

// publish status
if ($model->published) {
    $status = Yii::t('Post', 'Published:');
    $stamp = $model->published;
}
else {
    $status = Yii::t('Post', 'Draft:');
    $stamp = $model->modified;
}

?>

<?php if (Yii::app()->user->hasFlash('commentSubmitted')): ?>
<div class="alert alert-warning">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
	<?php echo Yii::app()->user->getFlash('commentSubmitted'); ?>
</div>
<?php endif; ?>

<?php $this->renderPartial('_view', array('data' => $model, 'standalone' => true)); ?>

<div class="page-title">
    <span class="muted"><?php echo Yii::t('Post', 'Share this post'); ?></span>
</div>

<!-- Twitter -->
<div class="share-button">
<iframe allowtransparency="true" frameborder="0" scrolling="no"
  src="//platform.twitter.com/widgets/tweet_button.html?url=<?php echo urlencode($model->getUrl(true)); ?>&amp;text=<?php echo CHtml::encode($model->title); ?>&amp;lang=<?php echo $this->language; ?>"
  style="width:110px; height:20px;"></iframe>
</div>

<!-- Facebook -->
<div class="share-button">
<iframe src="//www.facebook.com/plugins/like.php?href=<?php echo urlencode($model->getUrl(true)); ?>&amp;send=false&amp;layout=button_count&amp;width=120&amp;show_faces=true&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21"
  scrolling="no"
  frameborder="0"
  style="border:none; overflow:hidden; width:120px; height:21px;"
  allowTransparency="true"></iframe>
</div>

<!-- Google+ -->
<div class="share-button">
<div class="g-plusone" data-size="medium" data-href="<?php echo CHtml::encode($model->getUrl(true)); ?>"></div>
</div>

<script type="text/javascript">
  window.___gcfg = {lang: '<?php echo $this->language; ?>'};

  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>

<div id="comments">
		<div class="page-title">
		    <span class="muted">
		    <?php
		    if (!$model->commentCount)
		        echo Yii::t('Post', 'No comments.');
		    else
		        echo Yii::t('Post', '{n} comment|{n} comments', array($model->commentCount));
		    ?>
		    </span>
		</div>

		<?php $this->renderPartial('_comments',array(
			'post'=>$model,
			'comments'=>$model->comments,
		)); ?>

		<?php if (!Yii::app()->user->isGuest): ?>
		<div class="form-comment well">
		<?php $this->renderPartial('/comment/_form',array(
		    'model'=>$comment,
		    'legend'=>Yii::t('Post', 'Leave a comment'),
		)); ?>
		</div>
		<?php endif; ?>

</div><!-- comments -->
