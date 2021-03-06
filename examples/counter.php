<?php
ob_start();
//initialize class loader
require(dirname(__FILE__).'/../lib/vendor/SplClassLoader.php');
$classLoader = new SplClassLoader('Mephir\ETagSession', dirname(__FILE__).'/../lib');
$classLoader->register();

use Mephir\ETagSession\ETagSession;
use Mephir\ETagSession\SessionHandler;
$session = ETagSession::createInstance(new SessionHandler\FilesSessionHandler(), array('save_path' => dirname(__FILE__).'/sessions'));
if ($session->has('counter'))
{
  $session->set('counter', $session->get('counter') + 1);
} else {
  $session->set('counter', 1);
}
$session->set('last_visit', time());
ob_end_flush();

foreach ($session->getHeaders() as $header)
{
  header($header);
}
?>
<html>
  <body>
    Visits: <?php echo $session->get('counter'); ?><br />
    Last visit: <?php echo date('d-m-Y H:i:s e', $session->get('last_visit')); ?>
  </body>
</html>