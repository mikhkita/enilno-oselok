<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Колесо Онлайн',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.extensions.*',
	),

	'modules'=>array(
        'gii'=>array(
            'class'=>'system.gii.GiiModule',
            'password'=>'4815162342',
        ),
    ),

	'defaultController'=>'site',

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
			'identityCookie' => array('domain' => '.koleso.dev'),
		),
		'cache'=>array('class'=>'system.caching.CFileCache'),
		// 'db'=>array(
		// 	'connectionString' => 'sqlite:protected/data/blog.db',
		// 	'tablePrefix' => 'tbl_',
		// ),
		// uncomment the following to use a MySQL database
		
		'db'=>array(
			'class'=>'system.db.CDbConnection',
			'connectionString' => 'mysql:host=localhost;port=8889;dbname=koleso',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => 'root',
			'charset' => 'utf8',
			// 'tablePrefix' => 'tbl_',
			'tablePrefix' => '',
			'enableProfiling'=>true,
			'schemaCachingDuration'=>3600,
		),
		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'urlManager'=>array(
            'urlFormat'=>'path',
            'showScriptName' => false,
            'appendParams' => false,
            'rules'=>array(
            	// 'http://<city:[\w-]*>[.]?koleso.dev/sitemap.xml'=>array('sitemap/<city>.xml', 'caseSensitive'=>false),
            	'admin/<controller:\w+>'=>array('<controller>/adminIndex', 'caseSensitive'=>false),
            	'admin/<controller:\w+>/<action:\w+>'=>array('<controller>/admin<action>', 'caseSensitive'=>false),
            	'http://<city:[\w-]*>[.]?koleso.dev/shiny'=>array('kolesoOnline/category','urlSuffix'=>'/','defaultParams' => array('type'=>1,'city'=>"<page>"), "parsingOnly" => true),
            	'http://<city:[\w-]*>[.]?koleso.dev/diski'=>array('kolesoOnline/category','urlSuffix'=>'/','defaultParams' => array('type'=>2,'city'=>"<page>"), "parsingOnly" => true),
            	'http://<city:[\w-]*>[.]?koleso.dev/kolesa'=>array('kolesoOnline/category','urlSuffix'=>'/','defaultParams' => array('type'=>3,'city'=>"<page>"), "parsingOnly" => true),
            	'http://<city:[\w-]*>[.]?koleso.dev/tire'=>array('kolesoOnline/category','urlSuffix'=>'/','defaultParams' => array('type'=>1,'city'=>"<page>"), "parsingOnly" => true),
            	'http://<city:[\w-]*>[.]?koleso.dev/disc'=>array('kolesoOnline/category','urlSuffix'=>'/','defaultParams' => array('type'=>2,'city'=>"<page>"), "parsingOnly" => true),
            	'http://<city:[\w-]*>[.]?koleso.dev/wheel'=>array('kolesoOnline/category','urlSuffix'=>'/','defaultParams' => array('type'=>3,'city'=>"<page>"), "parsingOnly" => true),
            	'http://<city:[\w-]*>[.]?koleso.dev/'=>array('kolesoOnline/index','defaultParams' => array('city'=>"<page>"), 'caseSensitive'=>false, "parsingOnly" => true),
            	'http://<city:[\w-]*>[.]?koleso.dev/cart'=>array('kolesoOnline/cart','urlSuffix'=>'/','defaultParams' => array('city'=>"<page>"), "parsingOnly" => true),
            	'http://<city:[\w-]*>[.]?koleso.dev/pay'=>array('kolesoOnline/pay','urlSuffix'=>'/','defaultParams' => array('city'=>"<page>"), "parsingOnly" => true),
            	'http://<city:[\w-]*>[.]?koleso.dev/thanks'=>array('kolesoOnline/index','urlSuffix'=>'/','defaultParams' => array('thanks' => true,'city'=>"<page>"), "parsingOnly" => true),
            	'http://<city:[\w-]*>[.]?koleso.dev/shiny/<id:[\w-.]+>'=>array('kolesoOnline/detail','urlSuffix'=>'.html','defaultParams' => array('type'=>1,'city'=>"<page>"), "parsingOnly" => true),
            	'http://<city:[\w-]*>[.]?koleso.dev/diski/<id:[\w-.]+>'=>array('kolesoOnline/detail','urlSuffix'=>'.html','defaultParams' => array('type'=>2,'city'=>"<page>"), "parsingOnly" => true),
            	'http://<city:[\w-]*>[.]?koleso.dev/kolesa/<id:[\w-.]+>'=>array('kolesoOnline/detail','urlSuffix'=>'.html','defaultParams' => array('type'=>3,'city'=>"<page>"), "parsingOnly" => true),
            	'http://<city:[\w-]*>[.]?koleso.dev/tire/<id:[\w-.]+>'=>array('kolesoOnline/detail','urlSuffix'=>'.html','defaultParams' => array('type'=>1,'city'=>"<page>"), "parsingOnly" => true),
            	'http://<city:[\w-]*>[.]?koleso.dev/disc/<id:[\w-.]+>'=>array('kolesoOnline/detail','urlSuffix'=>'.html','defaultParams' => array('type'=>2,'city'=>"<page>"), "parsingOnly" => true),
            	'http://<city:[\w-]*>[.]?koleso.dev/wheel/<id:[\w-.]+>'=>array('kolesoOnline/detail','urlSuffix'=>'.html','defaultParams' => array('type'=>3,'city'=>"<page>"), "parsingOnly" => true),
				'http://<city:[\w-]*>[.]?koleso.dev/<controller:\w+>/<action:\w+>'=>array('<controller>/<action>', 'defaultParams' => array('city'=>"<page>"), 'caseSensitive'=>false, "parsingOnly" => true),
				'http://<city:[\w-]*>[.]?koleso.dev/<page:\w+>.html'=>array('kolesoOnline/page','defaultParams' => array('page'=>"<page>",'city'=>"<page>"), 'caseSensitive'=>false, "parsingOnly" => true),
            	'shiny'=>array('kolesoOnline/category','urlSuffix'=>'/','defaultParams' => array('type'=>1)),
            	'diski'=>array('kolesoOnline/category','urlSuffix'=>'/','defaultParams' => array('type'=>2)),
            	'kolesa'=>array('kolesoOnline/category','urlSuffix'=>'/','defaultParams' => array('type'=>3)),
            	'tire'=>array('kolesoOnline/category','urlSuffix'=>'/','defaultParams' => array('type'=>1)),
            	'disc'=>array('kolesoOnline/category','urlSuffix'=>'/','defaultParams' => array('type'=>2)),
            	'wheel'=>array('kolesoOnline/category','urlSuffix'=>'/','defaultParams' => array('type'=>3)),
            	'cart'=>array('kolesoOnline/cart','urlSuffix'=>'/', "parsingOnly" => true),
            	'pay'=>array('kolesoOnline/pay','urlSuffix'=>'/', "parsingOnly" => true),
            	''=>"kolesoOnline",
            	'cart'=>array('kolesoOnline/cart','urlSuffix'=>'/'),
            	'pay'=>array('kolesoOnline/pay','urlSuffix'=>'/'),
            	'thanks'=>array('kolesoOnline/index','urlSuffix'=>'/','defaultParams' => array('thanks' => true), "parsingOnly" => true),
            	'shiny/<id:[\w-.]+>'=>array('kolesoOnline/detail','urlSuffix'=>'.html','defaultParams' => array('type'=>1)),
            	'diski/<id:[\w-.]+>'=>array('kolesoOnline/detail','urlSuffix'=>'.html','defaultParams' => array('type'=>2)),
            	'kolesa/<id:[\w-.]+>'=>array('kolesoOnline/detail','urlSuffix'=>'.html','defaultParams' => array('type'=>3)),
            	'tire/<id:[\w-.]+>'=>array('kolesoOnline/detail','urlSuffix'=>'.html','defaultParams' => array('type'=>1)),
            	'disc/<id:[\w-.]+>'=>array('kolesoOnline/detail','urlSuffix'=>'.html','defaultParams' => array('type'=>2)),
            	'wheel/<id:[\w-.]+>'=>array('kolesoOnline/detail','urlSuffix'=>'.html','defaultParams' => array('type'=>3)),
            	'admin'=>'site/login',
            	'<controller:\w+>/<id:\d+>'=>array('<controller>/index', 'caseSensitive'=>false),
            	'<controller:\w+>/<action:\w+>'=>array('<controller>/<action>', 'caseSensitive'=>false),
            	// 'admin/<controller:\w+>/<filter_id:\d+>'=>array('<controller>/adminIndex', 'caseSensitive'=>false),
				'<page:\w+>.html'=>array('kolesoOnline/page','defaultParams' => array('page'=>"<page>"), 'caseSensitive'=>false),
			),
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
		'session' => array(
		   'class' => 'CDbHttpSession',
		   'cookieParams' => array('domain' => ($_SERVER["HTTP_HOST"] == "koleso.tomsk.ru")?'koleso.tomsk.dev':'.koleso.dev'),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>require(dirname(__FILE__).'/params.php'),
);