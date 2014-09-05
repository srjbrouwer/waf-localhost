<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mc extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('menu_model');
		$this->load->model('pages');
		$this->load->helper('form');
		$this->load->library('af_redirect');
	}
	function view($test){
 		if (strlen($page)>0){
 		
 			$data['page_meta'] = $this->pages->get_page_meta($page);
 			
 			if(count($data['page_meta'])>0){
 				if(($data['page_meta'][0]->acl_id <= $current_session['user_acl'])||($data['page_meta'][0]->acl_id==0)){
	 				
	 				//load menu
					$data['menu'] = $this->menu_model->get_menu();
					//title
 					$data['title'] = ucfirst($data['page_meta'][0]->page_title);
					//header
					$this->load->view('templates/header', $data);
					
					//get adaptation data
					$requested_page = uri_string();
					
					//body
					if (file_exists('application/views/page/'.$page.'.php')){
							$this->load->view('page/'.$page, $data);
					}else{
							$data['page_content']=$this->pages->get_page($data['page_meta'][0]->page_id);
							$this->load->view('page/index', $data);
					}
					//footer
					$this->load->view('templates/footer', $data);
				}else{
					$this->af_redirect->to('/errors/403');
				}
			}else{
				$this->af_redirect->to('/errors/404');
			}
		}else{
			$this->af_redirect->to('/errors/404');
		}
	}
}
?>
