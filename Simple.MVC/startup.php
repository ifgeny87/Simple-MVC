<?php
if (version_compare(phpversion(), '5.5.0', '<') == true) { die ('PHP5.5 Only'); }

// Константы:
define('DIRSEP', DIRECTORY_SEPARATOR);

// Путь до системной папки:
$sys_path = realpath(dirname(__FILE__));
if($sys_path)
	define('SYS_PATH', $sys_path . DIRSEP);
unset($sys_path);

// Путь до сайта:
$app_path = realpath(dirname(__FILE__) . DIRSEP . '..' . DIRSEP . 'app');
if($app_path)
	define('APP_PATH', $app_path . DIRSEP);
unset($app_path);

// Загрузка классов "на лету"
function __autoload($class_name) {
	$filename = $class_name . '.php';

	$files = [
		APP_PATH . 'classes' . DIRSEP . $filename,
		SYS_PATH . 'classes' . DIRSEP . $filename
	];

	foreach($files as $file) {
		if(is_readable($file)) {
			include $file;
			return;
		}
	}
	
	return false;
}

Class HttpException Extends \Exception
{
}