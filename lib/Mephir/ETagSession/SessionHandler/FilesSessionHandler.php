<?php

namespace Mephir\ETagSession\SessionHandler;

/**
 * FilesSessionHandler is reimplementation of default PHP files session handler
 * @see http://www.php.net/manual/en/class.sessionhandlerinterface.php
 *
 * @package    etagtrackingpixel
 * @subpackage session
 * @author     Pawel Wilk <pwilkmielno@gmail.com>
 */
class FilesSessionHandler implements SessionHandlerInterface
{
  protected
    $path;

  public function __cosntruct($path = '/tmp')
  {
    $this->path = $path;
  }

  public function close()
  {
    //
  }

  public function destroy($session_id)
  {
    //
  }

  public function gc($maxlifetime)
  {
    //
  }

  public function open($save_path, $name)
  {
    //
  }

  public function read($session_id)
  {
    //
  }

  public function write($session_id, $session_data)
  {
    //
  }
}