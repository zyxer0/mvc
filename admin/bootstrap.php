<?php
session_start();

require_once dirname(dirname(__FILE__)) . '/config/config.php';
$settings = (object) parse_ini_file(dirname(dirname(__FILE__)) . '/config/settings.ini');

// подключаем файлы ядра
require_once dirname(dirname(__FILE__)) . '/web/core/db.php';
require_once 'core/model.php';
require_once 'core/view.php';
require_once 'core/controller.php';
require_once dirname(dirname(__FILE__)) . '/web/lib/validation.php';

/*
Здесь обычно подключаются дополнительные модули, реализующие различный функционал:
	> аутентификацию
	> кеширование
	> работу с формами
	> абстракции для доступа к данным
	> ORM
	> Unit тестирование
	> Benchmarking
	> Работу с изображениями
	> Backup
	> и др.
*/

require_once 'core/route.php';
Route::start(); // запускаем маршрутизатор
