<?php
	
class MY_Model extends CI_Model {
	
	public $table_name;
	public $cols;
		
	public $error;
	public $message;

	// for dropdowns
	public $nameCol = 'name';
	public $valueCol = 'id';
	public $dropdown_array = array(0 => '');
	public $fuller_array = array(0 => array());
	
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
		return $this->cols;
	}
	
	// set cols from post, based on field names
	public function update_cols_from_form() {
		$fields = $this->db->list_fields($this->table_name); // field names
		foreach ($fields as $f) {
			if ($this->input->post($f) != null) {
				$this->cols[$f] = $this->input->post($f);
			}
		}
		return true;
	}
	
	// update database table from cols
	public function update_db_from_cols() {
		if (!isset($this->cols['id']) || !$this->cols['id']) { return false; } // gotta have an id
		
		$fields = $this->db->list_fields($this->table_name); // field names
		foreach ($fields as $f) { // make sure we only use things that have db columns
			if (isset($this->cols[$f])) {
				$data[$f] = $this->cols[$f];
			}
		}
		$this->db->where('id', $this->cols['id']);
		$this->db->update($this->table_name, $data);
	}
	
	// for populating forms
	public function set_dropdown_arrays() {
		$this->db->order_by("{$this->valueCol} ASC");
		$query = $this->db->get($this->table_name);
		
		foreach ($query->result_array() as $row) {
			$this->dropdown_array[$row[$this->valueCol]] = $row[$this->nameCol];
			$this->fuller_array[$row[$this->valueCol]] = $row;
		}
		return $this->dropdown_array;
	}	

}

?>
