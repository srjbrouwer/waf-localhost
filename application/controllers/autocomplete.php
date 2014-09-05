<?php
class Autocomplete extends CI_Controller {
	function index() {
		if(isset($_POST['term'])){
			$this->load->model('autocompletes', 'autocompletes');
			$query= $this->autocompletes->search_airport_place();
 
			foreach($query->result() as $row):
				$new_row['label']=htmlentities(stripslashes($row->airport_name." (".$row->airport_iata .") - ".$row->airport_city));
				$new_row['value']=htmlentities(stripslashes($row->airport_name." (".$row->airport_iata .") - ".$row->airport_city));
				$new_row['category']=htmlentities(stripslashes($row->airport_country));
				$row_set[] = $new_row;
			endforeach;
			
			echo json_encode($row_set);
			}
		}
	function airport_json(){
		if(isset($_POST['term'])){
			$this->load->model('autocompletes', 'autocompletes');
			$query= $this->autocompletes->get_all_airport_place();
 
			foreach($query->result() as $row):
				$new_row['label']=htmlentities(stripslashes($row->airport_name." ( ".$row->airport_iata ." ) - ".$row->airport_city));
				$new_row['value']=htmlentities(stripslashes($row->airport_name." ( ".$row->airport_iata ." ) - ".$row->airport_city));
				$new_row['iata']=htmlentities(stripslashes($row->airport_iata));
				$new_row['category']=htmlentities(stripslashes($row->airport_country));
				$row_set[] = $new_row;
			endforeach;
			
			echo json_encode($row_set);
		}
	}
	function airport_json_v2(){
		if(isset($_POST['term'])){
			$this->load->model('autocompletes', 'autocompletes');
			$query= $this->autocompletes->get_all_airport_place_v2();
 
			foreach($query->result() as $row):
				$new_row['label']=htmlentities(stripslashes($row->airport_cityairport." ( ".$row->airport_code ." )"));
				$new_row['value']=htmlentities(stripslashes($row->airport_cityairport." (".$row->airport_code .") - ".$row->airport_country));
				$new_row['iata']=htmlentities(stripslashes($row->airport_code));
				$new_row['category']=htmlentities(stripslashes($row->airport_country));
				$row_set[] = $new_row;
			endforeach;
			
			echo json_encode($row_set);
		}
	}
}
?>