<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="language" content="<?php echo $this->language; ?>" />
<meta name="title" content="<?php echo $this->config['blog_name']; ?>"/>
<meta name="description" content="<?php echo $this->config['blog_description']; ?>"/>
<meta name="keywords" content="<?php echo $this->config['blog_keywords']; ?>"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<link rel="shortcut icon" type="image/x-icon" href="<?php echo Yii::app()->request->baseUrl; ?>/favicon.ico" />
<link rel="alternate" type="application/rss+xml" href="<?php echo $this->createUrl('post/feed', array('type' => 'rss', 'language' => $this->language)); ?>" />
<link rel="alternate" type="application/atom+xml" href="<?php echo $this->createUrl('post/feed', array('type' => 'atom', 'language' => $this->language)); ?>" />

<?php
if (isset($this->config['google_siteverification']))
    Yii::app()->clientScript->registerMetaTag($this->config['google_siteverification'], 'google-site-verification');

if (isset($this->config['google_analytics_trackingid']))
    Yii::app()->clientScript->registerScript('google-analytics', <<<EOF
var _gaq = _gaq || [];
_gaq.push(['_setAccount', '{$this->config['google_analytics_trackingid']}']);
_gaq.push(['_trackPageview']);

(function() {
  var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
  ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
})();
EOF
, CClientScript::POS_HEAD);

Yii::app()->clientScript->registerCoreScript('core');
?>

<title><?php echo CHtml::encode($this->pageTitle . ' - ' . $this->config['blog_name']); ?></title>

<!--[if lt IE 9]>
<script src="<?php echo Yii::app()->baseUrl; ?>/js/html5shiv.min.js"></script>
<script src="<?php echo Yii::app()->baseUrl; ?>/js/respond.min.js"></script>
<![endif]-->
</head>

<body>

<div class="container-narrow container">

   <header class="container-inner row" id="header">
        <div class="masthead">
        <?php
        $items = array(array('label'=>Yii::t('app', 'Home'), 'url'=>array('post/index')));
        $page = $this->get_page_info($this->config['header_pagelink']);
        if ($page)
            $items[] = array('label'=>$page->title, 'url'=>array('page/view', 'name' => $page->name));

        $items[] = array('label'=>Yii::t('app', 'Login'), 'url'=>array('site/login'), 'visible'=>Yii::app()->user->isGuest);
        $items[] = array('label'=>Yii::t('Post', 'New post'), 'url'=>array('post/new'), 'visible'=>(Yii::app()->user->checkAccess('editor') || Yii::app()->user->checkAccess('admin')));
        $items[] = array('label'=>Yii::t('app', 'Logout'), 'url'=>array('site/logout'), 'visible'=>!Yii::app()->user->isGuest);
        $this->widget('zii.widgets.CMenu', array(
            'htmlOptions' => array('class' => 'nav nav-pills pull-right'),
            'items'=>$items,
        ));
        ?>

        <h3 id="blogname"><?php echo CHtml::link(CHtml::encode($this->config['blog_name']), array('post/index'), array('encode' => true)); ?></h3>
        <h6 id="blogdesc" class="text-muted"><?php echo CHtml::encode($this->config['blog_description']); ?></h6>
        </div>
    </header>

    <div id="content">

    <!-- TODO breadcrumbs -->

    <?php echo $content; ?>

    </div>

    <footer class="footer page-footer row">
        <div class="container-fluid text-center text-muted credit">
        Powered by <a target="_blank" href="<?php echo Yii::app()->params['poweredByUrl']; ?>"><?php echo Yii::app()->name; ?></a> |
        Copyright &copy; <?php echo Yii::app()->params['copyrightHolder']; ?><br/>
        <?php if ($this->availableLanguages):
            echo Yii::t('app', 'Change language:'); ?>

        <?php
        foreach ($this->availableLanguages as $lang) {
            echo CHtml::link(CHtml2::flagImage($lang),
                array('site/language', 'id' => $lang), array('title' => $lang));
            echo '&nbsp;';
        }
        ?>
        <br/>
        <?php endif; ?>
        <small>git revision: <?php echo $this->revisionUrl; ?></small><br/>
        <small><?php echo Yii::t('app', 'Page generated in {seconds} seconds', array('{seconds}' => round(Yii::getLogger()->getExecutionTime(), 3))); ?></small>
        </div>
    </footer>

</div>

</body>
</html>
