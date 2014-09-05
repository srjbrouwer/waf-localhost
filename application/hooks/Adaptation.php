<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Adaptation Layer
| Author: Brouwer, S.R.J.
| 
| -------------------------------------------------------------------------
*/
//Adapdation Class extends the framework to process output.
class Adaptation extends CI_Controller {
	//output of complete request
	public $output;
	
	function __construct(){
		parent::__construct();
		//connect to framework
		$this->CI =& get_instance();
		//get current page output
		$this->output = $this->CI->output->get_output();
	}	
	function Display(){	
		require_once('adapt.class.php');

		$adapt = new Adapt($this->output);
		$result = $adapt->run();

		echo $result;
	}
}
/* End of file adaptation.php */
?>