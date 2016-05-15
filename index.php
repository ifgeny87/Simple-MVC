<?php
error_reporting (E_ALL);
define('LOAD_ACCESS', microtime(1));

require_once('Simple.MVC/startup.php');
require_once('config.php');

// Подключаюсь к базе
Registry::set('db',
	new \PDO(sprintf('%s:host=%s;dbname=%s', $config['sql_protocol'], $config['sql_hostname'], $config['sql_database']),
		$config['sql_username'], $config['sql_password']));

// Создаю роутер
Registry::set('router', $router = new \Router);

// Создаю объект шаблонов
Registry::set('template', $template = new \Template);

// Освобождаю конфиг
unset($config);

// Выполняю работу
$result = $router->delegate();

if($result instanceof \Exception)
	throw $result;