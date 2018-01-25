<?php
// HTTP
define('HTTP_SERVER', 'http://192.168.1.49/preprod/migan/admin/');
define('HTTP_CATALOG', 'http://192.168.1.49/preprod/migan/');

// HTTPS
define('HTTPS_SERVER', 'http://192.168.1.49/preprod/migan/admin/');
define('HTTPS_CATALOG', 'http://192.168.1.49/preprod/migan/');

// DIR
define('DIR_APPLICATION', 'C:/wampserver/www/migan/admin/');
define('DIR_SYSTEM', 'C:/wampserver/www/migan/system/');
define('DIR_IMAGE', 'C:/wampserver/www/migan/image/');
define('DIR_STORAGE', 'C:/wampserver/storage/');
define('DIR_CATALOG', 'C:/wampserver/www/migan/catalog/');
define('DIR_LANGUAGE', DIR_APPLICATION . 'language/');
define('DIR_TEMPLATE', DIR_APPLICATION . 'view/template/');
define('DIR_CONFIG', DIR_SYSTEM . 'config/');
define('DIR_CACHE', DIR_STORAGE . 'cache/');
define('DIR_DOWNLOAD', DIR_STORAGE . 'download/');
define('DIR_LOGS', DIR_STORAGE . 'logs/');
define('DIR_MODIFICATION', DIR_STORAGE . 'modification/');
define('DIR_SESSION', DIR_STORAGE . 'session/');
define('DIR_UPLOAD', DIR_STORAGE . 'upload/');

// DB
define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'miganoc');
define('DB_PORT', '3306');
define('DB_PREFIX', 'migan_');

// OpenCart API
define('OPENCART_SERVER', 'https://www.opencart.com/');
