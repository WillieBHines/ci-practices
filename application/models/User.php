<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class User extends CI_Model {
	

		public $cols; // all the columns from the user
		public $error;
		public $message;

        public function __construct()
        {
                parent::__construct();
								
				// check session and cookie for key
				if ($this->session->userdata('ukey')) {
					$this->set_user_with_key($this->session->userdata('ukey'));
				} elseif ($this->input->cookie('ckey')) {
					$this->set_user_with_key($this->input->cookie('ckey'));
				}
        }
		
		public function logout() {
		
			$this->cols = array();
			$this->session->unset_userdata('ukey');
			delete_cookie('ckey');
			
		}
		
		public function set_user_with_key($key) {
			// get the user for this key
			$query = $this->db->where('ukey', $key);
			$query = $this->db->get('users');		
			if ($query->num_rows() == 0) {
				$this->error = "I couldn't find a user with that key.";
				return false;
			} else {
				foreach ($query->result_array() as $row) {
					$this->cols = $row;
				}	
			}
			$this->remember_key($this->cols['ukey']);
			
			// return key	
			return $this->cols['ukey'];
			
		}
		
		public function set_user_with_email($email, $force_reset = false) {
						
			
			$this->logout();
						
			// check to see if this user exists
			$query = $this->db->where('email', $email);
			$query = $this->db->get('users');		
				
			//$query = $this->db->get_where('users', array('email', $email));
			if ($query->num_rows() == 0) {

				// if not make one
				$key = $this->generate_key();
				$this->db->insert('users', array('email' => $email, 'ukey' => $key));

				// now get THAT new row
				$query = $this->db->where('email', $email);
				$query = $this->db->get('users');			
			} else {
				//die('got here');
			}
			
			// set user (id, email, group_id, key)
			foreach ($query->result_array() as $row) {
				$this->cols = $row;
			}			
			
			$this->remember_key($this->cols['ukey']);
			
			// return key	
			return $this->cols['ukey'];
		}
		
		
		public function send_link($email, $force_reset = false) {
			$this->load->library('messages');
		
			// set or make user
			$key = $this->set_user_with_email($email, $force_reset);
			if ($force_reset) {
				$key = $this->reset_key($email);
			}
						
			// send email with activation code
			$this->messages->send_activation_link($email, $key);

			// make sure user is logged out (in case someone requested new key)
			$this->logout();

		}

		public function is_admin() {
			// do we belong to group 1
			// if not logged in return false
			if (!isset($this->cols['group_id']) || $this->cols['group_id'] != 1) {
				return false;
			} else {
				return true;
			}
		}
		
		public function logged_in() {
			if (isset($this->cols['id']) && $this->cols['id'] > 0) {
				return true;
			}
			return false;
		}
		
		
		public function propose_new_email($new_email) {
			// must be logged in
			if (!$this->logged_in()) { 
				$this->error = "Must be logged in to change email.";
				return false;
			} 
			// already being used?
			$this->db->where('email', $new_email);
			$query = $this->db->get('users');

			if ($query->num_rows() > 0) {
				$this->error = "Someone is already using '{$new_email}.' If it's you, just go to <a href='".base_url('/workshops');"'>the front page</a> and log in with it.";
				return false;
			}
			
			// set it up as possible new email (and temp key)
			$temp_key = $this->generate_key();

			$this->db->where('id', $this->cols['id']);
			$this->db->update('users', array('new_email' => $new_email, 'temp_ukey' => $temp_key));

			$this->load->library('messages');
			$this->messages->send_change_email_link($new_email, $this->cols['ukey'], $temp_key);
			
		}
		
		public function invoke_new_email($key, $temp_key) {
			// log in user
			$this->set_user_with_key($key);
			if ($this->cols['temp_ukey'] == $temp_key) {
				//update email
				$this->db->where('id', $this->cols['id']);
				$this->db->update('users', array('email' => $this->cols['new_email']));
				$this->cols['email'] = $this->cols['new_email'];
				$this->message = "Email changed to '{$this->cols['email']}'.";
				return true;
			} else {
				$this->logout();
				$this->error = "Something is wrong with the link, so I can't update the email. Log in, then go to your profile page and try to change the email again.";
				return false;
			}
			
		}
		
		
		private function remember_key($key) {
			$this->session->set_userdata('ukey', $key);
			$this->input->set_cookie('ckey', $key, 31449600); // a year!
		}
				
		private function generate_key() {
			return substr(md5(uniqid(mt_rand(), true)), 0, 16);
		}
		
		private function reset_key($email) {
			// create a new key, update database, return key
			$key = $this->generate_key();
			$this->db->update('users', array('ukey' => $key), array('email' => $email));
		
			// return key
			return $key;
		}
		
		

}

?>