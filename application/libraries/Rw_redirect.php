<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Af_Redirect{
	public function to($view){
		$CI =& get_instance();
		$data['title'] = ucfirst("Reiswok");
		$CI->load->view('vw_templates/header', $data);
		if (file_exists('application/views/'.$view.'.php')){
				$CI->load->view($view, $data);
		}
		$CI->load->view('vw_templates/footer', $data);
	}
}
?>