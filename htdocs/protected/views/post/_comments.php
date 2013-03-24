<?php foreach($comments as $i => $comment): ?>
<div class="comment well" id="c<?php echo $comment->id; ?>">

	<?php echo CHtml::link("#" . (count($comments) - $i), $comment->getUrl($post), array(
		'class'=>'cid',
		'title'=> Yii::t('Post', 'Permalink to this comment'),
	)); ?>

	<div class="page-header-small">
		<?php echo Yii::t('Post', '{user} says:',
		    array('{user}' => CHtml::link($comment->author->name, array('user/view', 'id' => $comment->author->id)))); ?>
	</div>

	<div class="muted">
		<small><?php echo $comment->timestamp; ?></small>
	</div>

	<div class="content">
		<?php echo nl2br(CHtml::encode($comment->content)); ?>
	</div>

</div><!-- comment -->
<?php endforeach; ?>
