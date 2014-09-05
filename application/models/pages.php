<?php
Class Pages extends CI_Model
{
	 function get_index()
	 {
		   $this -> db -> select('page_id, page_title');
		   $this -> db -> from('pages');

		   $query = $this -> db -> get();

			return $query->result();
	}
	function get_page($page_id)
	 {
		   $this -> db -> select('page_id, page_name, acl_id, page_title, page_text');
		   $this -> db -> from('pages');
		   $this -> db -> where('page_id', $page_id);

		   $query = $this -> db -> get();

			return $query->result();
	}
	function get_page_content($page_id)
	 {
		   $this -> db -> select('page_text');
		   $this -> db -> from('pages');
		   $this -> db -> where('page_id', $page_id);

		   $query = $this -> db -> get();

			return $query->result();
	}
	function get_page_meta($page_name){
		   $this -> db -> select('page_id, page_name, acl_id, page_title');
		   $this -> db -> from('pages');
		   $this -> db -> where('page_name', $page_name);

		   $query = $this -> db -> get();

			return $query->result();		
	}
}
?>
