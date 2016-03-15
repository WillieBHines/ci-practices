<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Workshop extends MY_Model {

		public $table_name = 'workshops';
		public $registrations = array();
		public $changes = array();

        public function __construct()
        {
                parent::__construct();
								
        }
		
		public function set_data($id, $user = null) {
			$this->set_cols_with_id($id);
			
			$this->db->where('id', $this->cols['location_id']);
			$query = $this->db->get('locations');
			foreach ($query->result_array() as $row) {
				$this->cols['place'] = $row['place'];
				$this->cols['lwhere'] = $row['lwhere'];
			}
			$this->cols = $this->prep_workshop_data($this->cols, $user);
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
		
		public function get_changes() {
			if (!$this->cols['id']) {
				$this->error = 'No workshop set.';
				return false;
			}
			$this->load->model('registration');
			$this->changes = $this->registration->get_changes($this->cols['id'], false);
			return $this->changes;
		}		
		
				
		public function prep_workshop_data($row, $user = null) {
			
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
			
			// if we're given user, create call to action
			$row = $this->set_user_action($row, $user);		
			
			return $row;
			//$row = wbh_check_last_minuteness($row);
			
		}
		
		// $row is workshop in question, $user is logged in user
		private function set_user_action($row, $user = null) {
			
			
			if (!$user || !$user->logged_in()) {
				return '<em>not logged in </em>';
			}
			if (!$user->workshops) {
				$this->user->load_workshops();
			}
			
			$action = '';
			
			$enroll_button = "<a class='btn btn-primary' href='".base_url('/registrations/enroll/'.$row['id'].'/'.$user->cols['id'])."'>";
			if ($row['type'] == 'soldout') {
				$enroll_button .= 'Join the Wait List';
			} else {
				$enroll_button .= 'Enroll';
			}
			$enroll_button .= "</a>";
				
			$in_it = false;
			foreach ($user->workshops as $wk) {
				if ($wk['id'] == $row['id']) { // registration
					$row['status_name'] = $wk['status_name'];
					$in_it = true;
					if ($wk['status_name'] == 'enrolled') {

						$action = "Enrolled. Want to <a class='btn btn-danger' href='".base_url('/registrations/drop/'.$wk['registration_id'])."'>drop?</a>";
					
					} elseif  ($wk['status_name'] == 'waiting') {
					
						$action = "Wait list (spot {$wk['rank']}). Want to <a class='btn btn-danger' href='".base_url('/registrations/drop/'.$wk['registration_id'])."'>drop?</a>";
					
					} elseif ($wk['status_name'] == 'invited') {
						$action = "You have an invitaion to join. <a class='btn btn-primary' href='".base_url('/registrations/accept/'.$wk['registration_id'])."'>Accept</a> or <a class='btn btn-primary' href='".base_url('/registrations/decline/'.$wk['registration_id'])."'>decline?</a> ";

					} elseif ($wk['status_name'] == 'dropped') {
						$action = "You've dropped. Want to $enroll_button ?";
					}
				}
			}
			// if you're not in it, you could enroll
			if (!$in_it) {
				$action = "Not enrolled. Want to $enroll_button";
			}
			$row['action'] = $action;
			return $row;
			
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
			if (date('Y', $ts) != date('Y')) {  
				return date('D M j, Y', $ts);
			} else {
				return date('D M j', $ts);
			}
		}	
	
		public function friendly_datetime($time_string) {
			return $this->friendly_date.' '.$this->friendly_time;
		}
	

		// pass in the workshop row as it comes from the database table
		// add some columns with date / time stuff figured out
		public function format_workshop_startend($row) {	
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