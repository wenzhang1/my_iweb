<?php
return array(
	'logs'=>array(
		'path'=>'logs/log',
		'type'=>'file'
	),
	'DB'=>array(
		'type'=>'mysql',
        'tablePre'=>'my_iweb_',
		'read'=>array(
			array('host'=>'localhost:3306','user'=>'root','passwd'=>'5953268','name'=>'my_iweb'),
		),

		'write'=>array(
			'host'=>'localhost:3306','user'=>'root','passwd'=>'5953268','name'=>'my_iweb',
		),
	),
	'langPath' => 'language',
	'viewPath' => 'views',
    'classes' => 'classes.*',
    'rewriteRule' =>'url',
	'theme' => 'default',		//主题
	'skin' => 'default',		//风格
	'timezone'	=> 'Etc/GMT-8',
	'upload' => 'upload',
	'dbbackup' => 'backup/database',
	'safe' => 'cookie',
	'safeLevel' => 'none',
	'lang' => 'zh_sc',
	'debug'=> true,
	'configExt'=> array('site_config'=>'config/site_config.php'),
	'encryptKey'=>'b7b63e4dd7fd3876b22f089b9090307f',
);
?>
