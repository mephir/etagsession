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
class FilesSessionHandler implements \SessionHandlerInterface
{
  private $save_path;

  public function open($save_path, $session_name)
  {
    $this->save_path = $save_path;
    if (!is_dir($this->save_path)) {
      mkdir($this->save_path, 0777);
    }
    return true;
  }

  public function close()
  {
    return true;
  }

  public function read($id)
  {
    return (string)@file_get_contents($this->save_path.'/sess_' . $id);
  }

  public function write($id, $data)
  {
    return file_put_contents($this->save_path.'/sess_' . $id, $data) === false ? false : true;
  }

  public function destroy($id)
  {
    $file = $this->save_path.'/sess_'.$id;
    if (file_exists($file)) {
      unlink($file);
    }
    return true;
  }

  public function gc($maxlifetime)
  {
    foreach (glob($this->save_path.'/sess_*') as $file) {
      if (filemtime($file) + $maxlifetime < time() && file_exists($file)) {
        unlink($file);
      }
    }
    return true;
  }
}