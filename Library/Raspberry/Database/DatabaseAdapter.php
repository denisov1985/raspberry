<?php
/**
 *
 *
 * @package
 * @subpackage
 * @author     Dmytro Denysov dmytro.denysov@racingpost.com
 * @copyright  2016 Racing Post
 */

namespace Raspberry\Database;


use Raspberry\Debug;
use Raspberry\Model;

class DatabaseAdapter
{
    private $connection;

    public function __construct($config)
    {
        $dsn = "mysql:host=$config->host";
        $opt = array(
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
        );
        $this->connection = new \PDO($dsn, $config->user, $config->pass, $opt);

        $result = $this->connection->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$config->dbname'");
        if ($result->rowCount() == 0) {
            $this->connection->exec("CREATE DATABASE $config->dbname");
        }
        $dsn = "mysql:host=$config->host;dbname=$config->dbname;charset=utf8";
        $this->connection = new \PDO($dsn, $config->user, $config->pass, $opt);
    }

    public function createTableIfNotExist($tableName, $fields = [])
    {
        $sql = "CREATE TABLE IF NOT EXISTS $tableName (id int NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        $parts = [];
        foreach ($fields as $fieldName => $field) {
            $parts[] = sprintf('%s %s%s COLLATE \'utf8_general_ci\' NULL', $fieldName, $field['type'], isset($field['size']) ? '(' . $field['size'] . ')' : '');

        }
        $sql .= implode(', ', $parts) . ')';
        $this->query($sql);
    }

    public function clearTable($table) {
        $sql = 'TRUNCATE TABLE ' . $table;
        $this->query($sql);
    }

    public function insert($table, $data = [])
    {

        $default = ['id' => 'NULL'];
        $data = array_merge($default, $data);

        $sql = 'INSERT INTO ' . $table . ' ';
        $fields = $values = [];
        foreach ($data as $key => $value) {
            $fields[] = $key;
            $value = str_ireplace("'", "''", $value);
            if ($value != 'NULL') {
                $values[] = "'$value'";
            }   else {
                $values[] = $value;
            }
        }

        $sql .= sprintf('(%s) VALUES (%s)', implode(', ', $fields), implode(', ', $values));
        $this->query($sql);
    }

    public function update($table, $data, $where)
    {
        $sql = "UPDATE $table SET ";
        $values = [];
        foreach ($data as $key => $value) {
            $fields[] = $key;
            $value = str_ireplace("'", "''", $value);
            $values[] = "$key = '$value'";
        }
        $sql .= implode(', ', $values);
        $sql .= ' WHERE ';
        $data = [];
        foreach ($where as $key => $value) {
            $data[] = "$key = '$value'";
        }
        $sql .= implode(' AND ', $data);
        $this->query($sql);
    }

    public function select($table, $where = [])
    {
        $sql = "SELECT * FROM $table";
        if (!empty($where)) {
            $sql .= ' WHERE ';
            $data = [];
            foreach ($where as $key => $value) {
                $data[] = "$key = '$value'";
            }
            $sql .= implode(' AND ', $data);
            $this->query($sql);
        }

        $collection = [];
        $result = $this->query($sql, \PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $collection[] = new \AppModel($row);
        }
        return $collection;
    }

    public function query($sql, $type = \PDO::FETCH_ASSOC)
    {
        try {
            return $this->connection->query($sql, $type);
        }  catch (\Exception $e) {
            throw new \Exception($e->getMessage() . ' ' . PHP_EOL . $sql);
        }
    }
}