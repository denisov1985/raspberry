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


class DatabaseAdapter
{
    private $connection;

    public function __construct($config)
    {
        $dsn = "mysql:host=$config->host;dbname=$config->dbname;charset=utf8";
        $opt = array(
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
        );
        $this->connection = new \PDO($dsn, $config->user, $config->pass, $opt);
    }
}