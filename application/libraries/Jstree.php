<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 */
class Jstree
{

    protected $options = array();
	protected $default = array(
        'agency' => array(
            'structure_table'	=> 'agensi',	    // the structure table (containing the id, left, right, level, parent_id and position fields)
            'data_table'		=> 'agensi',		// table for additional fields (apart from structure ones, can be the same as structure_table)
            'data2structure'	=> 'uid',			        // which field from the data table maps to the structure table
            'structure'			=> array(			        // which field (value) maps to what in the structure (key)
                'id'			=> 'id',
                'uid'           => 'uid',
                'nama'          => 'nama',
                'kod'           => 'kod',
                'left'			=> 'lft',
                'right'			=> 'rgt',
                'level'			=> 'lvl',
                'parent_uid'	=> 'puid',
                'position'		=> 'pos'
            ),
		    'data'				=> array('uid','nama','kod','lft','rgt','lvl','puid','pos')			// array of additional fields from the data table
		),
        'strategic' => array(
            'structure_table'	=> '_pelan_strategik',	    // the structure table (containing the id, left, right, level, parent_id and position fields)
    		'data_table'		=> '_pelan_strategik',		// table for additional fields (apart from structure ones, can be the same as structure_table)
    		'data2structure'	=> 'uid',			               // which field from the data table maps to the structure table
    		'structure'			=> array(			                // which field (value) maps to what in the structure (key)
    			'id'			=> 'id',
                'uid'           => 'uid',
                'nama'          => 'nama',
                'keterangan'    => 'keterangan',
                'tarikh_mula'   => 'tarikh_mula',
                'tarikh_tamat'  => 'tarikh_tamat',
    			'left'			=> 'lft',
    			'right'			=> 'rgt',
    			'level'			=> 'lvl',
    			'parent_uid'	=> 'puid',
    			'position'		=> 'pos'
    		),
            'data'				=> array('uid','nama','keterangan','tarikh_mula','tarikh_tamat','lft','rgt','lvl','puid','pos')			// array of additional fields from the data table
        )
	);

    protected $rslt	= null;


    function __construct()
    {
        $CI =& get_instance();
        $this->db =& $CI->db;

        $this->options = array_merge($this->default, $this->options);
    }

    public function getRootAgency($id = '')
    {
        $sql = "SELECT parent.*
               FROM agensi AS node, agensi AS parent
               WHERE node.lft BETWEEN parent.lft AND parent.rgt AND node.uid = '$id'
               ORDER BY parent.lft";

       return $this->db->query($sql)->row(1);
    }


    ////////////
    // JSTree //
    ////////////


    public function getNode($model, $id, $options = array())
    {

        $node = $this->db->query("
			SELECT
				s.".implode(", s.", $this->options["$model"]['structure'])."
			FROM
				".$this->options["$model"]['structure_table']." s
			WHERE
				s.".$this->options["$model"]['structure']['uid']." = '".$id."'"
		)->row_array();

		if(!$node) {
			throw new Exception('Node does not exist');
		}
		if(isset($options['with_children'])) {
			$node['children'] = $this->getChildren($model, $id, isset($options['deep_children']));
		}
		if(isset($options['with_path'])) {
			$node['path'] = $this->getPath($model, $id);
		}
		return $node;
    }

     public function getChildren($model, $id, $recursive = false)
     {

         if($recursive) {
 			$node = $this->getNode($model, $id);

            $sql = "
                SELECT
                    s.".implode(", s.", $this->options["$model"]['structure'])."
                FROM
                    ".$this->options["$model"]['structure_table']." s
                WHERE
                    s.".$this->options["$model"]['structure']['left']." > ".(int)$node[$this->options["$model"]['structure']['left']]." AND
                    s.".$this->options["$model"]['structure']['right']." < ".(int)$node[$this->options["$model"]['structure']['right']]."
                ORDER BY
                    s.".$this->options["$model"]['structure']['left']."
            ";

 		}
 		else {

            $sql = "
                SELECT
                    s.".implode(", s.", $this->options["$model"]['structure'])."
                FROM
                    ".$this->options["$model"]['structure_table']." s
                WHERE
                    s.".$this->options["$model"]['structure']['parent_uid']." = '".$id."'
                ORDER BY
                    s.".$this->options["$model"]['structure']['position']."
            ";
            // echo $sql;

 		}

        return $this->db->query($sql)->result_array();
     }

