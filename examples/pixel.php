<?php
//initialize class loader
require(dirname(__FILE__).'/../lib/vendor/SplClassLoader.php');
$classLoader = new SplClassLoader('Mephir\ETagSession', dirname(__FILE__).'/../lib');
$classLoader->register();

use Mephir\ETagSession\ETagSession;
use Mephir\ETagSession\SessionHandler;

$etag = ETagSession::createInstance(new SessionHandler\FilesSessionHandler(), array('save_path' => dirname(__FILE__).'\sessions'));