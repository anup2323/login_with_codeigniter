<?php
defined('BASEPATH') OR exit('No direct script access allowed');



class UsersapiController extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('UserapiModel');
		$this->load->helper('url','form','html');
		$this->load->library('form_validation');
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, PUT, DELETE, OPTIONS');
		header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');
	}

	public function response($data, $status = 200) {
		$this->output
			 ->set_content_type('application/json')
			 ->set_status_header($status)
			 ->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			 ->_display();
		exit;
	}

	public function login() {
		$user_details= $this->get_input();
		if (isset($_SERVER['PHP_AUTH_USER'])) {
			  $authusername = base64_decode($_SERVER['PHP_AUTH_USER']);
			  $authpassword = base64_decode($_SERVER['PHP_AUTH_PW']); 
			if(($authusername == 'username') && ($authpassword=='password')){
				$uname = $user_details->username;
				$upass = $user_details->password; 
				$data= array(
				'username' =>$uname,
				'password' =>$upass,
				
				);
				if(!empty($uname) && !empty($upass)){
					$res = $this->UserapiModel->checkLogin($data);
					if(!empty($res)){
						return $this->response([
						'success'	=> true,
						'message'	=> 'Login Successfully',
						'user_data' =>$res
						], );
					}else{
						return $this->response([
						'success'	=> false,
						'message'	=> 'Invalid username or password',
						], 401);
					}
					
				}else{
					return $this->response([
					'success'	=> false,
					'message'	=> 'Username and Password can not be blank'
					], 401);
				}
				
			}else{
				return $this->response([
					'success'	=> false,
					'message'	=> 'Invalid Auth Username or Password',
				], 401);
			}
		} else {
			return $this->response([
					'success'	=> false,
					'message'	=> 'Authorization can not be blank',
			], 401);
		}
	}
	
	

	public function get_input() {
		return json_decode(file_get_contents('php://input'));
		
	}
	

	// logout function
	public function logout(){
		$this->session->sess_destroy();
		return $this->response([
					'success'	=> true,
					'message'	=> 'Logout Successfully',
				], 401);
		
	}
	
	public function get_state(){
		 $res = $this->check_auth();  
		if($res == 1){
			$statelist = $this->UserapiModel->get_state();
			if(!empty($statelist)){
				return $this->response([
					'success'	=> true,
					'statelist'	=> $statelist,
				], );
		
			}else{
				return $this->response([
					'success'	=> false,
					'message'	=> 'No records found',
				], 401);
			}
		}else{
			return $this->response($res);
		}
	}
	public function get_district(){
		 $res = $this->check_auth();  
		if($res == 1){
			$statelist = $this->UserapiModel->get_district();
			if(!empty($statelist)){
				return $this->response([
					'success'	=> true,
					'statelist'	=> $statelist,
				], );
		
			}else{
				return $this->response([
					'success'	=> false,
					'message'	=> 'No records found',
				], 401);
			}
		}else{
			return $this->response($res);
		}
	}
	
	public function get_child(){
		 $res = $this->check_auth();  
		if($res == 1){
			$statelist = $this->UserapiModel->get_child();
			if(!empty($statelist)){
				return $this->response([
					'success'	=> true,
					'statelist'	=> $statelist,
				], );
		
			}else{
				return $this->response([
					'success'	=> false,
					'message'	=> 'No records found',
				], 401);
			}
		}else{
			return $this->response($res);
		}
	}
	public function save_child(){
		 $res = $this->check_auth(); 
		if($res == 1){
			$child_name = $_POST['child_name'];
			$dob = $_POST['dob'];
			$gender = $_POST['gender'];
			$father_name	 = $_POST['father_name'];
			$mother_name = $_POST['mother_name'];
			$state_id = $_POST['state_id'];
			$district_id = $_POST['district_id'];
			 $file_name = $_FILES['file']['name'];
			$filedta = $_FILES['file'];
			$table ='child_details';
			
			$data= array(
			'child_name' =>$child_name,
			'dob' =>$dob,
			'gender' =>$gender,
			'father_name' =>$father_name,
			'mother_name' =>$mother_name,
			'state_id' =>$state_id,
			'district_id' =>$district_id,
			'image' =>$file_name,
			);
			
			if(!empty($child_name) && !empty($dob) && !empty($gender) && !empty($father_name) && !empty($mother_name) && !empty($state_id) && !empty($district_id) && !empty($file_name)){
				$config['upload_path'] = './uploads/';
				$config['allowed_types'] = 'jpg|png';
				$config['max_size'] = 2000;
				$config['max_width'] = 1500;
				$config['max_height'] = 1500;
				$this->load->library('upload', $config);
				if (!$this->upload->do_upload('file')) {
					$error = array('error' => $this->upload->display_errors());
					return $this->response([
					'success'	=> false,
					'statelist'	=> $error,
					], 401);
				}else{
					$uploaddata = array('image_metadata' => $this->upload->data());
					$result = $this->UserapiModel->save($data,$table);
					return $this->response($result);
				}
				
			}else{
				return $this->response([
					'success'	=> false,
					'statelist'	=> 'some filed is missing',
				], 401);
			}
		}else{
			return $this->response($res);
		}
	}
	
	public function save_dist(){
		$user_details= $this->get_input();
		 $res = $this->check_auth(); 
		if($res == 1){
			$state_id = $user_details->state_id;
			$district_name = $user_details->district_name;
			$table ='district';
			$data= array(
			'state_id' =>$state_id,
			'district_name' =>$district_name,
			
			);
			$statedate = $this->UserapiModel->save($data,$table);
			return $this->response($statedate);
		}else{
			return $this->response($res);
		}
	}
	
	public function save_state(){
		$user_details= $this->get_input();
		 $res = $this->check_auth(); 
		if($res == 1){
			$state_name = $user_details->state_name;
			$table ='state';
			$data= array(
			'state_name' =>$state_name,
			);
			$statedate = $this->UserapiModel->save($data,$table);
			return $this->response($statedate);
		}else{
			return $this->response($res);
		}
	}
	public function check_auth(){
		if (isset($_SERVER['PHP_AUTH_USER'])) {
			 $authusername = base64_decode($_SERVER['PHP_AUTH_USER']);
			 $authpassword = base64_decode($_SERVER['PHP_AUTH_PW']);  
			if(!empty($authusername) && !empty($authpassword)){
				if(($authusername =='username')  && ($authpassword =='password')){
					 return 1;
				}else{
					return [
						'success'	=> false,
						'message'	=> 'Invalid auth details',
					];
				}
			}else{
				return [
						'success'	=> false,
						'message'	=> 'Authorization can not be blank',
				];
			}
		}else{
			return [
						'success'	=> false,
						'message'	=> 'Invalid auth',
				];
		}
	}
		
		
	

		 
}