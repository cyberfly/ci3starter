<?php

/*
 * This seeder will create records for language usage
 * to run this seeder, use php cli seed
 * */

class LanguageSeeder extends Seeder {

    public function run()
    {
        //empty table

        $this->db->truncate('languages');

        //set data and run insert

        $data = [
            'key' => 'dashboard',
            'language' => 'malay',
            'set' => 'sidebar',
            'text' => 'Utama',
        ];

        $this->db->insert('languages', $data);

        //set data and run insert

        $data = [
            'key' => 'strategic_plan',
            'language' => 'malay',
            'set' => 'sidebar',
            'text' => 'Pelan Strategik',
        ];

        $this->db->insert('languages', $data);

        //set data and run insert

        $data = [
            'key' => 'skt',
            'language' => 'malay',
            'set' => 'sidebar',
            'text' => 'Sasaran Kerja Tahunan',
        ];

        $this->db->insert('languages', $data);

        //insert more language key below
    }

}