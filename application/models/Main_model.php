<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main_model extends CI_Model{

  public function __construct(){
    $this->load->database();
  }

  public function add_datetime($data){
  	$this->db->insert('games',$data);
  }

  public function get_last_timestamp(){
  	$this->db->order_by('id','desc');
  	$this->db->limit(1);
  	$query = $this->db->get('games');
  	return $query->row_array();
  }

  public function add_random_number($data){
    $this->db->set('num',$data['num']);
    $this->db->where('id',$data['id']);
    $this->db->update('games'); 
  }

  public function get_all_games(){
    $this->db->order_by('id','desc');
    $query = $this->db->get('games');
    return $query->result_array();
  }

  public function make_prediction($data){
    $this->db->insert('draws',$data);
  }

  public function get_game_details($id){
    $this->db->where('id',$id);
    $query = $this->db->get('games');
    return $query->row_array();
  }

}