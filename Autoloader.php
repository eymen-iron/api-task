<?php

class Autoloader
{
	public static function register()
	{
		spl_autoload_register(function ($class) {
			$file = BASE_PATH . "/classes/{$class}.php";
			if (file_exists($file)) {
				require $file;
				return true;
			}
			return false;
		});
	}
}