<?php
/**
 *
 *
 * @package
 * @subpackage
 * @author     Dmytro Denysov dmytro.denysov@racingpost.com
 * @copyright  2016 Racing Post
 */

$opt = array(
    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
);
$dsn = "mysql:host=localhost;dbname=test;charset=utf8";
$connection = new \PDO($dsn, 'root', '', $opt);

$sql = "SELECT * FROM emp";
$result = $connection->query($sql);
foreach ($result as $row) {
    $collection[] = $row;
}
//print_r($collection);

function format($collection, $id) {
    $data = [];
    foreach ($collection as $item) {
        if ($item['parent_id'] == $id) {
            $data[] = $item;
        }
    }
    foreach ($data as $key => $item) {
        $data[$key]['sub'] = format($collection, $item['id']);
    }
    return $data;
}

$a = format($collection, 0);

print_r($a);