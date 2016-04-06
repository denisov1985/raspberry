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
        foreach ($fields as $field) {
            $parts[] = sprintf('%s %s%s COLLATE \'utf8_general_ci\' NULL', $field['name'], $field['type'], isset($field['size']) ? '(' . $field['size'] . ')' : '');

        }
        $sql .= implode(', ', $parts) . ')';
        $this->connection->exec($sql);
    }

    public function clearTable($table) {
        $sql = 'TRUNCATE TABLE ' . $table;
        $this->connection->exec($sql);
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
        $this->connection->exec($sql);
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
        }

        $collection = [];
        $result = $this->connection->query($sql, \PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $collection[] = $row;
        }
        return $collection;
    }
}