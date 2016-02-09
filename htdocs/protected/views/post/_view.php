<?php
// publish status
if ($data->published) {
    $status = Yii::t('Post', 'Published:');
    $stamp = $data->published;
}
else {
    $status = Yii::t('Post', 'Draft:');
    $stamp = $data->modified;
}
?>
<div class="post">
    <div class="page-title">
    <h2>
    <?php if (!isset($standalone)): ?>
    <?php echo CHtml::link(CHtml::encode($data->title), $data->url); ?>
    <?php else: ?>
    <?php echo CHtml::encode($data->title); ?>
    <?php endif; ?>
    </h2>
    </div>

    <small class="text-muted">
    <?php echo $status; ?> <?php echo $stamp; ?> <?php echo Yii::t('app', 'by'); ?> <?php echo CHtml::link($data->author->name, array('post/index', 'author' => $data->author->id)); ?>
    </small>

    <div class="page-content">
    <?php
    if (strpos($data->content, '[pluspics:') !== false) {
        // check with regex
        $matches = false;
        if (preg_match_all('/\[pluspics\:(\d+):(\d+)\]/', $data->content, $matches) && count($matches) == 3) {
            $data->content = preg_replace('/(\[pluspics\:\d+:\d+\])/', '', $data->content);

            // loop through matches
            $match_uid = $matches[1];
            $match_aid = $matches[2];
            if (count($match_uid) == count($match_aid)) {
                // register pluspics stuff
                Yii::app()->clientScript->registerCoreScript('pluspics');

                foreach ($match_uid as $i => $uid) {
                    $aid = $match_aid[$i];
                    Yii::app()->clientScript->registerScript('pluspics-' . $uid . '-' . $aid, <<<EOF
$(document).ready(function(){
    $('.pluspics-{$uid}-{$aid}').plusPics({
        userId: '{$uid}',
        albumId: '{$aid}',
        numImages: 100,
        title: ''
    });
});
EOF
                        );
                }
            }
        }

        if (preg_match_all('/\[img\:(.*)\]/', $data->content, $matches) && count($matches) == 2) {
            // loop through matches
            $match_full = $matches[0];
            $match_img = $matches[1];
            foreach ($match_img as $i => $img)
                $data->content = str_replace($match_full[$i], '<img src="'.Yii::app()->baseUrl . '/images/'.$img.'"/>', $data->content);
        }
    }

    $this->beginWidget('CMarkdown');
    echo $data->content;
    $this->endWidget();
    ?>
    </div>

    <?php if (isset($standalone) and $standalone and $data->attachmentCount > 0): ?>
    <div id="attachments">
		<div class="attachments-title">
		    <span class="text-muted">
            <?php echo CHtml::image(Yii::app()->baseUrl . '/images/attach.png', '', array('style' => 'width: 18px; height: 18px')); ?>
		    <?php echo Yii::t('Post', '{n} attachment|{n} attachments', array($data->attachmentCount)); ?>
		    </span>
		</div>
		<ul class="unstyled">
		<?php foreach ($data->attachments as $att): ?>
		<li>
		    <?php echo CHtml::link($att->filename, array('attachment/download', 'id' => $att->id)); ?>
		    <small class="text-muted"><?php echo Yii::app()->format->formatSize($att->size); ?></small>
		</li>
		<?php endforeach; ?>
		</ul>
    </div>
    <?php endif; ?>

    <?php if ($data->tags): ?>
    <div class="nav post-footer">
    <small><strong>Tags:</strong></small> <?php echo implode(' ', $data->tagLinks); ?>
    </div>
    <?php endif; ?>

    <?php if ($data->languages and count($data->languages) > 1): ?>
    <small class="nav post-footer">
    <b><?php echo Yii::t('app', 'Available in:'); ?></b>
    <?php
        foreach ($data->languages as $lang):
            echo CHtml::link(CHtml2::flagImage($lang->language),
                array('post/view', 'id' => $data->id, "language" => $lang->language), array('title' => $lang->language));
            echo '&nbsp;';
        endforeach;
    ?>
    </small>
    <?php endif; ?>

    <small class="nav text-muted post-footer">
		<?php echo CHtml::link(Yii::t('Post', 'Permalink'), $data->getUrl(true)); ?> |

        <?php if (!isset($standalone)): ?>
            <?php if ($data->comments_enabled && !$comments_disabled): ?>
            <?php echo CHtml::link(Yii::t('Post', 'Comments ({n})', array('{n}' => $data->commentCount)), $data->url .'#comments'); ?> |
            <?php if ($data->attachmentCount > 0): ?>
            <?php echo CHtml::link(Yii::t('Post', 'Attachments ({n})', array('{n}' => $data->attachmentCount)), $data->url .'#attachments'); ?> |
            <?php endif; ?>
        <?php endif; ?>

        <?php elseif (Yii::app()->user->checkAccess('editor') || Yii::app()->user->checkAccess('admin')): ?>
        <?php echo CHtml::link(Yii::t('app', 'Edit'), array('post/edit', 'id' => $data->id, 'language' => $data->language)); ?>&nbsp;|
        <?php echo CHtml::link(Yii::t('app', 'Delete'), array('post/delete', 'id' => $data->id, 'language' => $data->language), array(
            'submit'=> array('post/delete', 'id' => $data->id, 'language' => $data->language),
            'confirm' => Yii::t('Post', 'Delete {lang} version of this post?', array('{lang}' => $data->language)))); ?>&nbsp;|
        <?php endif; ?>

		<?php echo Yii::t('app', 'Last modified:'); ?> <?php echo $data->modified; ?>
	</small>
</div>
