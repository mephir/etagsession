<?php

namespace Mephir\ETagSession;

/**
 * ETagSession allows you to use session with id is stored in ETag header instead of cookie/url
 *
 * @package    etagtrackingpixel
 * @subpackage session
 * @author     Pawel Wilk <pwilkmielno@gmail.com>
 */
class ETagSession implements \ArrayAccess
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
   *  * save_path: Path to directory in which session will be saved
   *
   * @param  SessionHandlerInterface $storage  Storage for session
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

    $this->storage->open($this->options['save_path'], $this->getETag());
    if ($this->requestHasETag())
    {
      $this->data = unserialize($this->storage->read($this->etag));
    }
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

  public function has($key)
  {
    return isset($this->data[$key]);
  }

  /**
   * Get data.
   *
   * The preferred format for a key is directory style so naming conflicts can be avoided.
   *
   * @param  string $key  A unique key identifying your data
   *
   * @return mixed Data associated with the key
   */
  public function get($key)
  {
    if (isset($this->data[$key]))
    {
      return $this->data[$key];
    }
    trigger_error(sprintf('Notice: Undefined index: %s', $key));
  }

  /**
   * Regenerates id that represents this storage. Equivalent of session_regenerate_id
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
   * Removes data
   *
   * The preferred format for a key is directory style so naming conflicts can be avoided.
   *
   * @param  string $key  A unique key identifying your data
   */
  public function remove($key)
  {
    if (isset($this->data[$key]))
    {
      unset($this->data[$key]);
    }
  }

  /**
   * Executes the shutdown procedure.
   */
  public function shutdown()
  {
    $this->storage->write($this->etag, serialize($this->data));
    $this->storage->close();
  }

  /**
   * Set data
   *
   * The preferred format for a key is directory style so naming conflicts can be avoided.
   *
   * @param  string $key   A unique key identifying your data
   * @param  mixed  $data  Data associated with your key
   */
  public function set($key, $data)
  {
    $this->data[$key] = $data;
  }

  /**
   * Checking if request have ETag header
   *
   * @return boolean
   */
  protected function requestHasETag()
  {
    return !empty($_SERVER['HTTP_IF_NONE_MATCH']);
  }

  /**
   * Generate new ETag or grab it from headers
   *
   * @param bool $new  Force render new ETag
   */
  protected function getETag($new = false)
  {
    if ($this->requestHasETag() && !$new)
    {
      $this->etag = str_replace('.', '', str_replace('/', '', str_replace('\\', '', $_SERVER['HTTP_IF_NONE_MATCH'])));
    } else {
      $this->etag = str_replace('.', '', uniqid(null, true));
    }
    return $this->etag;
  }

  /**
   * Generates header which needs to be sent to user
   *
   * @return array
   */
  public function getHeaders()
  {
    return array(
      'Cache-Control: private, must-revalidate, proxy-revalidate',
      'ETag: ' . $this->etag,
    );
  }

  /**
   * Whether a offset exists
   * @see http://www.php.net/manual/en/arrayaccess.offsetexists.php
   */
  public function offsetExists($offset)
  {
    return $this->has($offset);
  }

  /**
   * Returns the value at specified offset
   * @see http://www.php.net/manual/en/arrayaccess.offsetget.php
   */
  public function offsetGet($offset)
  {
    return $this->get($offset);
  }

  /**
   * Assigns a value to the specified offset
   * @see http://www.php.net/manual/en/arrayaccess.offsetset.php
   */
  public function offsetSet ($offset, $value)
  {
    $this->set($offset, $value);
  }

  /**
   * Unsets an offset.
   * @see http://www.php.net/manual/en/arrayaccess.offsetunset.php
   */
  public function offsetUnset($offset)
  {
    $this->remove($offset);
  }
}