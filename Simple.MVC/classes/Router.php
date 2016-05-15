<?php

Class Router {
	private
		$paths = [];

	function __construct() {
		// регистрирую системные контроллеры
		if(is_dir($path = SYS_PATH . 'controllers'))
			$this->registerPath($path);
		// ... и контроллеры приложения
		if(is_dir($path = APP_PATH . 'controllers'))
			$this->registerPath($path);
	}

	function registerPath($path) {
		$realpath = realpath($path);
		
		if (!is_dir($realpath)) {
			throw new Exception("Invalid controller path: `{$path}`");
		}

		$this->paths[] = $realpath . DIRSEP;
		
		return $this;
    }
	
	function delegate() {
		// Анализируем путь
		$this->getController($file, $controller, $action, $args);
		
		// Файл доступен?
		if (is_readable($file) == false) {
			return new \Exception('Controller not found', 500);
		}

		// Подключаю файл
		include($file);

		// Создам экземпляр контроллера
		$class = 'Controller_' . ucwords(strtolower($controller));
		
		if(!class_exists($class)) {
			return new \Exception("Class `{$class}` not found", 500);
		}
		
		$controller = new $class;

		// Действие доступно?
		if (is_callable(array($controller, $action)) == false) {
			return new \Exception('Action not found', 500);
		}

		// Выполняем действие
		return $controller->$action();
	}
	
	private function getController(&$file, &$controller, &$action, &$args) {
		$route = (empty($_GET['route'])) ? '' : $_GET['route'];

		if (empty($route)) { $route = 'index'; }

		// Получаем раздельные части
		$route = trim($route, '/\\');
		$controller = null;
		
		for($i = count($this->paths) - 1; $i >= 0; $i--) {
			if($controller) break;
		
			$parts = explode('/', $route);
			$cmd_path = $this->paths[$i];

			// Находим правильный контроллер
			foreach ($parts as $part) {
				if(empty($part) || substr($part, 0, 1) == '.')
					continue;
			
				$fullpath = $cmd_path . $part;

				// Есть ли папка с таким путём?
				if(is_dir($fullpath)) {
					$cmd_path .= $part . DIRSEP;
					array_shift($parts);
					continue;
				}

				// Находим файл
				if (is_readable($fullpath . '.php')) {
					$controller = $part;
					array_shift($parts);
					break;
				}
			}
		}

		if (empty($controller)) { $controller = 'index'; }

		// Получаем действие
		$action = array_shift($parts);
		if (empty($action)) { $action = 'index'; }

		$file = $cmd_path . $controller . '.php';
		$args = $parts;
	}

}
