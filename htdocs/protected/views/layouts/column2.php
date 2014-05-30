<?php $this->beginContent('//layouts/main'); ?>

<div class="row">
    <div class="col-md-9">
    <?php echo $content; ?>
    </div>

    <div class="col-md-3">

    <div class="well sidebar" id="sidebar-pages">
    <?php
    $pagelist = array();
    foreach ($this->pages as $page) {
        $pagelist[] = array('label' => $page->title, 'url' => array('page/view', 'name' => $page->name));
    }
    $items = array_merge(array(
            array('label'=>Yii::t('app', 'Pages'), 'itemOptions' => array('class' => 'nav-header'))),
        $pagelist);

    $this->widget('zii.widgets.CMenu', array(
        'htmlOptions' => array('class' => 'nav nav-list'),
        'items'=>$items)); ?>
    </div>

    <div class="well sidebar" id="sidebar-archives">
    <?php
    $archlist = array();
    foreach ($this->archives as $date) {
        $month = Yii::app()->locale->dateFormatter->format(Yii::t('Post', 'MMMM yyyy'), sprintf('%d-%02d-01', $date['year'], $date['month']));
        $archlist[] = array('label' => Yii::t('Post', '{month} ({count})', array('{month}' => $month, '{count}' => $date['num'])), 'url' =>
            array('post/archives', 'year' => $date['year'], 'month' => $date['month']), 'itemOptions' => array('class' => 'small'));
    }
    $items = array_merge(array(
            array('label'=>Yii::t('app', 'Archives'), 'itemOptions' => array('class' => 'nav-header'))),
        $archlist);

    $this->widget('zii.widgets.CMenu', array(
        'htmlOptions' => array('class' => 'nav nav-list'),
        'items'=>$items)); ?>
    </div>

    <!-- TODO
    <div class="well sidebar" id="sidebar-tags">
    <span class="nav-header">
    <?php echo Yii::t('app', 'Tags'); ?>
    </span>
    </div>
     -->

    <div class="well sidebar" id="sidebar-menu">
    <?php
    $items = array(
        array('label'=>Yii::t('app', 'Menu'), 'itemOptions' => array('class' => 'nav-header')),
        array('label'=>Yii::t('app', 'Login'), 'url'=>array('site/login'), 'visible'=>Yii::app()->user->isGuest),
        array('label'=>Yii::t('app', '{feed} Atom', array('{feed}' => CHtml::image(Yii::app()->baseUrl . '/images/feed.png', 'Atom feed'))), 'url'=>array('post/feed', 'type' => 'atom', 'language' => $this->language)),
        array('label'=>Yii::t('app', '{feed} RSS', array('{feed}' => CHtml::image(Yii::app()->baseUrl . '/images/feed.png', 'Atom feed'))), 'url'=>array('post/feed', 'type' => 'rss', 'language' => $this->language)),
    );

    if (Yii::app()->user->checkAccess('editor') || Yii::app()->user->checkAccess('admin')) {
        $items[] = array('label'=>Yii::t('app', 'Manage posts'), 'url'=>array('post/admin'));
        $items[] = array('label'=>Yii::t('app', 'Manage pages'), 'url'=>array('page/admin'));
        if (Yii::app()->user->checkAccess('admin')) {
            $items[] = array('label'=>Yii::t('app', 'Manage users'), 'url'=>array('user/admin'));
        }
    }

    $items[] = array('label'=>Yii::t('app', 'Logout'), 'url'=>array('site/logout'), 'visible'=>!Yii::app()->user->isGuest);

    $this->widget('zii.widgets.CMenu', array(
        'htmlOptions' => array('class' => 'nav nav-list'),
        'encodeLabel' => false,
        'items'=>$items,
    ));
    ?>
    </div>

    </div>

</div>

<?php $this->endContent();
