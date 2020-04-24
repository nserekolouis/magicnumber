<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

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
		$this->load->model(array('users_model'));
	}

	public function index(){
		$this->load->view('landing_page');
	}
	

	public function login(){
		$mobile_number = $this->input->post('mobile_number');
		$code = $this->input->post('code');
		if(!empty($mobile_number)&&empty($code)){
            #clean the number
            $data['mobile_number'] = "+256".substr($mobile_number, -9);
            $digits = 4;
            $data['code'] = rand(pow(10, $digits-1), pow(10, $digits)-1);	
            $method = 'POST';
	        $info['username'] = 'admin@admin.com';
	        $info['password'] = 'password';
	        $info['message'] = "Magic Number code, please use this code to login".$data['code'];
	        $info['contact'] = $data['mobile_number'];
	        $info['phonebook'] = '418';
	        $url = 'http://sendsmsug.info/apis/send_sms';
	        $this->CallAPI($method,$url,$info);
            $this->users_model->add_user($data);
            $this->load->view('verification',$data);
		}else if(!empty($mobile_number)&&!empty($code)){
          $result = $this->users_model->verify_user(array('mobile_number'=>$mobile_number,'code'=>$code));
	        if($result == 1){
	        	redirect('main/load_game/?number='.$mobile_number,'refresh');
	        }
		}else{
			$this->load->view('login');
		}	
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
}