     public function removeNode($model, $id)
     {
        // $id = (int)$id;
        if(!$id || $id === 1) { throw new Exception('Could not create inside roots'); }
        $data = $this->getNode($model, $id, array('with_children' => true, 'deep_children' => true));
        $lft = (int)$data[$this->options["$model"]['structure']["left"]];
        $rgt = (int)$data[$this->options["$model"]['structure']["right"]];
        $pid = $data[$this->options["$model"]['structure']["parent_uid"]];
        $pos = (int)$data[$this->options["$model"]['structure']["position"]];
        $dif = $rgt - $lft + 1;

        $sql = array();
        // deleting node and its children from structure
        $sql[] = "
        	DELETE FROM ".$this->options["$model"]['structure_table']."
        	WHERE ".$this->options["$model"]['structure']["left"]." >= ".(int)$lft." AND ".$this->options["$model"]['structure']["right"]." <= ".(int)$rgt."
        ";
        // shift left indexes of nodes right of the node
        $sql[] = "
        	UPDATE ".$this->options["$model"]['structure_table']."
        		SET ".$this->options["$model"]['structure']["left"]." = ".$this->options["$model"]['structure']["left"]." - ".(int)$dif."
        	WHERE ".$this->options["$model"]['structure']["left"]." > ".(int)$rgt."
        ";
        // shift right indexes of nodes right of the node and the node's parents
        $sql[] = "
        	UPDATE ".$this->options["$model"]['structure_table']."
        		SET ".$this->options["$model"]['structure']["right"]." = ".$this->options["$model"]['structure']["right"]." - ".(int)$dif."
        	WHERE ".$this->options["$model"]['structure']["right"]." > ".(int)$lft."
        ";
        // Update position of siblings below the deleted node
        $sql[] = "
        	UPDATE ".$this->options["$model"]['structure_table']."
        		SET ".$this->options["$model"]['structure']["position"]." = ".$this->options["$model"]['structure']["position"]." - 1
        	WHERE ".$this->options["$model"]['structure']["parent_uid"]." = '".$pid."' AND ".$this->options["$model"]['structure']["position"]." > ".(int)$pos."
        ";
        // delete from data table
        // if($this->options['data_table']) {
        // 	$tmp = array();
        // 	$tmp[] = (int)$data['id'];
        // 	if($data['children'] && is_array($data['children'])) {
        // 		foreach($data['children'] as $v) {
        // 			$tmp[] = (int)$v['id'];
        // 		}
        // 	}
        // 	$sql[] = "DELETE FROM ".$this->options['data_table']." WHERE ".$this->options['data2structure']." IN (".implode(',',$tmp).")";
        // }

        // drop strategic plan table
        // $sp_data_table = $data['id'] . '_sp_data';
        $strategic_plan_table = $data['id'] . '_pelan_strategik';

        // load dbforge library
        $CI =& get_instance();
        $CI->load->dbforge();

        // drop sp_struct_table
        $CI->dbforge->drop_table($strategic_plan_table, TRUE);

        foreach($sql as $v) {
        	try {
        		$this->db->query($v);
        	} catch(Exception $e) {
        		$this->reconstruct();
        		throw new Exception('Could not remove');
        	}
        }
        return true;
     }

     /**
      * Get path for current node
      * @param  [type] $model [type of db]
      * @param  [type] $id    [node uid]
      * @return [type]        [array]
      */
     public function getPath($model, $id)
     {
        $node = $this->getNode($model, $id);
		$sql = false;
		if($node) {
			$sql = "
				SELECT
					s.".implode(", s.", $this->options["$model"]['structure']).",
					d.".implode(", d.", $this->options["$model"]['data'])."
				FROM
					".$this->options["$model"]['structure_table']." s,
					".$this->options["$model"]['data_table']." d
				WHERE
					s.".$this->options["$model"]['structure']['uid']." = d.".$this->options["$model"]['data2structure']." AND
					s.".$this->options["$model"]['structure']['left']." <= ".(int)$node[$this->options["$model"]['structure']['left']]." AND
					s.".$this->options["$model"]['structure']['right']." >= ".(int)$node[$this->options["$model"]['structure']['right']]."
				ORDER BY
					s.".$this->options["$model"]['structure']['left']."
			";
		}

		return $sql ? $this->db->query($sql)->result_array() : false;
     }

