<?php
/**
 * Migration: Create_Language_Table
 *
 * Created by: Cli for CodeIgniter <https://github.com/kenjis/codeigniter-cli>
 * Created on: 2017/08/29 03:02:00
 */
class Migration_Create_Language_Table extends CI_Migration {

	public function up()
	{
        // Creating a table
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE,
				'primary' => TRUE
			),
			'key' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
			),
			'language' => array(
				'type' =>'VARCHAR',
				'constraint' => '100',
				'default' => 'malay',
			),
            'set' => array(
				'type' =>'VARCHAR',
				'constraint' => '100',
                 'null' => TRUE,
			),
			'text' => array(
				'type' => 'TEXT',
			),
		));

        $this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('languages');
	}

	public function down()
	{
        // Dropping a table
		$this->dbforge->drop_table('languages');
	}

}
