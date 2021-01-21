<?php

define('MAIN_EMAIL', '');
define('MAIN_NAME', '');
define('MAIN_TITLE', '');
define('BASE_URL', 'http://localhost');
define('APP_ROOT', rtrim($_SERVER['DOCUMENT_ROOT'], '/'));
define('WEB_ROOT', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));
define('ERROR_LOGFILE', APP_ROOT.'/app/logs/errors_log.html');
define('EXCEPTION_LOGFILE', APP_ROOT.'/app/logs/exceptions_log.html');
define('CHARSET', 'utf-8');
//productionmode or testmode
define('PRODUCTIONMODE', false);
//languages
$languages = ['en'];
define('MAINLANGUAGE', 'en');
//prefixes
$prefixes = ['admin'];
