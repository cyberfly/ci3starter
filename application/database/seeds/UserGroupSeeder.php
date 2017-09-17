<?php

/*
 * This seeder will create records for groups usage
 * to run this seeder, use php cli seed
 * */

class UserGroupSeeder extends Seeder {

    public function run()
    {
        //empty table

        $this->db->truncate('groups');

        //set data and run insert

        $data = [
            'name' => 'admin',
            'description' => 'Super Administrator',
        ];

        $this->db->insert('groups', $data);

        //set data and run insert

        $data = [
            'name' => 'members',
            'description' => 'General User',
        ];

        $this->db->insert('groups', $data);

        //set data and run insert

        $data = [
            'name' => 'general_admin',
            'description' => 'General Admin',
        ];

        $this->db->insert('groups', $data);

        //set data and run insert

        $data = [
            'name' => 'agency_admin',
            'description' => 'Agency Admin',
        ];

        $this->db->insert('groups', $data);

        //set data and run insert

        $data = [
            'name' => 'data_champion',
            'description' => 'Data Champion',
        ];

        $this->db->insert('groups', $data);

        //insert more data below
    }

}