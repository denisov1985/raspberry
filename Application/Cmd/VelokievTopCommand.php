<?php

use Raspberry\Cmd\Command;
use Parser\Parser;

class VelokievCommand extends Command
{

    private $db;

    public function run()
    {
        $this->prepare();
        $parser = new Parser();

        for ($year = 2015; $year < 2016; $year++) {
            echo PHP_EOL;
            for ($m = 1; $m <= 12; $m++) {
                echo '.';
                if ($m < 10) {
                    $m = '0' . $m;
                }

                $link = 'http://velokyiv.com/forum/cycleplan.php?cp_date=' . $year . '-' . $m . '&cp_forum_id=1';
                $document = $parser->getDocument($link);

                echo $link . PHP_EOL;

                $rows = $document->get('table.cp_table tr');
                foreach ($rows as $row) {
                    if ($row->find('td', 0) == NULL) {
                        continue;
                    }

                    $day   = trim($row->find('td', 0)->innertext);
                    $time  = trim($row->find('td', 1)->innertext);
                    $title = trim(strip_tags($row->find('td', 2)->innertext));
                    $members = trim($row->find('td', 3)->innertext);
                    $org = trim(strip_tags($row->find('td', 4)->innertext));

                    try {
                        $data = [
                            'trip_name' => $title,
                            'user_forum_id' => 0,
                            'created_date' => sprintf('%s-%s-%s', '2016', $m, $day),
                            'user_name' => $org,
                            'members' => $members
                        ];
                        $this->db->insert('topics', $data);
                    }   catch (Exception $e) {
                        print_r($data);
                        die();
                    }

                }
            }
        }

    }

    private function prepare()
    {
        /** @var Raspberry\Database\DatabaseAdapter $db */
        $this->db = $this->getDi()->get('application.database');

        $this->db->createTableIfNotExist('topics', [[
            'name' => 'trip_name',
            'type' => 'varchar',
            'size' => 255
        ], [
            'name' => 'user_forum_id',
            'type' => 'varchar',
            'size' => 255
        ], [
            'name' => 'created_date',
            'type' => 'date'
        ], [
            'name' => 'user_name',
            'type' => 'varchar',
            'size' => 255
        ], [
            'name' => 'members',
            'type' => 'int'
        ],

        ]);

        $this->db->clearTable('topics');
    }

}