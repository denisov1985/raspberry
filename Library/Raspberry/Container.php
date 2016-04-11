<?php
/**
 *
 *
 * @package
 * @subpackage
 * @author     Dmytro Denysov dmytro.denysov@racingpost.com
 * @copyright  2016 Racing Post
 */

namespace Raspberry;


class Container implements \Iterator, \ArrayAccess
{
    private $data;

    public function __construct($data = [])
    {
        $result = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $result[$key] = new self($value);
            }   else  {
                $result[$key] = $value;
            }
        }
        $this->data = $result;
    }


    /**
     * @return mixed
     */
    public function get($key = null, $default = '')
    {
        if (isset($key)) {
            if (isset($this->data[$key])) {
                return $this->data[$key];
            }   else  {
                return $default;
            }
        }
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function set($data)
    {
        $this->data = $data;
    }

    /**
     * Get config value
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    function __get($name)
    {
        if (!isset($this->data[$name])) {
            debug_backtrace();
            throw new \Exception("Value '$name' not found");
        }
        return $this->data[$name];
    }

    /**
     * Set config value
     * @param $name
     * @param $value
     */
    function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function current()
    {
        return current($this->data);
    }

    public function next()
    {
        next($this->data);
    }

    public function key()
    {
        return key($this->data);
    }

    public function valid()
    {
        return key($this->data) !== null;
    }

    public function rewind()
    {
        reset($this->data);
    }

    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }


}