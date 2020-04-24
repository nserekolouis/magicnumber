<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_model extends CI_Model{

    public function __construct(){
		$this->load->database();
	}

	public function add_user($data){
		$this->db->where('mobile_number',$data['mobile_number']);
		$query = $this->db->get('users');
		if($query->num_rows()>0){
			$this->db->set('code',$data['code']);
			$this->db->where('mobile_number',$data['mobile_number']);
			$this->db->update('users');
			return 0;
		}else{
			$this->db->insert('users',$data);
			return 1;
		}
	}

	public function verify_user($data){
		$this->db->where('mobile_number',$data['mobile_number']);
		$this->db->where('code',$data['code']);
		$query = $this->db->get('users');
		if($query->num_rows()>0){
			return 1;
		}else{
			return 0;
		}
	}

	public function get_user_details($data){
		$query = $this->db->get_where('users',$data);
	    return $query->row_array(); 
	}

}