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
  protected $file_pointer = null;

  public function close()
  {
    if ($this->file_pointer !== false)
    {
      flock($this->file_pointer, LOCK_UN);
      fclose($this->file_pointer);
      $this->file_pointer = null;
      return true;
    }
    return false;
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
    $this->file_pointer = fopen($save_path.'/'.$name, 'r+');
    if ($this->file_pointer === false)
    {
      return false;
    }
    flock($this->file_pointer, LOCK_EX);
    return true;
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