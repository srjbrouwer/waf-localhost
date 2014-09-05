<?php
class Autocompletes extends CI_Model {
     function search($table_name,$table_search_field,$table_result_field) {
          $this->db->select($table_result_field);
          $this->db->like($table_search_field, $this->input->post('term'), 'both'); 
          return $this->db->get($table_name, 10);  
     }
     function search_airport_place(){
		$this -> db -> select('airport_name, airport_city, airport_iata, airport_icao, airport_country');
   		$this -> db -> from('airports');
   		if(strlen($this->input->post('term'))<=3){
			$this -> db -> where('(airport_iata LIKE "%'.$this->input->post('term').'%") AND airport_iata is not null AND TRIM(airport_iata) <> "" AND airport_icao is not null'); 
		}else{
			$this -> db -> where('(airport_city LIKE "'.$this->input->post('term').'%" OR airport_iata LIKE "%'.$this->input->post('term').'%" OR airport_name LIKE "%'.$this->input->post('term').'%") AND airport_iata is not null AND TRIM(airport_iata) <> "" AND airport_icao is not null'); 
		}
		$this -> db -> order_by('airport_name');
		$this -> db ->limit(10);
		
		return $this -> db -> get();
     }
     function get_all_airport_place(){
		$this -> db -> select('airport_name, airport_city, airport_iata, airport_icao,airport_country');
   		$this -> db -> from('airports');
		$this -> db -> where('airport_iata is not null AND TRIM(airport_iata) <> "" AND airport_icao is not null'); 
		$this -> db -> order_by('airport_city');
		//$this -> db ->limit(10);
		
		return $this -> db -> get();
     }
     function get_all_airport_place_v2(){
		$this -> db -> select('airport_cityairport,airport_country, airport_code');
   		$this -> db -> from('airports_v2');
		//$this -> db -> where('airport_iata is not null AND TRIM(airport_iata) <> "" AND airport_icao is not null'); 
		$this -> db -> order_by('airport_country, airport_cityairport');
		//$this -> db ->limit(10);
		
		return $this -> db -> get();
     }
}