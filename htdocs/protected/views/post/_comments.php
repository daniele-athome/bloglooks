<?php foreach($comments as $i => $comment): ?>
<div class="comment well" id="c<?php echo $comment->id; ?>">

	<?php echo CHtml::link("#" . (count($comments) - $i), $comment->getUrl($post), array(
		'class'=>'cid',
		'title'=> Yii::t('Post', 'Permalink to this comment'),
	)); ?>

	<div class="page-header-small">
		<?php
		$userlink = $comment->author ?
                CHtml::link($comment->author->name, array('user/view', 'id' => $comment->author->id)) :
		        $comment->anon_name;

		if ($comment->follow_up)
			echo Yii::t('Post', '{user} replies to <a href="{reply}">comment</a>:',
				array('{user}' => $userlink,
					  '{reply}' => $comment->follow_up->getUrl($post)));
		else
			echo Yii::t('Post', '{user} says:',
					array('{user}' => $userlink));
		?>
	</div>

	<div class="muted">
		<small><?php echo $comment->timestamp; ?></small>
	</div>

	<div class="content">
		<?php echo nl2br(CHtml::encode($comment->content)); ?>
	</div>

	<small class="nav muted post-footer">
	<?php echo CHtml::link(Yii::t('Post', 'Reply'), '#comment-form', array('onclick' => 'replyComment('.$comment->id.'); return false;')); ?>
	</small>

</div><!-- comment -->
<?php endforeach; ?>
