<?php
/*
Name:   crv.class.php
Author: Brouwer, S.R.J.

Concept Relationship Variable Class
*/
class Crv{
	//define public variables
	//database settings
	public $a_db_hostname;
	public $a_db_username;
	public $a_db_password;
	public $a_db_name;
	public $a_db;
	//session_id
	public $sessionId;
	//output of complete request
	public $output;
	//---------------[concept, relations, var RegEx]--------------
	public $a_regex = "/[a-zA-Z0-9\/]+/";
	public $a_regex_concept_id = "/[0-9]+/";
	public $a_regex_concept_parent_id = "/[0-9]+/";
	public $a_regex_concept_name = "/[a-zA-Z\/]+[0-9a-zA-Z\/]*/";
	public $a_regex_var_id = "/[0-9]+/";
	public $a_regex_var_name = "/[a-zA-Z]+[0-9a-zA-Z]*/";
	public $a_regex_var_inheritance = "/[0,1]{1}/";
	public $a_regex_relationship_id = "/[1-9]{1}[0-9]*/";
	public $a_regex_relationship_weight = "/[0-9\.\-]+/";
	public $a_regex_var_value = "/.*/";
	public $a_regex_var_weight = "/[0-9\.\-]+/";
	public $a_regex_var_global = "/[0-1]{1}/";
	public $a_regex_var_option = "/[0-6]{1}/";
	//----[log]---
	public $a_log;
	
