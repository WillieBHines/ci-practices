<?php
	
class MY_Model extends CI_Model {
	
	public $table_name;
	public $cols;
	public $error;
	public $message;
	
	function __construct() {
		parent::__construct();
	}
	
	public function set_cols_with_id($id) {
		if (!$id) {
			$this->error = "No ID selected."; 
			return false;
		}
		if (!$this->table_name) {
			$this->error = 'No table selected.';
			return false;
		}
		
		$this->db->where('id', $id);
		$query = $this->db->get($this->table_name);
		foreach ($query->result_array() as $row) {
			$this->cols = $row;
		}
		return true;
	}

}

?>
