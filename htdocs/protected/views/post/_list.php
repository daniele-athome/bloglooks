<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
	'template'=>"{items}\n{pager}",
    'ajaxUpdate' => false,
    'pagerCssClass' => 'pager pagination-centered',
    'pager' => array(
        'class' => 'ext.CustomLinkPager',
        'header' => false,
        'htmlOptions' => array('class' => 'pager'),
        'maxButtonCount' => 0,
        'prevPageLabel' => Yii::t('Post', '&larr; Newer'),
        'nextPageLabel' => Yii::t('Post', 'Older &rarr;'),
        'hiddenPageCssClass' => 'disabled',
        'previousPageCssClass' => 'previous',
        'nextPageCssClass' => 'next',
        'hideFirstPageButton' => true,
        'hideLastPageButton' => true,
    ),
    'viewData' => array(
        'comments_disabled' => $comments_disabled,
    ),
));
