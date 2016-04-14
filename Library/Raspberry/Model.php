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


class Model extends Container
{
    protected $scaffold;
    protected static $di;
    protected static $db;

    public function __construct($data = [])
    {
        $defaultData = ['id' => null];
        if (!isset($data['id'])) {
            $data = array_merge($defaultData, $data);
        }
        parent::__construct($data);
    }

    public function __call($name, $args = [])
    {
        $prefix = substr($name, 0, 3);
        if ($prefix == 'set') {
            $field = str_ireplace($prefix, '', $name);
            $field = self::camelCaseToUnderscore($field);
            $this[$field] = $args[0];
        }

        if ($prefix == 'get') {
            $field = str_ireplace($prefix, '', $name);
            $field = self::camelCaseToUnderscore($field);
            return $this[$field];
        }
    }

    /**
     * @return mixed
     */
    public function getScaffold()
    {
        return $this->scaffold;
    }

    /**
     * @param mixed $scaffold
     */
    public function setScaffold($scaffold)
    {
        $this->scaffold = $scaffold;
    }

    public function getData()
    {
        $data = [];

        foreach ($this->scaffold as $fieldName => $value) {
            $data[$fieldName] = $this->get($fieldName);
        }

        return $data;
    }

    /**
     * @return mixed
     */
    public static function getDi()
    {
        return self::$di;
    }

    /**
     * @param mixed $di
     */
    public static function setDi(DependencyInjection $di)
    {
        self::$di = $di;
        self::$db = $di->get('application.database');
    }

    public static function getTableName()
    {
        $input = str_ireplace('model', '', get_called_class());
        return self::camelCaseToUnderscore($input);
    }

    private static function camelCaseToUnderscore($input) {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('_', $ret);
    }

    public static function find($id)
    {
        $result = self::findBy(['id' => $id]);
        if (isset($result[0])) {
            return $result[0];
        }   else  {
            return false;
        }
    }

    public static function findBy($where, $order = [])
    {
        $result = self::$db->select(self::getTableName(), $where, $order);
        $data = [];
        foreach ($result as $row) {
            $data[] = new static($row);
        }
        return $data;
    }

    public static function findAll($order = [])
    {
        return self::findBy([], $order);
    }

    public function save()
    {
        if ($this->getId() == null) {
            self::$db->insert($this->getTableName(), $this->get());
        }   else  {
            self::$db->update($this->getTableName(), $this->get(), ['id' => $this->getId()]);
        }
    }
}