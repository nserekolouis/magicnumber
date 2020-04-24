<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	function __construct(){
		parent::__construct();
		$this->load->model(array('main_model','users_model','wallet_model'));
	}

	public function load_game(){
		$string = $this->input->get('number');
		if(!empty($string)){
		    $contact = "+".str_replace(' ', '',$string);
		    $user = $this->users_model->get_user_details(array('mobile_number'=>$contact))['id'];
		    $data['user_stake'] = $this->users_model->get_user_details(array('mobile_number'=>$contact))['stake'];
		    $transactions = $this->wallet_model->get_transactions($user);
            $credit = $this->wallet_model->get_wallet($user)['amount'];
		    if(sizeof($transactions)>0){
		    	foreach($transactions as $key => $transaction){
				# code...
				    if($transaction['status'] == '2'){
				    	$credit = $credit+$transaction['amount'];
				    }elseif ($transaction['status'] == '3') {
				    	# code...
				    	$credit = $credit+$transaction['amount'];
				    }elseif ($transaction['status'] == '4') {
				    	# code...
				    	$credit = $credit-$transaction['amount'];
				    }
			    }
			    $this->wallet_model->read_user_transactions($user);
			    $this->wallet_model->update_wallet($user,$credit);
		    }
			
			$data['credit'] = $credit;
			$data['mobile_number'] = $contact;
			$data['games'] = $this->main_model->get_all_games();
			$data['play'] = $this->main_model->get_last_timestamp()['id'];
			$data['stoptime'] = $this->main_model->get_last_timestamp()['datetime'];
			$this->load->view('game',$data);
			//echo json_encode($data);
		}else{
            redirect('/','refresh');
		}
	}
	public function generate_random_number(){
		$randomNumber = mt_rand(1,9);
		#get last time stamp
		$id = $this->main_model->get_last_timestamp()['id'];
		$this->main_model->add_random_number(array('num'=>$randomNumber,'id'=>$id));

		date_default_timezone_set("Africa/Nairobi");
		#get date today
		#$date = date("Y-m-d");
		#get current timestamp
		$t = date('H:i:s'); 
		$time = date('Y-m-d H:i:s',strtotime('+61 seconds',strtotime($t)));
		$data['datetime'] = $time;
		$this->main_model->add_datetime($data);
	}

	public function get_prediction(){    
		$data['prediction'] = $this->input->post('prediction');
		$data['games'] = $this->input->post('play');
		$string = "+256".substr($this->input->post('mobile_number'), -9);
		$mobile_number = str_replace(' ', '',$string);
		$data['users'] = $this->users_model->get_user_details(array('mobile_number'=>$mobile_number))['id'];
		$this->main_model->make_prediction($data);
		$info['wnumber'] = $this->main_model->get_game_details($data['games'])['num'];
	    $info['prediction'] = $data['prediction'];
		$info['status'] = "1";
		$stake = $this->input->post('stake');
		if($info['wnumber'] == $data['prediction']){
			$this->user_won($mobile_number,$stake);
		}else{
			$this->user_lost($mobile_number,$stake);
		}
		echo json_encode($info);
	}

	public function user_won($contact,$stake){
		$data['users'] = $this->users_model->get_user_details(array('mobile_number'=>$contact))['id'];
		$data['amount'] = $stake;
		$data['reference'] = '101';
		$data['status']='3';
		$this->wallet_model->add_transaction($data);
	}

	public function user_lost($contact,$stake){
		$data['users'] = $this->users_model->get_user_details(array('mobile_number'=>$contact))['id'];
		$data['amount'] = $stake;
		$data['reference'] = '101';
		$data['status']='4';
		$this->wallet_model->add_transaction($data);
	}

	public function topup(){
		$string = $this->input->get('number');
		if(!empty($string)){
		    $contact = "+".str_replace(' ', '',$string);
		    $user = $this->users_model->get_user_details(array('mobile_number'=>$contact))['id'];
		    $data['user_stake'] = $this->users_model->get_user_details(array('mobile_number'=>$contact))['stake'];
		    $transactions = $this->wallet_model->get_transactions($user);
            $credit = $this->wallet_model->get_wallet($user)['amount'];
		    if(sizeof($transactions)>0){
		    	foreach($transactions as $key => $transaction){
				# code...
				    if($transaction['status'] == '2'){
				    	$credit = $credit+$transaction['amount'];
				    }elseif ($transaction['status'] == '3') {
				    	# code...
				    	$credit = $credit+$transaction['amount'];
				    }elseif ($transaction['status'] == '4') {
				    	# code...
				    	$credit = $credit-$transaction['amount'];
				    }
			    }
			    $this->wallet_model->read_user_transactions($user);
			    $this->wallet_model->update_wallet($user,$credit);
		    }
			
			$data['credit'] = $credit;
			$data['mobile_number'] = $contact;
			$data['games'] = $this->main_model->get_all_games();
			$data['play'] = $this->main_model->get_last_timestamp()['id'];
			$data['stoptime'] = $this->main_model->get_last_timestamp()['datetime'];
			$this->load->view('topup',$data);
			//echo json_encode($data);
		}else{
            redirect('/','refresh');
		}
	}

	public function add_transaction(){
		$contact = "+256".substr(str_replace(' ', '',$this->input->post('users')), -9); 
		$data['users'] = $this->users_model->get_user_details(array('mobile_number'=>$contact))['id'];
		$data['amount'] = $this->input->post('amount');
		$data['reference'] = $this->input->post('reference');
		$this->wallet_model->add_transaction($data);
		$response['message'] = 'transaction added';
		$response['status'] = '1';
		echo json_encode($response);
	}

	public function update_transaction(){
		$contact = "+256".substr(str_replace(' ', '',$this->input->post('user')), -9);
		$data['users'] = $this->users_model->get_user_details(array('mobile_number'=>$contact))['id'];
		$data['reference'] = $this->input->post('reference');
		$data['status'] = $this->input->post('status');
		$data['amount'] = $this->input->post('amount');
		$this->wallet_model->update_transaction($data,$data['status'],$data['amount']);
		$response['message'] = 'transaction updated';
		$response['status'] = '1';
		echo json_encode($response);
	}

	public function withdraw(){
		$string = $this->input->get('number');
		if(!empty($string)){
			$contact = "+".str_replace(' ', '',$string);
			$user = $this->users_model->get_user_details(array('mobile_number'=>$contact))['id'];
			$credit = $this->wallet_model->get_wallet($user)['amount'];
			$transactions = $this->wallet_model->get_transactions($user);
		    if(sizeof($transactions)>0){
		    	foreach($transactions as $key => $transaction){
				# code...
				    if($transaction['status'] == '2'){
				    	$credit = $credit+$transaction['amount'];
				    }elseif ($transaction['status'] == '3') {
				    	# code...
				    	$credit = $credit+$transaction['amount'];
				    }elseif ($transaction['status'] == '4') {
				    	# code...
				    	$credit = $credit-$transaction['amount'];
				    }
			    }
			    $this->wallet_model->read_user_transactions($user);
			    $this->wallet_model->update_wallet($user,$credit);
		    }
		    $data['mobile_number'] = $contact;
			$data['credit'] = $credit;
			$this->load->view('withdraw',$data);
		}else{
			redirect('/','refresh');
		}
	}

	public function make_withdraw(){
		$amount = $this->input->post('amount');
		$user_contact =  str_replace(' ', '',$this->input->post('user'));
		$data['user'] = "+256".substr($user_contact, -9);
        $user_id = $this->users_model->get_user_details(array('mobile_number'=>$data['user']))['id'];
        
        #if user can withdraw		
		$credit = $this->wallet_model->get_wallet($user_id)['amount'];
		if($credit>=$amount){
			$digits = 4;
            $data['code'] = rand(pow(10, $digits-1), pow(10, $digits)-1);	
            $method = 'POST';
	        $info['username'] = 'admin@admin.com';
	        $info['password'] = 'password';
	        $info['message'] = "Magic Number, withdraw code is ".$data['code'];
	        $info['contact'] = $data['user'];
	        $info['phonebook'] = '418';
	        $url = 'http://sendsmsug.info/apis/send_sms';
	        $this->CallAPI($method,$url,$info);
	        $this->add_withdraw_transaction($user_id,$amount,$data['code']);
	        $response['message'] = 'success';
			$response['status_code'] = '1';
		}else{
			$response['message'] = 'insufficient amount';
			$response['status_code'] = '0';
		}
		echo json_encode($response);
	}

	public function add_withdraw_transaction($user_id,$amount,$token){
		$data['users'] = $user_id;
		$data['amount'] = $amount;
		$data['reference'] = '101';
		$data['status']= '1';
		$data['token'] = $token;
		$this->wallet_model->add_transaction($data);
	}

	public function request_withdraw($user,$withdraw_contact,$withdraw_amount,$token){
 	    $url = 'http://www.sudopay.xyz/node/make_transaction/';
        $info['username'] = "nserekolouis@gmail.com";
        $info['password'] = "password";
        $info['contact']  = $withdraw_contact;
        $info['amount'] = $withdraw_amount;
        $info['trans_type'] = "cash_out";
        $method = 'POST';
        $result = $this->CallAPI($method,$url,$info);
        $json_result = json_decode($result,true);
        $clue['status_code'] = $json_result['status_code']; 
        $clue['message'] = $json_result['message'];
        $clue['order_number'] = $json_result['order_number'];
        if(!empty($clue['order_number'])){
        	$this->update_withdraw_transaction($user,$clue,$token);
        }
		return $clue;
	}

	public function update_withdraw_transaction($user,$clue,$token){
		$reference = $clue['order_number'];
		$token = $token;
		$this->wallet_model->update_withdraw_transaction($user,$reference,$token);
	}

	public function withdraw_made(){

	}

	public function withdraw_not_made(){

	}

	public function verify_token(){
		$user_number = "+256".substr(str_replace(' ', '',$this->input->post('user_number')), -9);
		$data['token'] = $this->input->post('withdraw_code');
		$data['users'] = $this->users_model->get_user_details(array('mobile_number'=>$user_number))['id'];
		$result = $this->wallet_model->verify_token($data);
		if($result == 1){
			$info['withdraw_contact'] = $this->input->post('withdraw_contact');
			$info['withdraw_amount'] = $this->input->post('withdraw_amount');
			$response = $this->request_withdraw($data['users'],$info['withdraw_contact'],$info['withdraw_amount'],$data['token']);
		}else{
		    $response['message'] = 'wrong code';
		    $response['status_code'] = '0';
		}
		echo json_encode($response);
	}


	function CallAPI($method, $url, $data = false){
	    $curl = curl_init();

	    switch ($method){
	        case "POST":
	            curl_setopt($curl, CURLOPT_POST, 1);
	            if ($data)
	                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	            break;
	        case "PUT":
	            curl_setopt($curl, CURLOPT_PUT, 1);
	            break;
	        default:
	            if ($data)
	                $url = sprintf("%s?%s", $url, http_build_query($data));
	    }

	    // Optional Authentication:
	    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	    curl_setopt($curl, CURLOPT_USERPWD, "username:password");
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	    $result = curl_exec($curl);
	    curl_close($curl);
	    return $result;
	}

	public function make_deposit_request(){
		$network = $this->input->post('network');
		if(strcmp($network,"MTN") == 0){
			$info['contact'] = $this->input->post('contact');
		    $info['amount'] = $this->input->post('amount');
		    $info['trans_type'] = $this->input->post('trans_type');
		    $info['username'] = "nserekolouis@gmail.com";
		    $info['password'] = "password";
		    $info['trans_type'] = "cash_in";
		    $url = "http://www.sudopay.xyz/node/make_transaction";
		    $method = "POST";
		    $response = json_decode($this->CallAPI($method,$url,$info),true);
		}else if(strcmp($network,"Airtel") == 0){
			$info['contact'] = $this->input->post('contact');
		    $info['amount'] = $this->input->post('amount');
		    $info['secret_code'] = $this->input->post('secret_code');
		    $info['trans_type'] = $this->input->post('trans_type');
		    $info['username'] = "nserekolouis@gmail.com";
		    $info['password'] = "password";
		    $info['trans_type'] = "cash_in";
		    $url = "http://www.sudopay.xyz/node/make_transaction";
		    $method = "POST";
		    $response = json_decode($this->CallAPI($method,$url,$info),true);
		}else{
			$response['message'] = "failed";
			$response['status_code'] = "0";
		}

		echo json_encode($response);
	}

	public function policy(){
		$this->load->view('policy');
	}
}
