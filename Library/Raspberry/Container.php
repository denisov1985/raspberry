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


class Container
{
    private $data;

    public function __construct($data)
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
    public function get()
    {
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
}