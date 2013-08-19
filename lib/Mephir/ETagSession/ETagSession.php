<?php

namespace Mephir\ETagSession;

/**
 * ETagSession allows you to use session with id is stored in ETag header instead of cookie/url
 *
 * @package    etagtrackingpixel
 * @subpackage session
 * @author     Pawel Wilk <pwilkmielno@gmail.com>
 */
class ETagSession {
  protected
    $options = array();

  /**
   * Class constructor.
   *
   * @see initialize()
   */
  public function __construct($options = array())
  {
    $this->initialize($options);

    if ($this->options['auto_shutdown'])
    {
      register_shutdown_function(array($this, 'shutdown'));
    }
  }

  /**
   * Initializes this Storage instance.
   *
   * Available options:
   *
   *  * auto_shutdown: Whether to automatically save the changes to the session (true by default)
   *
   * @param  array $options  An associative array of options
   *
   * @return bool true, if initialization completes successfully, otherwise false
   */
  public function initialize($options = array())
  {
    $this->options = array_merge(array(
      'auto_shutdown' => true,
    ), $options);
  }

  /**
   * Returns the option array.
   *
   * @return array The array of options
   */
  public function getOptions()
  {
    return $this->options;
  }

  /**
   * Reads data from this storage.
   *
   * The preferred format for a key is directory style so naming conflicts can be avoided.
   *
   * @param  string $key  A unique key identifying your data
   *
   * @return mixed Data associated with the key
   */
  public function read($key)
  {
    //
  }

  /**
   * Regenerates id that represents this storage.
   *
   * @param  boolean $destroy Destroy session when regenerating?
   *
   * @return boolean True if session regenerated, false if error
   */
  public function regenerate($destroy = false)
  {
    //
  }

  /**
   * Removes data from this storage.
   *
   * The preferred format for a key is directory style so naming conflicts can be avoided.
   *
   * @param  string $key  A unique key identifying your data
   *
   * @return mixed Data associated with the key
   */
  public function remove($key)
  {
    //
  }

  /**
   * Executes the shutdown procedure.
   */
  public function shutdown()
  {
    //
  }

  /**
   * Writes data to this storage.
   *
   * The preferred format for a key is directory style so naming conflicts can be avoided.
   *
   * @param  string $key   A unique key identifying your data
   * @param  mixed  $data  Data associated with your key
   */
  public function write($key, $data)
  {
    //
  }
}