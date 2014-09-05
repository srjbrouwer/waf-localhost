<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class File extends CI_Controller {
	function __construct(){
   		parent::__construct();
 	}
	function view($file){
		if (file_exists('application/views/file/'.$file.'.php')){
			$this->load->view('file/'.$file);
		}
	}
}
?>
