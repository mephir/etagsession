<?php

namespace Mephir\ETagSession;

/**
 * ETagSession allows you to use session with id is stored in ETag header instead of cookie/url
 *
 * @package    etagtrackingpixel
 * @subpackage session
 * @author     Pawel Wilk <pwilkmielno@gmail.com>
 */
class ETagSession //implements ArrayAccess
{
  protected
    $options = array(),
    $storage = null,
    $data = array(),
    $etag = '';

  protected static $instances = array();

  public static function createInstance(\SessionHandlerInterface $storage, $options = array(), $name = 'default')
  {
    if (isset(self::$instances[$name]))
    {
      throw new \LogicException(sprintf("Instance '%s' already exists", $name));
    }
    $className = __CLASS__;
    self::$instances[$name] = new $className($storage, $options);
    return self::$instances[$name];
  }

  public function getInstance($name = 'default')
  {
    if (!isset(self::$instances[$name]))
    {
      throw new \DomainException(sprintf("Instance '%s' does not exists", $name));
    }
    return self::$instances[$name];
  }

  public function hasInstance($name = 'default')
  {
    return isset(self::$instances[$name]);
  }

  /**
   * Class constructor.
   *
   * @see initialize()
   */
  public function __construct(\SessionHandlerInterface $storage, $options = array())
  {
    $this->initialize($storage, $options);

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
   * @param SessionHandlerInterface $storage  Storage for session
   * @param  array $options  An associative array of options
   *
   * @return bool true, if initialization completes successfully, otherwise false
   */
  public function initialize(\SessionHandlerInterface $storage, $options = array())
  {
    $this->storage = $storage;
    $this->options = array_merge(array(
      'auto_shutdown' => true,
      'save_path' => '/tmp'
    ), $options);

    if ($this->requestHasETag())
    {
      $this->storage->open($this->option['save_path'], $this->getETag());
    }
//     header("Cache-Control: private, must-revalidate, proxy-revalidate");
//     header("ETag: kupa");
//     $this->getETag();
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

  protected function requestHasETag()
  {
    return !empty($_SERVER["HTTP_IF_NONE_MATCH"]);
  }

  protected function getETag()
  {
    if ($this->requestHasETag())
    {
      $this->etag = $_SERVER["HTTP_IF_NONE_MATCH"];
    } else {
      $this->etag = uniqid(null, true);
    }
    return $this->etag;
  }
}