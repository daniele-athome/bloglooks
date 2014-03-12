A new comment has been posted by <?php if ($comment->author_id): ?>
user <b><?php echo $comment->author->name; ?></b> &lt;<a href="mailto:<?php echo $comment->author->login; ?>"><?php echo $comment->author->login; ?></a>&gt;
<?php else: ?><?php
    echo $comment->anon_name; ?> &lt;<a href="mailto:<?php echo $comment->anon_email; ?>"><?php echo $comment->anon_email; ?></a>&gt;
<?php endif; ?>
<br/>
<br/>

<?php echo nl2br(CHtml::encode($comment->content)); ?>

<br/>
<br/>
--<br/>
<?php echo CHtml::link('Reply to this comment', $comment->getUrl(null, true)); ?><br/>
<?php echo $comment->getUrl(null, true); ?>