    function __construct($db_arr,$sessionId){
    	//cache queries
    	ini_set('mysqlnd_qc.enable_qc', 1);
    	ini_set('mysqlnd_qc.cache_by_default', 1);
		
		//set global sessionID
	    $this->sessionId    =   $sessionId;
	    
	    //set database parameters
	    $this->a_db_hostname  	=   $db_arr['hostname'];
	    $this->a_db_username  	=   $db_arr['username'];
	    $this->a_db_password 	    =   $db_arr['password'];
	    $this->a_db_name			=   $db_arr['name'];
	     
	    $this->a_db = new mysqli($this->a_db_hostname, $this->a_db_username, $this->a_db_password, $this->a_db_name);
	}
	//---------------------[Logging]-------------------
	function a_log_write($msg, $subject=''){
		//$callers=debug_backtrace();
		//$subject = $callers[1]['function'];
		
		//already something written
		if(strlen($this->a_log)){
			$this->a_log .= '<br />'.date("YmdHims").' '.$subject.' - '.$msg;		
		}else{
			$this->a_log .= date("YmdHims").' '.$subject.' - '.$msg;
		}
		return;
	}
    //-------------------------------------[Concepts]-----------------------------
	function get_a_concept($concept){
		if(is_numeric($concept)){
			$query = $this->a_db->query("SELECT * FROM a_concepts WHERE concept_id=".$concept);
		}else{
			$query = $this->a_db->query("SELECT * FROM a_concepts WHERE concept_name='".$concept."'");
		}
		$result = false;
		if($row = $query->fetch_assoc()){
			$result = $row;
		}else{
			$this->a_log_write('Not found');
		}
		return $result;
	}
	function get_a_concepts(){
		$query = $this->a_db->query("SELECT * FROM a_concepts");
		$result = array();
		while($row = $query->fetch_assoc()){
			$result[] = $row;
		}
		return $result;
	}
	function create_a_concept($concept_parent_id=0, $concept_name){
		$result = false;
		
		if ((preg_match($this->a_regex_concept_name, $concept_name)) && (preg_match($this->a_regex_concept_parent_id, $concept_parent_id)) ){
			if($query = $this->a_db->query("INSERT INTO a_concepts (concept_parent_id, concept_name) VALUES (".$concept_parent_id.", '".$concept_name."')")){
			    $result = true;
				$this->a_log_write('Insert succeeded, id='.$this->a_db->insert_id);
			}else{
				$this->a_log_write('Insert failed, '.$this->a_db->error);
			}
		}else{
			$this->a_log_write('Invalid input');
		}
		return $result;
	}
	function update_a_concept($search, $concept_parent_id=0, $concept_name){
		$result = false;
		
		if (preg_match($this->a_regex, $search)) {
			$concept_search = $this->get_a_concept($search);
			if(isset($concept_search['concept_id'])){
				if ((preg_match($this->a_regex_concept_name, $concept_name)) && (preg_match($this->a_regex_concept_parent_id, $concept_parent_id)) ){
					if($query = $this->a_db->query("UPDATE a_concepts SET concept_parent_id = ".$concept_parent_id.", concept_name = '".$concept_name."' WHERE concept_id = ".$concept_search['concept_id'])){
					    $result = true;
						$this->a_log_write('Update succeeded');
					}else{
						$this->a_log_write('Update failed, '.$this->a_db->error);
					}
				}else{
					$this->a_log_write('Invalid input');
				}
			}else{
				$this->a_log_write('Not found, unable to update');
			}
		}else{
			$this->a_log_write('Invalid search');
		}
		return $result;
	}
	function remove_a_concept($search){
		$result = false;
		
		if (preg_match($this->a_regex, $search)) {
			$concept_search = $this->get_a_concept($search);
			if(isset($concept_search['concept_id'])){
				if($query = $this->a_db->query("DELETE FROM a_concepts WHERE concept_id = ".$concept_search['concept_id'])){
                    $result = true;
					$this->a_log_write('Delete succeeded');
				}else{
					$this->a_log_write('Delete failed, '.$this->a_db->error);
				}
			}else{
				$this->a_log_write('Not found, unable to delete');
			}
		}else{
			$this->a_log_write('Invalid search');
		}
		return $result;
	}
	function childs_a_concept($concept_id){
		$query = $this->a_db->query("SELECT * FROM a_concepts WHERE concept_parent_id=".$concept_id." AND concept_parent_id<>concept_id");
		$result = array();
		while($row = $query->fetch_assoc()){
			$result[] = $row;
		}
		return $result;
	}
	//-------------------------------------[Relationships]-----------------------------
	function get_a_relationship($relationship){
		$query = $this->a_db->query("SELECT * FROM a_relationships WHERE relationship_id='".$relationship."'");
		$result = array();
		if($row = $query->fetch_assoc()){
			$result = $row;
		}else{
		    $result = false;
			$this->a_log_write('Not found');
		}
		return $result;
	}
	function childs_a_relationships($parent_concept_id, $parent_var_name){
	    $result = array();

	    $query = $this->a_db->query("SELECT * FROM a_relationships WHERE parent_concept_id=".$parent_concept_id." AND parent_var_name='".$parent_var_name."'");
	    
	    while($row = $query->fetch_assoc()){
			$result[] = $row;
		}
	    
	    return $result;
	}
	function get_a_relationships(){
		$query = $this->a_db->query("SELECT * FROM a_relationships");
		$result = array();
		while($row = $query->fetch_assoc()){
			$result[] = $row;
		}
		return $result;
	}
	function create_a_relationship($parent_concept_id, $parent_var_name, $child_concept_id, $child_var_name, $relationship_weight=1){
		$result = false;

		if ((preg_match($this->a_regex_concept_id, $parent_concept_id)) && (preg_match($this->a_regex_concept_id, $child_concept_id)) && (($parent_concept_id<>$child_concept_id) ||($parent_var_name<>$child_var_name)) && (preg_match($this->a_regex_relationship_weight, $relationship_weight)) && (preg_match($this->a_regex_var_name, $parent_var_name)) && (preg_match($this->a_regex_var_id, $child_var_name)) ){
			if($query = $this->a_db->query("INSERT INTO a_relationships (parent_concept_id, parent_var_name, child_concept_id, child_var_name, relationship_weight) VALUES (".$parent_concept_id.", '".$parent_var_name."', ".$child_concept_id.", '".$child_var_name."', '".$relationship_weight."')")){
			    $result = true;
				$this->a_log_write('Insert succeeded, id='.$this->a_db->insert_id);
			}else{
				$this->a_log_write('Insert failed, '.$this->a_db->error);
			}
		}else{
			$this->a_log_write('Invalid input');
		}
		return $result;
	}
	function update_a_relationship($search, $parent_concept_id, $parent_var_name, $child_concept_id, $child_var_name, $relationship_weight=1){
		$result = false;
		
		if (preg_match($this->a_regex, $search)) {
			$relationship_search = $this->get_a_relationship($search);
			if(isset($relationship_search['relationship_id'])){
				if ((preg_match($this->a_regex_concept_id, $parent_concept_id)) && (preg_match($this->a_regex_concept_id, $child_concept_id)) && (($parent_concept_id<>$child_concept_id) ||($parent_var_name<>$child_var_name)) && (preg_match($this->a_regex_relationship_weight, $relationship_weight)) && (preg_match($this->a_regex_var_name, $parent_var_name)) && (preg_match($this->a_regex_var_name, $child_var_name)) ){
					if($query = $this->a_db->query("UPDATE a_relationships SET parent_concept_id = ".$parent_concept_id.", parent_var_name = '".$parent_var_name."', child_concept_id = ".$child_concept_id.", child_var_name='".$child_var_name."', relationship_weight='".$relationship_weight."' WHERE relationship_id = ".$relationship_search['relationship_id'])){
						$result = true;
						$this->a_log_write('Update succeeded');
					}else{
						$this->a_log_write('Update failed, '.$this->a_db->error);
					}
				}else{
					$this->a_log_write('Invalid input');
				}
			}else{
				$this->a_log_write('Not found, unable to update');
			}
		}else{
			$this->a_log_write('Invalid search');
		}
		return $result;
	}
	function remove_a_relationship($search){
		$result = false;
		
		if (preg_match($this->a_regex_relationship_id, $search)) {
			$relationship_search = $this->get_a_relationship($search);
			if(isset($relationship_search['relationship_id'])){
				if($query = $this->a_db->query("DELETE FROM a_relationships WHERE relationship_id = ".$relationship_search['relationship_id'])){
				    $result = true;
					$this->a_log_write('Delete succeeded');
				}else{
					$this->a_log_write('Delete failed, '.$this->a_db->error);
				}
			}else{
				$this->a_log_write('Not found, unable to delete');
			}
		}else{
			$this->a_log_write('Invalid search');
		}
		return $result;
	}	
	//-------------------------------------[Variables]-----------------------------
	function get_a_vars(){
		$query = $this->a_db->query("SELECT * FROM a_vars");
		$result = array();
		while($row = $query->fetch_assoc()){
			$result[] = $row;
		}
		return $result;
	}
	function get_a_vars_by_concept($concept_name, $global=0, $option=0){
		/*
			Retrieve all vars of a specific concept with their values
		*/
		//always sent empty array
		$result = false;
		//get concept
		$concept = $this->get_a_concept($concept_name);

		if($concept){
			//conceptId retrieved - Get all vars
			$query = $this->a_db->query("SELECT * FROM a_vars WHERE concept_id=".$concept['concept_id']);
			//set vars array
			$vars = array();
			//loop all returned rows
			while($row = $query->fetch_assoc()){
				$vars[]= $row;
			}
			
			//get all values of the specific rows and set in the result array
			foreach($vars as &$var){
				//if inheritance, calculate value else return
				
				$value	=	$this->get_a_var_val($var['var_name'],$concept['concept_name'],$global,$option,$arr_var_val);
				$var['var_calc_arr'] = $arr_var_val;
				$var['var_value'] = $value['value'];
				$var['var_weight']	= $value['weight'];
			}
			$result = $vars;
		}
		return $result;
	}
	function create_a_var($var_name, $var_inheritance=0, $concept_id, $var_weight=1, $global=0){
		$result = false;
		
		if ((preg_match($this->a_regex_concept_id, $concept_id)) && (preg_match($this->a_regex_var_name, $var_name)) && (preg_match($this->a_regex_var_inheritance, $var_inheritance)) && (preg_match($this->a_regex_var_weight, $var_weight)) ){

			if($query = $this->a_db->query("INSERT INTO a_vars (concept_id, var_name,var_inheritance, var_weight) VALUES (".$concept_id.",  '".$var_name."', ".$var_inheritance.", ".$var_weight.")")){
				$this->a_log_write('Insert succeeded, id='.$this->a_db->insert_id);
				$result = true;
			}else{
				$this->a_log_write('Insert failed, '.$this->a_db->error);
			}
		}else{
			$this->a_log_write('Invalid input');
		}
		return $result;
	}
	function update_a_var($var_id, $var_name,$var_inheritance=0, $concept_id, $var_weight=1){
		$result = false;
		
		if ((preg_match($this->a_regex_concept_id, $concept_id)) && (preg_match($this->a_regex_var_name, $var_name)) && (preg_match($this->a_regex_var_inheritance, $var_inheritance)) && (preg_match($this->a_regex_var_weight, $var_weight)) ){
			if($query = $this->a_db->query("UPDATE a_vars SET concept_id = ".$concept_id.", var_name='".$var_name."', var_inheritance=".$var_inheritance.", var_weight = ".$var_weight." WHERE var_id= ".$var_id)){
				if($this->a_db->affected_rows>0){
					$result = true;
					$this->a_log_write('Update succeeded');
				}else{
					$this->a_log_write('Update failed or not necessary');
				}
			}else{
				$this->a_log_write('Update failed, '.$this->a_db->error);
			}
		}else{
			$this->a_log_write('Invalid input');
		}
		return $result;
	}
	function set_a_var_value($var_name, $concept_id, $var_value, $global=0, $var_weight=1, $var_inheritance=0){
		$result = false;
		//check var value exists
		$var_search = $this->get_a_var($var_name, $concept_id, $global);
		
		if(!$var_search){
			//insert
			$result= $this->create_a_var($var_name,$var_inheritance, $concept_id, $var_weight);
			if($result){
				//again
				$result = $this->set_a_var_value($var_name, $concept_id, $var_value, $global, $var_weight, $var_inheritance);
			}
		}else{
			//set var value
			$result = $this->set_a_var_val($var_search['var_id'],$var_value,$global);
		}
	
		return $result;
	}
	function get_a_var_by_id($var_id){
		$result = false;
		if (preg_match($this->a_regex_var_id, $var_id)){
			$query = $this->a_db->query("SELECT * FROM a_vars WHERE var_id=".$var_id);
			if($row = $query->fetch_assoc()){
				$result = $row;
			}else{
				$this->a_log_write('Not found');
			}
		}else{
			$this->a_log_write('Invalid input');
		}
		return $result;	       
	}
	function get_a_var($var_name, $concept_id){
		$result = false;
		if ((preg_match($this->a_regex_concept_id, $concept_id)) && (preg_match($this->a_regex_var_name, $var_name)) ){
			$query = $this->a_db->query("SELECT * FROM a_vars WHERE var_name='".$var_name."' AND concept_id=".$concept_id);
			if($row = $query->fetch_assoc()){
				$result = $row;
			}else{
				$this->a_log_write('Not found');
			}
		}else{
			$this->a_log_write('Invalid input');
		}
		return $result;
	}
	function remove_a_var($var_id){
		$result = false;
		if (preg_match($this->a_regex_var_id, $var_id)) {
			if($query = $this->a_db->query("DELETE FROM a_vars WHERE var_id=".$var_id)){
				if($this->a_db->affected_rows>0){
				    $result = true;
					$this->a_log_write('Delete succeeded');
				}else{
					$this->a_log_write('Delete failed');
				}
			}else{
				$this->a_log_write('Delete failed, '.$this->a_db->error);
			}
		}else{
			$this->a_log_write('Invalid input');
		}
		return $result;
	}
	//-------------------------------[REMOVE var values]------------------------------------
	function remove_a_var_val($var_id,$global){
		$result = false;
		if ( (preg_match($this->a_regex_var_id, $var_id)) && (preg_match($this->a_regex_var_global, $global)) ){
			$query_session = ($global ? 0 : $this->sessionId);
			if($query = $this->a_db->query("DELETE FROM a_vars_values WHERE session_id='".$query_session."' AND var_id=".$var_id)){
				if($this->a_db->affected_rows>0){
				    $result = true;
					$this->a_log_write('Delete succeeded');
				}else{
					$this->a_log_write('Delete failed');
				}
			}else{
				$this->a_log_write('Delete failed, '.$this->a_db->error);
			}
		}else{
			$this->a_log_write('Invalid input');
		}
		return $result;
	}
	//-------------------------------[SET var values]------------------------------------
	function set_a_var_val($var_id,$var_value,$global=0){
		$result = false;
		if ( (preg_match($this->a_regex_var_id, $var_id)) && (preg_match($this->a_regex_var_value, $var_value))&& (preg_match($this->a_regex_var_global, $global)) ){
			$query_session = ($global ? 0 : $this->sessionId);
			if($query = $this->a_db->query("SELECT var_value FROM a_vars_values WHERE session_id='".$query_session."' AND var_id=".$var_id)){
				if($row = $query->fetch_assoc()){
					//update
					$result = $this->update_a_var_val($var_id,$var_value,$global);
				}else{
					//add
					$result = $this->add_a_var_val($var_id,$var_value,$global);
				}
			}else{
				$this->a_log_write('Get var value failed, '.$this->a_db->error);
			}
		}else{
			$this->a_log_write('Invalid input');
		}
		return $result;
	}
	//-------------------------------[ADD var values]------------------------------------
	function add_a_var_val($var_id,$var_value,$global=0){
		$result = false;
		if ( (preg_match($this->a_regex_var_id, $var_id)) && (preg_match($this->a_regex_var_value, $var_value))&& (preg_match($this->a_regex_var_global, $global)) ){
			$query_session = ($global ? 0 : $this->sessionId);
			if($query = $this->a_db->query("INSERT INTO a_vars_values (var_id, session_id, var_value) VALUES (".$var_id." , '".$query_session."', '".$var_value."')")){
				$this->a_log_write('Insert succeeded, id='.$this->a_db->insert_id);
				$result = true;
			}else{
				$this->a_log_write('Insert var value failed, '.$this->a_db->error);
			}
		}else{
			$this->a_log_write('Invalid input');
		}
		return $result;
	}
	//-------------------------------[UPDATE var values]------------------------------------
	function update_a_var_val($var_id,$var_value,$global=0){
		$result = false;

		if ( (preg_match($this->a_regex_var_id, $var_id)) && (preg_match($this->a_regex_var_value, $var_value))&& (preg_match($this->a_regex_var_global, $global)) ){
			$query_session = ($global ? 0 : $this->sessionId);
			if($query = $this->a_db->query("UPDATE a_vars_values SET var_value='".$var_value."' WHERE session_id='".$query_session."' AND var_id=".$var_id)){
				$this->a_log_write('Update succeeded');
				$result = true;
			}else{
				$this->a_log_write('Update var value failed, '.$this->a_db->error);
			}
		}else{
			$this->a_log_write('Invalid input');
		}
		return $result;
	}
	//-------------------------------[GET var values]------------------------------------
	function get_a_var_val_by_id($var_id,$global=0){
		$result = false;
		if ( (preg_match($this->a_regex_var_id, $var_id)) && (preg_match($this->a_regex_var_global, $global)) ){
			$query_session = ($global ? 0 : $this->sessionId);
			if($query = $this->a_db->query("SELECT var_value FROM a_vars_values WHERE session_id='".$query_session."' AND var_id=".$var_id)){
				if($row = $query->fetch_assoc()){
					$result = $row;
				}else{
					$this->a_log_write('Get var value not found');
				}
			}else{
				$this->a_log_write('Get var value failed, '.$this->a_db->error);
			}
		}else{
			$this->a_log_write('Invalid input');
		}
		return $result['var_value'];
	}
    function get_a_var_val($var_name, $concept_name, $global=0, $option=0, &$arr_var_val=array()){
        $result = array();
        $result['value'] = '';
        $result['weight'] = 0;
        /*option
            0 = default; value including possible relations and weight
            1 = Weighted Average
            2 = from average; take global + specific score
            3 = from avaerag; take global + specific score weighted
            4,5 = 0,1 with rounding, not used in this function
            6 = ignore inheritance
        */
        //check input
        if ((preg_match($this->a_regex_concept_name, $concept_name)) && (preg_match($this->a_regex_var_name, $var_name)) && (preg_match($this->a_regex_var_global, $global)) && (preg_match($this->a_regex_var_option, $option)) ){
            //get concept
            $concept = $this->get_a_concept($concept_name);
            if(!$concept){
                $this->a_log_write('Get concept failed');
                //exit
                return $result;
            }
            //get var
            $var = $this->get_a_var($var_name, $concept['concept_id']);
           
            if(!$var){
                $this->a_log_write('No (child)var within concept');
                //exit
                return $result;
            }
            
            //inheritated and not option 6
            if($var['var_inheritance'] && $option!=6){
                $value_arr = array();
                $weight_arr = array();
                
                //initiate vars
                //$value_arr[] = '';
                //$weight_arr[] = 0;
                
                //if parent has value and weight
                if($var['var_parent_value']){
                	$parent_value = $this->get_a_var_val_by_id($var['var_id'],$global);
                	if(strlen($parent_value)){
						$value_arr[] = $parent_value;
						$weight_arr[] = $var['var_weight'];
                	}
                }
                
                //get parent childs
                $childConcepts = $this->childs_a_concept($concept['concept_id']);
                foreach($childConcepts as $childConcept){
                    if($childConcept['concept_id']!=$concept['concept_id']){
                        //get child variable value if exists
                        $child_var = $this->get_a_var_val($var_name,$childConcept['concept_name'], $global, $option,  $arr_var_val);
                        if(!$child_var['weight']==0){
                            if(is_numeric($child_var['value'])){
                                $value_arr[] = $child_var['value'];
                                $weight_arr[] = $child_var['weight'];                            
                            }else{
                                $this->a_log_write('Child value not numeric:'.$childConcept['concept_name'].'-'.$var_name);
                            }
                        }else{
                            $this->a_log_write('Child with zero weight or does not exist:'.$childConcept['concept_name']);
                        }
                    }else{
                        $this->a_log_write('Loop on concept:'.$concept['concept_id']);
                    }
                }
                //get relationships
                foreach($this->childs_a_relationships($concept['concept_id'],$var['var_name']) as $childRelationship){
                    if(($childRelationship['child_concept_id']!=$concept['concept_id'])||($childRelationship['child_var_name']!=$var['var_name'])){
                        //get concept of child relationship
                        $childRelationshipConcept = $this->get_a_concept($childRelationship['child_concept_id']);
                        if($childRelationshipConcept){
                        	$childRelationshipVar = $this->get_a_var($childRelationship['child_var_name'], $childRelationship['child_concept_id'], 0);
                            if($childRelationshipVar){
                                //get child variable value if exists and belongs to same concept
                                if($childRelationshipVar['concept_id']==$childRelationship['child_concept_id']){
                                    $child_var = $this->get_a_var_val($childRelationshipVar['var_name'],$childRelationshipConcept['concept_name'], $global, $option, $arr_var_val);
                                    if(!$child_var['weight']==0){
                                        if(is_numeric($child_var['value'])){
                                            $value_arr[] = $child_var['value'];
                                            $weight_arr[] = $child_var['weight'] * $childRelationship['relationship_weight'];                         
                                        }else{
                                        	$log_child_var = (isset($child_var['var_name']) ? $child_var['var_name'] : '');
                                            $this->a_log_write('Child value not numeric:'.$childRelationshipConcept['concept_name'].'-'.$log_child_var);
                                        }
                                    }else{
                                    	$log_child_var = (isset($child_var['var_name']) ? $child_var['var_name'] : '');
                                        $this->a_log_write('Child with zero weight:'.$childRelationshipConcept['concept_name'].'-'.$log_child_var);
                                    }
                                }else{
                                    $this->a_log_write('Child concept and var dont match/belong:'.$childRelationship['child_concept_id'].'-'.$childRelationship['child_var_name']);
                                }
                            }else{
                                $this->a_log_write('Child var not found:'.$childRelationship['child_concept_id'].'-'.$childRelationship['child_var_name']);
                            }
                        }else{
                            $this->a_log_write('Child concept not found:'.$childRelationship['child_concept_id'].'-'.$childRelationship['child_var_name']);
                        }
                    }else{
                        $this->a_log_write('Loop on relationship:'.$concept['concept_id']);
                    }
                }
                
                //calculate resutl depending on option
                /*
                    2 = global * weight + case 0
                    3 = global * weight  + case 1
                */
                if($option==2){
                    $global_value = $this->get_a_var_val($var_name, $concept_name, 1, 0);
                    $value_arr[] = $global_value['value'];
                    $weight_arr[] = $global_value['weight'];
                    $option = $option-2;
                }elseif($option==3){
                	//global is already weighted
                	$global_value = $this->get_a_var_val($var_name, $concept_name, 1, 0);
                    $value_arr[] = $global_value['value'];
                    $weight_arr[] = 1;
                    $option = $option-2;
                }
                switch($option){
                    case 0:
                        //default
                        foreach($value_arr as $key=>$value){
                            $result['value'] = $result['value'] + ($value * $weight_arr[$key]);
                        }
                        //end value
                        $result['value'] = $result['value'];
                        //weight of parent var
                        $result['weight'] = $var['var_weight'];
                        break;
                    case 1:
                        //wa
                        //sum of weight
                        $weight_sum = 0;
                        foreach($value_arr as $key=>$value){
                            $result['value'] = $result['value'] + ($value * $weight_arr[$key]);
                            $weight_sum += $weight_arr[$key];
                        }
                        //end value
                        $weight_sum = ($weight_sum == 0 ? 1 : $weight_sum);
                        $result['value'] = $result['value'] / $weight_sum;
                        //weight of parent var
                        $result['weight'] = $var['var_weight'];
                        break;
                }
                //add to value array if parent has it's own value
                if($var['var_parent_value']){
                    $arr_var_val[] = array(
                            'weight' => $result['weight'],
                            'value' => $result['value'],
                            'var_name' => $var_name,
                            'concept_name' => $concept_name
                        );
            	}
            }else{
                $result['value'] = $this->get_a_var_val_by_id($var['var_id'],$global);
                $result['weight'] = $var['var_weight'];
                //set array list of variable
                $arr_var_val[] = array(
	                'weight' => $var['var_weight'],
                	'value' => $result['value'],
                	'var_name' => $var_name,
            		'concept_name' => $concept_name
            	);
            }
        }else{
            $this->a_log_write('Invalid input');
        }

        return $result;
    }
}
?>