<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wallet_model extends CI_Model{

	public function __construct(){
		$this->load->database();
	}

	public function add_transaction($data){
		$this->db->insert('transactions',$data);
	}

	public function update_withdraw_transaction($user,$reference,$token){
		$this->db->set('reference',$reference);
		$this->db->where('users',$user);
		$this->db->where('token',$token);
		$this->db->update('transactions');
	}

	public function update_transaction($data,$status,$amount){
		$this->db->set('status',$status);
		$this->db->set('amount',$amount);
		$this->db->where('users',$data['users']);
		$this->db->where('reference',$data['reference']);
		$this->db->update('transactions');
	}

	public function get_transactions($user){
		$this->db->where('users',$user);
		$this->db->where('read','0');
		$this->db->where('status >','1');
		$query = $this->db->get('transactions');
		return $query->result_array();
	}

	public function read_user_transactions($user){
		$this->db->set('read','1');
		$this->db->where('users',$user);
		$this->db->where('status >','1');
		$this->db->update('transactions');
	}

	public function create_wallet($user){
		$this->db->insert('wallet',array('users'=>$user));
	}

	public function get_wallet($user){
		$this->db->where('users',$user);
		$query = $this->db->get('wallet');
		if($query->num_rows()>0){
           return $query->row_array();
		}else{
			$this->db->insert('wallet',array('users'=>$user));
			return 0;
		}
		
	}


	public function update_wallet($user,$amount){
		$this->db->set('amount',$amount);
		$this->db->where('users',$user);
		$this->db->update('wallet');
	}

	public function verify_token($data){
		$query = $this->db->get_where('transactions',$data);
		if($query->num_rows()>0){
			return '1';
		}else{
			return '0';
		}
	}

}