     public function createNode($model, $pid, $parent, $position = 0, $data = array())
     {
        // $parent = (int)$parent;
		if($parent == 0) { throw new Exception('Parent is 0'); }
		$parent = $this->getNode($model, $parent, array('with_children'=> true));
		if(!$parent['children']) { $position = 0; }
		if($parent['children'] && $position >= count($parent['children'])) { $position = count($parent['children']); }

		$sql = array();
		$par = array();

		// PREPARE NEW PARENT
		// update positions of all next elements
		$sql[] = "
			UPDATE ".$this->options["$model"]['structure_table']."
				SET ".$this->options["$model"]['structure']["position"]." = ".$this->options["$model"]['structure']["position"]." + 1
			WHERE
				".$this->options["$model"]['structure']["parent_uid"]." = '".$parent[$this->options["$model"]['structure']['uid']]."' AND
				".$this->options["$model"]['structure']["position"]." >= ".$position."
			";
		$par[] = false;

		// update left indexes
		$ref_lft = false;
		if(!$parent['children']) {
			$ref_lft = $parent[$this->options["$model"]['structure']["right"]];
		}
		else if(!isset($parent['children'][$position])) {
			$ref_lft = $parent[$this->options["$model"]['structure']["right"]];
		}
		else {
			$ref_lft = $parent['children'][(int)$position][$this->options["$model"]['structure']["left"]];
		}
		$sql[] = "
			UPDATE ".$this->options["$model"]['structure_table']."
				SET ".$this->options["$model"]['structure']["left"]." = ".$this->options["$model"]['structure']["left"]." + 2
			WHERE
				".$this->options["$model"]['structure']["left"]." >= ".(int)$ref_lft."
			";
		$par[] = false;

		// update right indexes
		$ref_rgt = false;
		if(!$parent['children']) {
			$ref_rgt = $parent[$this->options["$model"]['structure']["right"]];
		}
		else if(!isset($parent['children'][$position])) {
			$ref_rgt = $parent[$this->options["$model"]['structure']["right"]];
		}
		else {
			$ref_rgt = $parent['children'][(int)$position][$this->options["$model"]['structure']["left"]] + 1;
		}
		$sql[] = "
			UPDATE ".$this->options["$model"]['structure_table']."
				SET ".$this->options["$model"]['structure']["right"]." = ".$this->options["$model"]['structure']["right"]." + 2
			WHERE
				".$this->options["$model"]['structure']["right"]." >= ".(int)$ref_rgt."
			";
		$par[] = false;

		// INSERT NEW NODE IN STRUCTURE
		$sql[] = "INSERT INTO ".$this->options["$model"]['structure_table']." (".implode(",", $this->options["$model"]['structure']).") VALUES (?".str_repeat(',?', count($this->options["$model"]['structure']) - 1).")";
		$tmp = array();
		foreach($this->options["$model"]['structure'] as $k => $v) {

            if($model == "agency") {

                switch($k) {
    				case 'id':
    					$tmp[] = null;
    					break;
                    case 'uid':
                        $tmp[] = uniqid('', true);
                        break;
                    case 'nama':
                        $tmp[] = $data['nama'];
                        break;
                    case 'kod':
                        $tmp[] = $data['kod'];
                        break;
    				case 'left':
    					$tmp[] = (int)$ref_lft;
    					break;
    				case 'right':
    					$tmp[] = (int)$ref_lft + 1;
    					break;
    				case 'level':
    					$tmp[] = (int)$parent[$v] + 1;
    					break;
    				case 'parent_uid':
    					$tmp[] = $parent[$this->options["$model"]['structure']['uid']];
    					break;
    				case 'position':
    					$tmp[] = $position;
    					break;
    				default:
    					$tmp[] = null;
    			}

            } // TODO: strategic table structure

		}
		$par[] = $tmp;

        $insertId = '';
		foreach($sql as $k => $v) {
			try {
				$this->db->query($v, $par[$k]);
                $insertId = $this->db->insert_id();

			} catch(Exception $e) {
				$this->reconstruct();
				throw new Exception('Could not create');
			}
		}
		if($data && count($data)) {

            if((int)$pid == 0) {

                // CREATE NEW STRATEGIC PLAN TABLE FOR NEWLY CREATED AGENCY
                // MHAFIZ
                // 13-07-2017

                // load forge class
                $this->load->dbforge();

                // Set default engine
                $attr = array(
                    'ENGINE' => 'InnoDB'
                );

                // Strategic Plan table initial with id
                $new_strategic_plan_table = $insertId . '_pelan_strategik';

                // Create fields
                // TODO: new fields stucture combined with programs
                $strategic_plan_fields = array(
                    'id' => array(
                            'type' => 'INT',
                            'auto_increment' => TRUE,
                            'unsigned' => TRUE,
                            'unique' => TRUE
                    ),
                    'uid' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '23',
                        'unique' => TRUE,
                    ),
                    'nama' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '255'
                    ),
                    'keterangan' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '255',
                        'null' => TRUE
                    ),
                    'tarikh_mula' => array(
                        'type' => 'DATE',
                        'null' => TRUE
                    ),
                    'tarikh_tamat' => array(
                        'type' => 'DATE',
                        'null' => TRUE
                    ),
                    'lft' => array(
                        'type' => 'INT'
                    ),
                    'rgt' => array(
                        'type' => 'INT'
                    ),
                    'lvl' => array(
                        'type' => 'INT'
                    ),
                    'puid' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '23'
                    ),
                    'pos' => array(
                        'type' => 'INT'
                    )
                );
                // Exec
                $this->dbforge->add_field($strategic_plan_fields)->add_key('id', TRUE)->create_table($new_strategic_plan_table, TRUE, $attr);


                // Insert default value into db
                $data = array(
                    'uid' => uniqid('', TRUE),
                    'nama' => 'ROOT',
                    'keterangan' => 'ROOT',
                    'lft' => 1,
                    'rgt' => 2,
                    'lvl' => 0,
                    'puid' => 0,
                    'pos' => 0
                );
                $this->db->insert($new_strategic_plan_table, $data);

            }

            $node = $insertId;
			// if(!$this->renameNode($node,$data)) {
			// 	$this->removeNode($node);
			// 	throw new Exception('Could not rename after create');
			// }
		}

		return $node;
     }

     public function moveNode($model, $id, $parent, $position = 0)
     {
        // $id			= (int)$id;
		// $parent		= (int)$parent;
		if($parent == 0 || $id == 0 || $id == 1) {
			throw new Exception('Cannot move inside 0, or move root node');
		}

		$parent		= $this->getNode($model, $parent, array('with_children'=> true, 'with_path' => true));
		$id			= $this->getNode($model, $id, array('with_children'=> true, 'deep_children' => true, 'with_path' => true));
		if(!$parent['children']) {
			$position = 0;
		}
		if($id[$this->options["$model"]['structure']['parent_uid']] == $parent[$this->options["$model"]['structure']['uid']] && $position > $id[$this->options["$model"]['structure']['position']]) {
			$position ++;
		}
		if($parent['children'] && $position >= count($parent['children'])) {
			$position = count($parent['children']);
		}
		if($id[$this->options["$model"]['structure']['left']] < $parent[$this->options["$model"]['structure']['left']] && $id[$this->options["$model"]['structure']['right']] > $parent[$this->options["$model"]['structure']['right']]) {
			throw new Exception('Could not move parent inside child');
		}

		$tmp = array();
		// $tmp[] = (int)$id[$this->options['structure']["uid"]];
        $tmp[] = "'".$id[$this->options["$model"]['structure']["uid"]]."'";
		if($id['children'] && is_array($id['children'])) {
			foreach($id['children'] as $c) {
				// $tmp[] = (int)$c[$this->options['structure']["uid"]];
                $tmp[] = "'".$c[$this->options["$model"]['structure']["uid"]]."'";
			}
		}
		$width = (int)$id[$this->options["$model"]['structure']["right"]] - (int)$id[$this->options["$model"]['structure']["left"]] + 1;

		$sql = array();

		// PREPARE NEW PARENT
		// update positions of all next elements
		$sql[] = "
			UPDATE ".$this->options["$model"]['structure_table']."
				SET ".$this->options["$model"]['structure']["position"]." = ".$this->options["$model"]['structure']["position"]." + 1
			WHERE
				".$this->options["$model"]['structure']["uid"]." != '".$id[$this->options["$model"]['structure']['uid']]."' AND
				".$this->options["$model"]['structure']["parent_uid"]." = '".$parent[$this->options["$model"]['structure']['uid']]."' AND
				".$this->options["$model"]['structure']["position"]." >= ".$position."
			";

		// update left indexes
		$ref_lft = false;
		if(!$parent['children']) {
			$ref_lft = $parent[$this->options["$model"]['structure']["right"]];
		}
		else if(!isset($parent['children'][$position])) {
			$ref_lft = $parent[$this->options["$model"]['structure']["right"]];
		}
		else {
			$ref_lft = $parent['children'][(int)$position][$this->options["$model"]['structure']["left"]];
		}
		$sql[] = "
			UPDATE ".$this->options["$model"]['structure_table']."
				SET ".$this->options["$model"]['structure']["left"]." = ".$this->options["$model"]['structure']["left"]." + ".$width."
			WHERE
				".$this->options["$model"]['structure']["left"]." >= ".(int)$ref_lft." AND
				".$this->options["$model"]['structure']["uid"]." NOT IN(".implode(',',$tmp).")
			";
		// update right indexes
		$ref_rgt = false;
		if(!$parent['children']) {
			$ref_rgt = $parent[$this->options["$model"]['structure']["right"]];
		}
		else if(!isset($parent['children'][$position])) {
			$ref_rgt = $parent[$this->options["$model"]['structure']["right"]];
		}
		else {
			$ref_rgt = $parent['children'][(int)$position][$this->options["$model"]['structure']["left"]] + 1;
		}
		$sql[] = "
			UPDATE ".$this->options["$model"]['structure_table']."
				SET ".$this->options["$model"]['structure']["right"]." = ".$this->options["$model"]['structure']["right"]." + ".$width."
			WHERE
				".$this->options["$model"]['structure']["right"]." >= ".(int)$ref_rgt." AND
				".$this->options["$model"]['structure']["uid"]." NOT IN(".implode(',',$tmp).")
			";

		// MOVE THE ELEMENT AND CHILDREN
		// left, right and level
		$diff = $ref_lft - (int)$id[$this->options["$model"]['structure']["left"]];

		if($diff > 0) { $diff = $diff - $width; }
		$ldiff = ((int)$parent[$this->options["$model"]['structure']['level']] + 1) - (int)$id[$this->options["$model"]['structure']['level']];
		$sql[] = "
			UPDATE ".$this->options["$model"]['structure_table']."
				SET ".$this->options["$model"]['structure']["right"]." = ".$this->options["$model"]['structure']["right"]." + ".$diff.",
					".$this->options["$model"]['structure']["left"]." = ".$this->options["$model"]['structure']["left"]." + ".$diff.",
					".$this->options["$model"]['structure']["level"]." = ".$this->options["$model"]['structure']["level"]." + ".$ldiff."
				WHERE ".$this->options["$model"]['structure']["uid"]." IN(".implode(',',$tmp).")
		";
		// position and parent_id
		$sql[] = "
			UPDATE ".$this->options["$model"]['structure_table']."
				SET ".$this->options["$model"]['structure']["position"]." = ".$position.",
					".$this->options["$model"]['structure']["parent_uid"]." = '".$parent[$this->options["$model"]['structure']["uid"]]."'
				WHERE ".$this->options["$model"]['structure']["uid"]."  = '".$id[$this->options["$model"]['structure']['uid']]."'
		";

		// CLEAN OLD PARENT
		// position of all next elements
		$sql[] = "
			UPDATE ".$this->options["$model"]['structure_table']."
				SET ".$this->options["$model"]['structure']["position"]." = ".$this->options["$model"]['structure']["position"]." - 1
			WHERE
				".$this->options["$model"]['structure']["parent_uid"]." = '".$id[$this->options["$model"]['structure']["parent_uid"]]."' AND
				".$this->options["$model"]['structure']["position"]." > ".(int)$id[$this->options["$model"]['structure']["position"]];
		// left indexes
		$sql[] = "
			UPDATE ".$this->options["$model"]['structure_table']."
				SET ".$this->options["$model"]['structure']["left"]." = ".$this->options["$model"]['structure']["left"]." - ".$width."
			WHERE
				".$this->options["$model"]['structure']["left"]." > ".(int)$id[$this->options["$model"]['structure']["right"]]." AND
				".$this->options["$model"]['structure']["uid"]." NOT IN(".implode(',',$tmp).")
		";
		// right indexes
		$sql[] = "
			UPDATE ".$this->options["$model"]['structure_table']."
				SET ".$this->options["$model"]['structure']["right"]." = ".$this->options["$model"]['structure']["right"]." - ".$width."
			WHERE
				".$this->options["$model"]['structure']["right"]." > ".(int)$id[$this->options["$model"]['structure']["right"]]." AND
				".$this->options["$model"]['structure']["uid"]." NOT IN(".implode(',',$tmp).")
		";

		foreach($sql as $k => $v) {
			//echo preg_replace('@[\s\t]+@',' ',$v) ."\n";
            // echo $v . '<br>';
			try {
				$this->db->query($v);
			} catch(Exception $e) {
				$this->reconstruct();
				throw new Exception('Error moving');
			}
		}
		return true;
     }


}
