<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
	var $current_session;
	function __construct(){
   		parent::__construct();
   		//$this->output->enable_profiler();

		$this->current_session = $this->session->userdata('user');
		if(!$_SESSION['login']){
			redirect('user/login/admin', 'refresh');
		}

		//load crud library
		$this->load->library('grocery_CRUD');
		$this->load->model('menu_model');
 	}
	public function users()
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_table('users');
			$crud->set_subject('Users');
			$crud->display_as('acl_id','Access Level');
			$crud->set_relation('acl_id','acls','acl_label');
 			$crud->change_field_type('user_password', 'password');
			$crud->callback_edit_field('user_password',array($this,'set_password_field_empty'));
    		$crud->callback_add_field('user_password',array($this,'set_password_field_empty'));
			$crud->callback_before_insert(array($this,'encrypt_password_callback'));
			$crud->callback_before_update(array($this,'encrypt_password_callback'));
			
			$output = $crud->render();
			$this->_crud_output($output);

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	public function pages()
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_table('pages');
			$crud->set_subject('Page');
			$crud->required_fields('page_name','acl_id','page_title');
			$crud->columns('page_name','acl_id','page_title','page_text');
			//$crud->display_as('acl_id','Access Level');
			$crud->set_relation('acl_id','acls','acl_label');
			
			$output = $crud->render();
			$this->_crud_output($output);

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	public function menus()
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_table('menu');
			$crud->set_subject('Menus');
			$crud->display_as('acl_id','Access Level');
			$crud->set_relation('acl_id','acls','acl_label');
			$crud->set_relation('parent_id','menu','name');
			$crud->set_relation('menu_id','menus','name');
			
			$output = $crud->render();
			$this->_crud_output($output);

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	public function a_settings()
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_table('a_globals');
			$crud->set_subject('Adaptation Global Script');
			$crud->unset_add();
			$crud->unset_delete();
			$crud->unset_texteditor('startup');
			
			$output = $crud->render();
			$this->_crud_output($output);

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	public function a_concepts()
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_table('a_concepts');
			$crud->set_subject('Adaptation Concepts');
			$crud->set_relation('concept_parent_id','a_concepts','concept_name');
			
			$output = $crud->render();
			$this->_crud_output($output);

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	public function a_variables()
	{
		try{
			$crud = new grocery_CRUD();
			$crud->set_table('a_vars');
			$crud->set_subject('Adaptation Variables');
			$crud->set_relation('concept_id','a_concepts','concept_name');
			
			$output = $crud->render();
			$this->_crud_output($output);

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}	
	public function a_variable_values()
	{
		try{
			$crud = new grocery_CRUD();
			$crud->set_table('a_vars_values');
			$crud->set_subject('Adaptation Variables Values');
			//$crud->set_relation('var_id','a_vars','var_name');
			
			$output = $crud->render();
			$this->_crud_output($output);

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}	
	public function a_relationships()
	{
		try{
			$crud = new grocery_CRUD();
			$crud->set_table('a_relationships');
			$crud->set_subject('Adaptation Relationships');
			$crud->set_relation('parent_concept_id','a_concepts','concept_name');
			$crud->set_relation('child_concept_id','a_concepts','concept_name');
			
			$output = $crud->render();
			$this->_crud_output($output);

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	//---------------------------[MC plugin]--------------------------------------
	public function mc_test()
	{
		try{
			$crud = new grocery_CRUD();
			$crud->set_table('mc_test');
			$crud->set_subject('MC tests');
			$crud->set_relation_n_n('questions','mc_test_question','mc_question','question_id','test_id','question');
			
			$output = $crud->render();
			$this->_crud_output($output);

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	public function mc_question()
	{
		try{
			$crud = new grocery_CRUD();
			$crud->set_table('mc_question');
			$crud->set_subject('MC questions');
			$crud->set_relation('test_id','mc_test','test_name');

			$output = $crud->render();
			$this->_crud_output($output);

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}	
	public function mc_answer()
	{
		try{
			$crud = new grocery_CRUD();
			$crud->set_table('mc_answer');
			$crud->set_subject('MC answer');
			$crud->set_relation('question_id','mc_question','question');
			$crud->set_relation('correct_id','mc_correct','correct_value');

			$output = $crud->render();
			$this->_crud_output($output);

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}		
	public function _crud_output($output = null)
	{
		$data['crud']=$output;
		
		$data['title'] = "Admin - CRUD";
		$data['menu'] = $this->menu_model->get_admin_menu($this->current_session['user_acl']);

		$this->load->view('templates/header_crud', $data);
		
		$this->load->view('crud.php',$data);
		$this->load->view('templates/footer', $data);	 	
	}
	function index(){
			$data['title'] = "Admin"; // Capitalize the first letter
			$data['menu'] = $this->menu_model->get_admin_menu($this->current_session['user_acl']);
			$data['user'] = $this->current_session;
			
			$this->load->view('templates/header_crud', $data);
			$this->load->view('admin/index',$data);
			$this->load->view('templates/footer', $data);	 	
	}
	function encrypt_password_callback($post_array) {
		$post_array['user_password'] = sha1($post_array['user_password']);

		return $post_array;
	}
	function set_password_field_empty() {
    	return "<input type='password' name='user_password' value='' />";
	}
}
?>
