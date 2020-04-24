<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  class Auth2 extends CI_Controller {
	 function __construct() {
        parent::__construct();
        $this->load->library('google','session');
        $this->load->model(array('requests_model')); 
    }

	public function index(){
    $info['data']=$this->input->post();
    $this->session->set_userdata($info);
		$data['google_login_url']=$this->google->get_login_url();
    $data['is_login'] = $this->session->userdata('sess_logged_in');
    $data['source'] =  $this->session->userdata('source');
    
    //echo json_encode($info);
    if($data['is_login']==0){
      $this->load->view('home',$data);
    }else{
       if($data['source']=='google'){
           $this->oauth2callback();
        }elseif($data['source']=='facebook'){
           $this->fblogin();
       }
    }
	}
    
    

	public function oauth2callback(){
        try{
             $google_data=$this->google->validate();
             $session_data=array(
            'person'=>$google_data['name'],
            'email'=>$google_data['email'],
            'source'=>'google',
            'profile_pic'=>$google_data['profile_pic'],
            'link'=>$google_data['link'],
            'sess_logged_in'=>1
            );
            $this->session->set_userdata($session_data);
            $data['name'] = $google_data['name'];
            $data['email'] = $google_data['email'];
            $details['gmail_user'] = $this->requests_model->add_gmail_user($data);
            
            $info=$this->session->userdata();
            if(!empty($info['data'])){
              $movie['imdb_id']=$info['data']['id'];
              $movie['title']=$info['data']['name'];
              $type = $info['data']['type'];
              if($type == 'movie'){
                   $details['movies']=$this->requests_model->add_movie($movie);
                   $this->requests_model->add_movie_request($details); 
              }elseif($type == 'show'){
                  $movie['sn']=$info['data']['season'];
                  $details['series']=$this->requests_model->add_serie($movie);
                  $this->requests_model->add_serie_request($details);
              }

            }
           redirect($info['data']['ret_url']);
        }catch (Google_Auth_Exception $e) {
          $this->logout();
        }
		    
	}
    
	public function logout(){
		session_destroy();
		unset($_SESSION['access_token']);
		$session_data=array(
				'sess_logged_in'=>0);
		$this->session->set_userdata($session_data);
		redirect(base_url());
    //echo "logout";
	}
    
  public function fblogin(){
         $fb = new Facebook\Facebook([
            'app_id' => '348932942383696',
            'app_secret' => 'a8f538da2a9745b389f76f67cc45c77f',
            'default_graph_version' => 'v2.5',
          ]);

     $helper = $fb->getRedirectLoginHelper();

     //$permissions = ['email','user_location','user_birthday','publish_actions']; 
      $permissions = ['email','user_birthday'];
     // For more permissions like user location etc you need to send your application for review

     $loginUrl = $helper->getLoginUrl('https://bodamovies.xyz/auth2/fbcallback', $permissions);

     header("location: ".$loginUrl);
}
  
  public function fbcallback(){
       $fb = new Facebook\Facebook([
          'app_id' => '348932942383696',
          'app_secret' => 'a8f538da2a9745b389f76f67cc45c77f',
          'default_graph_version' => 'v2.5',
        ]);
    
        $helper = $fb->getRedirectLoginHelper();  
  
        try {  
            
            $accessToken = $helper->getAccessToken();  
            
        }catch(Facebook\Exceptions\FacebookResponseException $e) {  
          // When Graph returns an error  
          echo 'Graph returned an error: ' . $e->getMessage();  
          exit;  
        } catch(Facebook\Exceptions\FacebookSDKException $e) {  
          // When validation fails or other local issues  
          echo 'Facebook SDK returned an error: ' . $e->getMessage();  
          exit;  
        }  
 
 
        try {
          // Get the Facebook\GraphNodes\GraphUser object for the current user.
          // If you provided a 'default_access_token', the '{access-token}' is optional.
          $response = $fb->get('/me?fields=id,name,email,first_name,last_name,birthday,location,gender', $accessToken);
         // print_r($response);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
          // When Graph returns an error
          echo 'ERROR: Graph ' . $e->getMessage();
          exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
          // When validation fails or other local issues
          echo 'ERROR: validation fails ' . $e->getMessage();
          exit;
        }
    
        // User Information Retrival begins................................................
        $me = $response->getGraphUser();
        
        $location = $me->getProperty('location');
//         echo "Full Name: ".$me->getProperty('name')."<br>";
//         echo "First Name: ".$me->getProperty('first_name')."<br>";
//         echo "Last Name: ".$me->getProperty('last_name')."<br>";
//         echo "Gender: ".$me->getProperty('gender')."<br>";
//         echo "Email: ".$me->getProperty('email')."<br>";
//         echo "location: ".$location['name']."<br>";
//         echo "Birthday: ".$me->getProperty('birthday')->format('d/m/Y')."<br>";
//         echo "Facebook ID: <a href='https://www.facebook.com/".$me->getProperty('id')."' target='_blank'>".$me->getProperty('id')."</a>"."<br>";
//         $profileid = $me->getProperty('id');
//         echo "</br><img src='//graph.facebook.com/$profileid/picture?type=large'> ";
//         echo "</br></br>Access Token : </br>".$accessToken;
    
        $session_data=array(
				'person' => $me->getProperty('name'),
				'email' => $me->getProperty('email'),
				'source' => 'facebook',
        'profileid' => $me->getProperty('id'),
				'sess_logged_in'=>1
				);
        $this->session->set_userdata($session_data);
    
        $data['oauth_provider']='facebook';
        $data['oauth_uid']= $me->getProperty('id');
        $data['first_name']=$me->getProperty('first_name');
        $data['last_name']=$me->getProperty('last_name');
        $data['email']=$me->getProperty('email');
        $data['gender']=$me->getProperty('gender');
        $data['picture']="//graph.facebook.com/".$me->getProperty('id')."/picture?type=large";
        $data['link']=$accessToken;
   
        $details['facebook_user']=$this->requests_model->add_facebook_user($data);
        $info=$this->session->userdata();
        if(!empty($info['data'])){
          $movie['imdb_id']=$info['data']['id'];
          $movie['title']=$info['data']['name'];
          $type = $info['data']['type'];
          if($type == 'movie'){
               $details['movies']=$this->requests_model->add_movie($movie);
               $this->requests_model->add_movie_request($details); 
          }elseif($type == 'show'){
              $movie['sn']=$info['data']['season'];
              $details['series']=$this->requests_model->add_serie($movie);
              $this->requests_model->add_serie_request($details);
          }
          
        }
        redirect($info['data']['ret_url']);
    }
    
}
