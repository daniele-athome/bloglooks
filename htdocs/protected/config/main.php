<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'bloglooks',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	    'application.web.auth.*',
	    'application.web.helpers.*',
	    'ext.YiiMailer.YiiMailer',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'ciao',
		 	// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
	),

    'defaultController' => 'post',

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		// uncomment the following to enable URLs in path-format
	    'urlManager'=>array(
	        'urlFormat'=>'path',
	        'showScriptName'=>false,
	        'rules'=>array(
	            // posts
	            'tag/<tag:.*?>'=>'post/index',
	            'user/<author:\d+>' => 'post/index',
	            'feed.<type:\w+>' => 'post/feed',
	            ''=>'post/index',
	            '<year:\d{4}>/<month:\d{2}>/<id:\d+>/<title:.*?>'=>'post/view',
	            'posts' => 'post/admin',
	            // pages
	            'pages' => 'page/admin',
	            'page/edit' => 'page/edit',
	            'page/new' => 'page/new',
	            'page/delete' => 'page/delete',
	            'page/<name:\w+>' =>'page/view',
	            // users
	            'profile/<id:\d+>' => 'user/view',
	            'users' => 'user/admin',
	            // generic
	            '<action:(login|logout|language)>'=>'site/<action>',
	            '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
	        ),
	    ),
        /*
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		*/
		// uncomment the following to use a MySQL database
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=bloglooks',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => 'ciao',
			'charset' => 'utf8',
		),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
		'authManager'=>array('class'=>'CMemoryAuthManager'),
	    'messages'=>array('class'=>'CPhpMessageSource'),
	    'clientScript'=>array(
	        'defaultScriptFilePosition' => CClientScript::POS_END,
	        'coreScriptPosition' => CClientScript::POS_END,
	        'packages' => array(
	            // register this for everything needed to the app
	            'core' => array(
	                'baseUrl' => '',
	                'css' => array('css/main.css'),
	                'depends' => array('jquery', 'bootstrap'),
	            ),
	            'jquery' => array(
	                'baseUrl' => '',
	                'js' => array('js/jquery-1.11.3.min.js', 'js/jquery.easing-1.3.js'),
	            ),
	            'bootstrap' => array(
	                'baseUrl' => '',
	                'js' => array('js/bootstrap.min.js'),
	                'css' => array('css/bootstrap.min.css', 'css/navlist.css'),
	            ),
	            'pluspics' => array(
	                'baseUrl' => '',
	                'js' => array('js/pluspics.js'),
	                'css' => array('css/pluspics.css'),
	                'depends' => array('jquery'),
                ),
                'typewatch' => array(
                    'baseUrl' => '',
                    'js' => array('js/jquery.typewatch.js'),
                    'depends' => array('jquery'),
                )
	        ),
	    ),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'blog@casaricci.it',
	    // copyright holder
        'copyrightHolder' => 'Daniele Ricci',
	    // bloglooks website
        'poweredByUrl' => 'https://github.com/daniele-athome/bloglooks',
	    // git revision url
        'revisionUrl' => 'https://github.com/daniele-athome/bloglooks/commit/%s',
        // markdown reference url
        'markdownUrl' => 'http://daringfireball.net/projects/markdown/syntax',
        // recaptcha keys
        'recaptcha' => array(
            'publicKey' => 'PUBLIC-KEY',
            'privateKey' => 'PRIVATE-KEY',
	    ),
		// timeout in milliseconds after user stops typing
		'autosaveDelay' => 3000,
	),
);
