<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Registration extends CI_Model {

		public $cols; // all the columns from the user
		public $error;
		public $message;

        public function __construct()
        {
                parent::__construct();
								
        }

		public function count_enrollments($wid, $status_id = 1) {
			$this->db->where('workshop_id', $wid);
			$this->db->where('status_id', $status_id);
			$query = $this->db->get('registrations');
			return $query->num_rows();
		}
}
	