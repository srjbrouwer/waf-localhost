<?php

class errors extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('menu_model');
	}

	public function view($page = '404')
	{
		if ( ! file_exists('application/views/errors/'.$page.'.php'))
		{
			// Whoops, we don't have a page for that!
			show_404();
		}
        
        //load menu
        $data['menu'] = $this->menu_model->get_menu();
        
		$data['title'] = ucfirst("Fout - ".$page); // Capitalize the first letter

		$this->load->view('templates/header', $data);
		$this->load->view('errors/'.$page, $data);
		$this->load->view('templates/footer', $data);
	}
}