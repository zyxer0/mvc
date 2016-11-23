<?php
session_start();
// еще изменения
require_once 'config/config.php';
$settings = (object) parse_ini_file("config/settings.ini");

// подключаем файлы ядра
require_once 'core/db.php';
require_once 'core/model.php';
require_once 'core/view.php';
require_once 'core/controller.php';
require_once 'lib/validation.php';
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
Route::$settings = $settings;
Route::start(); // запускаем маршрутизатор