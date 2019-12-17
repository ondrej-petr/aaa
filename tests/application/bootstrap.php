<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 'On');

// Define project base directory
define('BASE_PATH', realpath(dirname(__FILE__) . '/../../'));

// Define path to application directory
defined('APPLICATION_PATH')
	|| define('APPLICATION_PATH', realpath(BASE_PATH . '/application'));

// Define path to global directory
defined('GLOBAL_PATH')
	|| define('GLOBAL_PATH', realpath(APPLICATION_PATH . '/../../global/application'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
	realpath(APPLICATION_PATH . '/../library'),
	realpath(GLOBAL_PATH . '/../library'),
	get_include_path()
)));

// Define application environment
define('APPLICATION_ENV', 'development');

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
require_once 'controllers/ControllerTestCase.php';

function d($v, $l = '') {}

function f($v, $t = Zend_Log::INFO) {}
