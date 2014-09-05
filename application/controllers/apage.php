<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Apage extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('pages');
	}
	function view($page){
		$current_session = $this->session->userdata('user');
 		if ((strlen($page)>0) && (substr($page,0,1)=='a')){
 			$data['page_meta'] = $this->pages->get_page_meta($page);
 			
 			if(count($data['page_meta'])>0){
 				if(($data['page_meta'][0]->acl_id <= $current_session['user_acl'])||($data['page_meta'][0]->acl_id==0)){
	 				
					//title
 					$data['title'] = ucfirst($data['page_meta'][0]->page_title);
					
					//get adaptation data
					$requested_page = uri_string();
					
					//body
					if (file_exists('application/views/apage/'.$page.'.php')){
							$this->load->view('apage/'.$page, $data);
					}else{
							$data['page_content']=$this->pages->get_page($data['page_meta'][0]->page_id);
							$this->load->view('apage/index', $data);
					}
				}
			}
		}
	}
}
?>
