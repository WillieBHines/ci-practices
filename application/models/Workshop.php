<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Workshop extends MY_Model {

		public $table_name = 'workshops';
		public $registrations = array();

        public function __construct()
        {
                parent::__construct();
								
        }
		
		public function set_data($id) {
			$this->set_cols_with_id($id);
			$this->cols = $this->prep_workshop_data($this->cols);
			$this->cols = $this->format_workshop_startend($this->cols);
			return $this->cols;
			
		}
		
		
		public function set_registrations() {			
			if (!isset($this->cols['id']) || !$this->cols['id']) {
				$this->error = 'No workshop set.';
				return false;
			}
			
			$this->db->select('registrations.*, users.email, statuses.status_name');
			$this->db->join('users', 'registrations.user_id = users.id');
			$this->db->join('statuses', 'registrations.status_id = statuses.id');
			$this->db->where('workshop_id', $this->cols['id']);
			$this->db->order_by('status_id asc, last_modified');
			$query = $this->db->get('registrations');
			foreach ($query->result_array() as $row) {
				$this->registrations[] = $row;
			}
			return $this->registrations;
		}
		
		public function prep_workshop_data($row) {
			
			$this->load->model('registration');
			$this->load->model('status');
			
			if ($row['when_public'] == 0 ) {
				$row['when_public'] = '';
			}
			$row = $this->format_workshop_startend($row);
			
			// count enrollments for each status type
			foreach ($this->status->status_names as $sn => $sid) {
				$row[$sn] = $this->registration->count_enrollments($row['id'], $sid);
			}		
			
			$row['open'] = ($row['enrolled'] >= $row['capacity'] ? 0 : $row['capacity'] - $row['enrolled']);
			if (strtotime($row['start']) < strtotime('now')) { 
				$row['type'] = 'past'; 
			} elseif ($row['enrolled']+$row['invited']+$row['waiting'] >= $row['capacity']  ) { 
				$row['type'] = 'soldout'; 
			} else {
				$row['type'] = 'open';
			}
			return $row;
			//$row = wbh_check_last_minuteness($row);
			
		}
		
		function friendly_time($time_string) {
			$ts = strtotime($time_string);
			$minutes = date('i', $ts);
			if ($minutes == 0) {
				return date('ga', $ts);
			} else {
				return date('g:ia', $ts);
			}
		}

		function friendly_date($time_string) {
			$ts = strtotime($time_string);
			$now_doy = date('z'); // day of year
			$wk_doy = date('z', $ts); // workshop day of year
	
			if ($wk_doy - $now_doy < 7) {
				return date('l', $ts); // Monday, Tuesday, Wednesday
			} elseif (date('Y', $ts) != date('Y')) {  
				return date('D M j, Y', $ts);
			} else {
				return date('D M j', $ts);
			}
		}	
	
	

		// pass in the workshop row as it comes from the database table
		// add some columns with date / time stuff figured out
		function format_workshop_startend($row) {	
			if (date('Y', strtotime($row['start'])) != date('Y')) {
				$row['showstart'] = date('D M j, Y - g:ia', strtotime($row['start']));
			} else {
				$row['showstart'] = date('D M j - g:ia', strtotime($row['start']));
			}
			$row['showend'] = $this->friendly_time($row['end']);
			$row['friendly_when'] = $this->friendly_date($row['start']).' '.$this->friendly_time($row['start']);
			$row['showtitle'] = "{$row['title']} - {$row['showstart']}-{$row['showend']}";
			$row['when'] = "{$row['showstart']}-{$row['showend']}";
	
			return $row;
		}	
		
		
}