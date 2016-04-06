<?php

use Raspberry\Cmd\Command;
use Parser\Parser;
use Parser\Document;

class VelokievCommand extends Command
{

    private $db;

    public function run()
    {
        $this->prepare();
        $parser = new Parser();

        $link = 'http://velokyiv.com/forum/cycleplan.php?cp_forum_id=1';
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

            $link = $row->find('td a', 0)->href;
            $data = Document::parseUrl($link);
            $topicId = $data['cp_id'];

            $members = trim($row->find('td', 3)->innertext);
            $org = trim(strip_tags($row->find('td', 4)->innertext));

            try {

                $result = $this->db->select('topics', ['trip_id' => $topicId]);

                if (count($result) > 0) {
                    continue;
                }

                $data = [
                    'trip_name' => $title,
                    'user_forum_id' => 0,
                    'created_date' => sprintf('%s-%s-%s', date('Y'), date('m'), $day),
                    'user_name' => $org,
                    'members' => $members,
                    'is_shared' => 0,
                    'trip_id' => $topicId
                ];
                $this->db->insert('topics', $data);
            }   catch (Exception $e) {
                print_r($data);
                die();
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
        ], [
            'name' => 'is_shared',
            'type' => 'int'
        ], [
            'name' => 'trip_id',
            'type' => 'int'
        ]

        ]);

        //$this->db->clearTable('topics');
    }

}