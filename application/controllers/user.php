<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {
	function __construct(){
   		parent::__construct();
   		$this->load->model('users','',TRUE);
   		$this->load->model('menu_model');
   		$this->load->library('form_validation');
		$this->load->helper(array('form'));
 	}
	function login($redirect="zoeken"){
		//load menu
		$data['menu'] = $this->menu_model->get_menu();
		
		//This method will have the credentials validation
		if (isset($_POST['email'])){
			$this->form_validation->set_rules('email', 'Email', 'trim|required');
			if($this->form_validation->run() == true){
				$this->form_validation->set_rules('password', 'Password', 'trim|required|sha1|callback_check_database');
			}
			if($this->form_validation->run() == false){
				//Field validation failed.  User redirected to login page
				$data['title'] = "Login"; // Capitalize the first letter
				$data['redirect'] = $redirect;
				
				$this->load->view('templates/header', $data);
				$this->load->view('user/login_view',$data);
				$this->load->view('templates/footer', $data);	 	
			}else{
				redirect($redirect, 'refresh');
			}
		}else{
				$data['title'] = "Login"; // Capitalize the first letter
				$data['redirect'] = $redirect;
				$this->load->view('templates/header', $data);
				$this->load->view('user/login_view',$data);
				$this->load->view('templates/footer', $data);	 				
		}
	}
	function logout(){
		$this->session->unset_userdata('user');
		redirect('/', 'refresh');
	}
	function index(){
			$data['title'] = "User"; // Capitalize the first letter
			//$this->load->view('templates/header', $data);
			//$this->load->view('login_view',$data);
			//$this->load->view('templates/footer', $data);	 	
	}
	function check_database($password){
		
	   //Field validation succeeded.  Validate against database
	   $user_email = $this->input->post('email');

	   //query the database
	   $result = $this->users->login($user_email, $password);
		
	   if($result){
			$sess_array = array();
			
			foreach($result as $row){
		   		$sess_array = array(
			 		'user_id' => $row->user_id,
			 		'user_active' => $row->user_active,
			 		'user_email' => $row->user_email,
			 		'user_acl' => $row->acl_id,
			 		'user_fname' => $row->user_fname,
			 		'user_lname' => $row->user_lname
		   		);
		 	}
		 	$this->session->set_userdata('user', $sess_array);
			return true;
	   }else{
			$this->form_validation->set_message('check_database', 'Invalid username or password');
			return false;
	   }
	}
}
?>
