<?php
/*
Name:   crv.class.php
Author: Brouwer, S.R.J.

Adaptation Class
*/
class Adapt{
    //define public variables
	//database settings
	public $a_db_hostname='localhost';
	public $a_db_username='root';
	public $a_db_password='';
	public $a_db_name='waf';
	public $a_db;
	//homepage set
	public $home_page = 'page/home';
	//----[BEGIN php client session information]------
	public $sessionId;
	public $ip_addr;
	public $requested_page;
	public $requested_page_array;
	//----[END php client session information]------
	public $a_globals;
	//output from framework
	public $output;
	//crv class
	public $crv;
	//public benchmark vars
	public $a_benchmark;
	public $start_time;
	public $end_time;
	//cache object
	public $data_object_cache = array();
	public $data_var_calc_arr = array();
	
	function __construct($output){
		//start benchmark
		$this->start_time = microtime(TRUE);
		//get current session
		session_start();
		$this->sessionId = session_id();
		$this->ip_addr = $_SERVER['REMOTE_ADDR'];
		$this->requested_page = uri_string();
		$this->requested_page = ((strlen($this->requested_page)==0) ? $this->home_page : $this->requested_page);
		$this->requested_page_array = explode("/",$this->requested_page);
		//if no page, set to /
		
		//set output
		$this->output = $output;
		
		//database settings
		//-Test Database connection
		$this->a_db = new mysqli($this->a_db_hostname, $this->a_db_username, $this->a_db_password, $this->a_db_name);
		
		if($this->a_db->connect_errno > 0){
    		echo $this->output = ('<div style="z-index: 99999; position: fixed; width: 100%; bottom: 0; padding: 10px; background: rgb(212, 210, 210);">Unable to connect to database [' . $this->db->connect_error . ']<br />Please check database settings in the adaptation Class</div>') . $this->output;
    		exit;
		}
		
		//database array
		$db_arr = array(
			'hostname'=>$this->a_db_hostname,
			'username'=>$this->a_db_username,
			'password'=>$this->a_db_password,
			'name'=>$this->a_db_name
		);
		
		//connect with relation model concept class
		require_once('crv.class.php');
		$this->crv = new Crv($db_arr, $this->sessionId);
		//fetch global adaptation settings
		$this->a_globals = $this->get_a_globals();
		//clean-up
		if($this->a_globals['clean_auto'] && (time()-strtotime($this->a_globals['clean_last']))>360 ){
			$this->clean_adaptation_data();
		}
	}
	function run(){
		$result='';
		//get page adaptation data, only apply to page and file URI
		$a_array = array('page','/','file','apage');
		if(in_array($this->requested_page_array[0],$a_array)){
			if($this->requested_page_array[0] == 'page'){
				$this->do_adaptation($this->a_globals['startup']);
			}
			$result = $this->do_adaptation($this->output);
			if($this->a_globals['log']){
				$result .= "<div style='border: 1px dashed #da0000; background:white; font-family:verdana; font-size:12px;'>".$this->crv->a_log."</div>";
			}
			//benchmark post result
			$this->end_time = microtime(TRUE);
			if($this->a_globals['benchmark']){
				$loading_time = $this->end_time - $this->start_time;
				$this->a_benchmark_write("Adaptation loading time:".$loading_time);
				$result .=  "<div style='width:100%;border:1px solid red; padding:5px;background:white;'> " . $this->a_benchmark . "</div>";
			}
		}elseif($this->requested_page_array[0]=='au'){
			$result = $_POST['term'];
		}else{
			$result = $this->output;
		}
		return $result;
	}
	//---------------------[Benchmark]-----------------------------------------------------
	function a_benchmark_write($msg, $subject=''){
		//$callers=debug_backtrace();
		//$subject = $callers[1]['function'];
		
		//already something written
		if(strlen($this->a_benchmark)){
			$this->a_benchmark .= '<br />'.date("YmdHims").' '.$subject.' - '.$msg;		
		}else{
			$this->a_benchmark .= date("YmdHims").' '.$subject.' - '.$msg;
		}
		return;
	}
	//---------------------[Request the global adaptation variables]---------------------
	function get_a_globals(){
		$query = $this->a_db->query("SELECT * FROM a_globals");
		$row = $query->fetch_assoc();
		return $row;
	}
	
	//-------------------[remove quotes[----------------------------
	function remove_quotes($input){
		$input = html_entity_decode($input,ENT_QUOTES);
		$search_arr = array('\'','"',htmlentities('\''),htmlentities('"'));
		$output = str_replace($search_arr,'',$input);
		return $output;
	}
	//--------------------------------------------------[CLEAN ADAPTATION  DATA]-------------------------------
	function clean_adaptation_data(){
		//clean visitor analysis
		if($this->a_globals['clean_toglobal']){
            $query = $this->a_db->query("SELECT var_id, AVG(var_value) as 'var_value', count(*) as 'weight' FROM a_vars_values as a, a_globals as b WHERE (TIMESTAMPDIFF(SECOND, a.time, NOW()) > b.clean_expire) AND concat('',var_value * 1) = var_value AND session_id <> '0' GROUP BY var_id");
            if($query){
                //get all global vars
                $query_global_var_values = $this->a_db->query("SELECT * FROM a_vars_values WHERE session_id='0' AND concat('',var_value * 1) = var_value");
                $global_var_ids = array();
                $global_var_values = array();
                while($row = $query_global_var_values->fetch_assoc()){
                    $global_var_ids[] = $row['var_id'];
                    $global_var_values[$row['var_id']]['value'] = $row['var_value'];
                    $global_var_values[$row['var_id']]['weight'] = $row['global_weight'];
                }
                $result = array();
                while($row = $query->fetch_assoc()){
                    //does the global variable value exist
                    if(in_array($row['var_id'], $global_var_ids)){
                        //calculate new value
                        $weight = $row['weight'] + $global_var_values[$row['var_id']]['weight'];
                        $value = (($row['weight'] * $row['var_value']) + ($global_var_values[$row['var_id']]['value'] * $global_var_values[$row['var_id']]['weight'])) / $weight;
                        $this->a_db->query("UPDATE a_vars_values SET var_value = ".$value.", global_weight = ".$weight." WHERE var_id = ".$row['var_id']." AND session_id='0'");
                    }else{
                        $this->a_db->query("INSERT INTO a_vars_values (var_id, session_id, var_value, global_weight) VALUES (".$row['var_id'].", 0, ".$row['var_value'].", ".$row['weight'].")");
                    }
                }
            }
		}
		$this->a_db->query("DELETE FROM a_vars_values WHERE (TIMESTAMPDIFF(SECOND, a_vars_values.time, NOW()) > ".$this->a_globals['clean_expire'].") AND session_id <> '0'");
		$this->a_db->query("UPDATE a_globals SET clean_last = now()");
	}
	function get_adaptation_page_data($concept,$global=0,$option=0,&$var_calc_arr = array()){	
		/*
		 retrieve concept
		*/
		//cache?
		$a_page_datavar= '/([0-9]*)[\;]{1}([0-9]*)[\;]{1}(\S*)/si';
		if(preg_match($a_page_datavar,$concept,$var_matches)){
			$global = (strlen($var_matches[1]) ? $var_matches[1] : $global);
			$option =  (strlen($var_matches[2]) ? $var_matches[2] : $option);
			$conceptGlobalOption = $concept;
			$concept = $var_matches[3];
		}else{
			$conceptGlobalOption = $concept;
		}
		if(isset($this->data_object_cache[$conceptGlobalOption])){
			//from cache
			
			$data_object = (object)$this->data_object_cache[$conceptGlobalOption];
			$var_calc_arr = $this->data_var_calc_arr[$conceptGlobalOption];
		}else{
		
			$get_concept	=	$this->crv->get_a_concept($concept);
			//concept not found //create concept?
			if(!$get_concept){
				//auto_create_concepts
				//-------------create----------------------
				if($this->a_globals['concept_create_auto']){
					$this->crv->create_a_concept(0,$concept);
					$conceptVars = array();
					
					//concept  created
					$conceptFound = true;
				}else{
					//concept not created
					$conceptFound = false;
				}
			}else{
				
				//concept found
				$conceptFound = true;
				//retrieve concept
				$conceptVars	=	$this->crv->get_a_vars_by_concept($concept,$global,$option);
			}

			//create databoject
			$data_object = new stdClass();
			
			//concept available?
			if($conceptFound){
				//vars to object
				foreach($conceptVars as $key=>$var){
					$data_object->$var['var_name'] = $var['var_value'];
					$var_calc_arr[$var['var_name']] = $var['var_calc_arr'];
				}
			
				//cache
				$this->data_object_cache[$conceptGlobalOption] = (array)$data_object;
				$this->data_var_calc_arr[$conceptGlobalOption] = $var_calc_arr;
			}
		}
		return $data_object;

	}
	//--------------------------------------------------[UPDATE ADAPTATION PAGE DATA]-------------------------------
	function update_adaption_page_data($data_object,$concept, $global=0, $vars_updated=array()){
		//concepts with options or global
		$a_page_datavar= '/([0-9]*)[\;]{1}([0-9]*)[\;]{1}(\S*)/si';
		if(preg_match($a_page_datavar,$concept,$var_matches)){
			$global = (strlen($var_matches[1]) ? $var_matches[1] : $global);
			$option =  (strlen($var_matches[2]) ? $var_matches[2] : $option);
			$conceptGlobalOption = $concept;
			$concept = $var_matches[3];
		}else{
			$conceptGlobalOption = $concept;
		}
		
		$data_arr	=	(array)$data_object;
		$concept_arr = $this->crv->get_a_concept($concept);

		if(!$concept_arr===false){
			foreach($data_arr as $var_name=>$value){
				//if update registered
				if(in_array($var_name,$vars_updated)){
					//get cache
					if(isset($this->data_object_cache[$conceptGlobalOption]->$var_name)){
						$data_object_cache = (object)$this->data_object_cache[$conceptGlobalOption];
						//create databoject
						//check if var needs to be updated
						if($data_object_cache->$var_name != $value){
							$result = $this->crv->set_a_var_value($var_name,$concept_arr['concept_id'],mysqli_real_escape_string($this->a_db,$value), $global);
						}
					}else{
						$data_object_cache = (object)$this->data_object_cache[$conceptGlobalOption];
						//set var				
						$result = $this->crv->set_a_var_value($var_name,$concept_arr['concept_id'],mysqli_real_escape_string($this->a_db,$value), $global);
					}
					//update cache 
					$data_object_cache->$var_name = $value;
					$this->data_object_cache[$conceptGlobalOption] = (array)$data_object_cache;
				}
			}
		}
		return;			
	}
	//---------------[lookup or get from data object]----
	function get_adaptation_var($input, $data_object, &$output='', $global=0, $option=0, &$arr_var_val = array()){
		$result=false;
		$a_var= '/([0-9]*)[\;]{0,1}([0-9]*)[\;]{0,1}(\S*)\$([0-9a-zA-Z]+)/si';
		if(preg_match($a_var,$input,$var_matches)){
				//get val of var
				$global = (strlen($var_matches[1]) ? $var_matches[1] : $global);
				$option =  (strlen($var_matches[2]) ? $var_matches[2] : $option);
				$concept = $var_matches[3];
				$var = $var_matches[4];
				
				//weighted average round functionality
				if(($option == 4)||($option == 5)){
					$option = $option-4;
					$round = true;
				}else{
					$round = false;
				}
				
				if( strlen($concept) || (($global+$option)>0) ){
					$val = ($this->crv->get_a_var_val($var, $concept, $global, $option, $arr_var_val));
					$val = $val['value'];
				}else{
					if(isset($data_object->$var)){
						$val = $data_object->$var;
					}else{
						$val = '';
						$this->crv->a_log_write("Check: ".htmlentities($a_var));
					}
				}
				//round?
				if($round){
					$output = round($val,2);
				}else{
					$output = $val;
				}
				$result = true;
		}
		return $result;
	}
	function do_adaptation($input,$for_page=false){
	    //output
	    $output = $input;
	    $css = "";
	    $js = "";
	    
	    //div index
	    $div_index=0;
	    
	    //matches
	    $a_global = '/\{([^\$\s]*?)(\$|\#)([0-9a-zA-Z]+)([\:]{0,1}[0-9]*)(\;[^\s]*?)([\%]{0,1}[0-9]*)\}(.*?)\{\/\g2(\g3)\g6\}|\{([^\$\s]*?)(\$|\#)([0-9a-zA-Z]+)([\:]{0,1}[0-9]*)([\S ]*?)\/\}/si';
	    $a_if = '/\{(\S*)\$([0-9a-zA-Z]+)\;(\-|\+|\=\=|\!\=|\<|\<\=|\>|\>\=|\<\>|\&gt\;|\&lt\;|\&gt\;\=|\&lt\;\=|\&lt\;\&gt\;)(\S*?\$[0-9a-zA-Z]+|[\"|\'|\&\#39\;][\S]*[\"|\'|\&\#39\;]|[0-9.]+)([\%]{0,1}[0-9]*)\}(.*?)\{\/\$(\g2\g5)\}/si';
	    $a_echo = '/\{([^\#\s]*?)\$([a-zA-Z0-9]+)\/\}/si';
	    $a_hashtag = '/\{\#([0-9a-zA-Z]+)\;(\S*?)\;(|\=\=|\!\=|\<|\>|\<\>|\&gt\;|\&lt\;|\&lt\;\&gt\;)(\S*?\$[0-9a-zA-Z]+|[0-9.\-]+)([\%]{0,1}[0-9]*)\}(.+?)\{\/\#(\g1\g5)\}/si';
	    $a_hashtag_short = '/\{\#([0-9a-zA-Z]+)\;(\S*?)\;(|\=\=|\!\=|\<|\>|\<\>|\&gt\;|\&lt\;|\&lt\;\&gt\;)(\S*?\$[0-9a-zA-Z]+|[0-9.\-]+)([\%]{0,1}[0-9]*)\/\}/si';
        $a_init = '/\{\S*?\$([0-9a-zA-Z]+)\;init\;([0-9.-]+|\S*?\$[0-9a-zA-Z]+|[\"|\'|\&\#39\;|\&quot\;][\S ]*?[\"|\'|\&\#39\;|\&quot\;])\;([\+\-\/\*][0-9.]+|[\+\-\/\*]\S*?\$[0-9a-zA-Z]+){0,1}\/\}/si';
        $a_set = '/\{[^}]*?\$([0-9a-zA-Z]+)\;set\;([0-9.-]+|\S*?\$[0-9a-zA-Z]+|[\"|\'|\&\#39\;|\&quot\;][\S ]*?[\"|\'|\&\#39\;|\&quot\;])\/\}/si';
        $a_var= '/(\S*)\$([0-9a-zA-Z]+)/si';
        $a_expression = '/\{\#expression\;(\S*?)\/\}/si';
        $a_top= '/\{(\S*?)\$([0-9a-zA-Z]+)\;top\;(\S*?)\;(\S*?);(\S*?)\/\}/si';
        //error variable
        $errors = "";

	    //global preg_match
	    $g_index=0;
	    //prevent duplicate lookups on page
	    $last_a_data_page = '';
	    while(preg_match_all($a_global, $output, $g_matches,PREG_PATTERN_ORDER))
	    {
			
			//start benchmark adaptation page data loading
			$start_time = microtime(TRUE);		
			if((isset($g_matches[1][$g_index]))||(isset($g_matches[9][$g_index]))){
				$a_data_page = ((strlen($g_matches[1][$g_index].$g_matches[9][$g_index])>0) ? $g_matches[1][$g_index].$g_matches[9][$g_index] : $this->requested_page);
			}else{
				$a_data_page = $this->requested_page;
			}
			if($last_a_data_page != $a_data_page){
				$var_calc_arr = array();
				$data_object = $this->get_adaptation_page_data($a_data_page,0,0,$var_calc_arr);
				$last_a_data_page = $a_data_page;
			}
			//process a page data to option and global variables if needed
			$global = 0;
			$option = 0;
			$a_data_page_concept = $a_data_page;
			$a_page_datavar= '/([0-9]*)[\;]{1}([0-9]*)[\;]{1}(\S+)/si';
			if(preg_match($a_page_datavar,$a_data_page,$var_matches)){
				$global = (strlen($var_matches[1]) ? $var_matches[1] : $global);
				$option =  (strlen($var_matches[2]) ? $var_matches[2] : $option);
				$a_data_page_concept = (strlen($var_matches[3]) ? $var_matches[3] : $a_data_page_concept);
			}
			
			$part = $g_matches[0][0];

			//end benchmark page data load loading
			$end_time = microtime(TRUE);
			$this->a_benchmark_write("Data object loading time:".($end_time - $start_time));
			
            switch(true){
            	case (preg_match_all($a_init, $part, $matches,PREG_PATTERN_ORDER) ? true : false):
            		//start benchmark
					$start_time = microtime(TRUE);
                    //loop all adaptations
                    $index = 0;
                    //keep track of updated vars
                    $vars_updated= array();

                    foreach($matches[1] as $a_func){
                        //get all functions and process
						
						//incremental value?
						$matches[3][$index] = (isset($matches[3][$index]) ? $matches[3][$index] : '');
						//already set?
						if(isset($data_object->$a_func)){

							if(is_numeric($data_object->$a_func) && strlen($matches[3][$index])){
								//get object data without inheritance
                    			$pure_var_val = $this->crv->get_a_var_val($a_func, $a_data_page_concept, $global, 6);
                    			$data_object->$a_func = $pure_var_val['value'];

								//numeric ? or float ?
								$increment_operator = substr($matches[3][$index],0,1);
								$increment_val = substr($matches[3][$index],1);
								if(is_numeric($data_object->$a_func) && (is_numeric($increment_val)) ){
									eval('$result = '.$data_object->$a_func.$increment_operator.$increment_val.';');
									$data_object->$a_func = $result;
									$vars_updated[] = $a_func;
								}elseif($this->get_adaptation_var($increment_val,$data_object,$increment_val)){
									//get val of var
									if(is_numeric($increment_val)){
										eval('$result = '.$data_object->$a_func.$increment_operator.$increment_val.';');
										$data_object->$a_func = $result;
										$vars_updated[] = $a_func;
									}
								}
							}elseif(!strlen($data_object->$a_func)){
								//data object exists but is an empty string
								$init_val = $matches[2][$index];
								$data_object->$a_func = $init_val;
								$vars_updated[] = $a_func;
							}
						}else{
							$init_val = $matches[2][$index];
							if(is_numeric($init_val)){
								$data_object->$a_func = $init_val;
								$vars_updated[] = $a_func;
							}elseif($this->get_adaptation_var($matches[2][$index],$data_object,$init_value)){
								//get val of var
								if(is_numeric($init_value)){
									$data_object->$a_func = $init_value;
									$vars_updated[] = $a_func;
								}
							}else{
								$init_val = $this->remove_quotes($init_val);
								$data_object->$a_func = $init_val;
								$vars_updated[] = $a_func;
							}
						}
						//remove init
						$part = str_replace($matches[0][$index],'',$part);

						//write back
						$this->update_adaption_page_data($data_object,$a_data_page,0,$vars_updated);

						//end benchmark
						$end_time = microtime(TRUE);
						$this->a_benchmark_write("Init loading time:".($end_time - $start_time));
						
						break;
                    }
                    break;
                case (preg_match_all($a_set, $part, $matches,PREG_PATTERN_ORDER) ? true : false):
                	//start benchmark
					$start_time = microtime(TRUE);
					
                    //loop all adaptations
                    //get_adaptation_var($input,$data_object,$output);
                    $index = 0;
                    
                    //vars updated
                    $vars_updated = array();
                    
                    foreach($matches[1] as $a_func){
                    	$set_val = $matches[2][$index];
						if(is_numeric($set_val)){
							$data_object->$a_func = $set_val;
							$vars_updated[] = $a_func;
						}elseif($this->get_adaptation_var($matches[2][$index],$data_object,$set_value)){
							//get val of var
							$data_object->$a_func = $set_value;
							$vars_updated[] = $a_func;
						}else{
							$data_object->$a_func = $this->remove_quotes($set_val);
							$vars_updated[] = $a_func;
						}
				
						//remove set
						$part = str_replace($matches[0][$index],'',$part);
						
						//write back
						$this->update_adaption_page_data($data_object,$a_data_page,0,$vars_updated);
						
						 //end benchmark
						$end_time = microtime(TRUE);
						$this->a_benchmark_write("Set loading time:".($end_time - $start_time));
						
						break;
                        //$index++;
                    }
                    break;
                case (preg_match_all($a_if, $part, $matches,PREG_PATTERN_ORDER) ? true : false):
                	//start benchmark
					$start_time = microtime(TRUE);
					
                    //loop all adaptations
                    $index = 0;
                    foreach($matches[2] as $a_func){
                        //get all functions and process
                        $concept = (strlen($matches[1][$index]) ? $matches[1][$index] : $a_data_page);
           				$var = $matches[2][$index];
           				
           				//get left if part
           				if($this->get_adaptation_var($concept.'$'.$var,$data_object,$a_if_var)){
                            $left_if = $a_if_var;
                        }else{
                        	$part = str_replace($matches[0][$index], "",$part);
                            $this->crv->a_log_write("If error variable: <i>'".htmlentities($concept.'$'.$var)."'</i><br />");
                            break;
                        }
                        
                        //get right if
                        $right_if = $matches[4][$index];
						if($this->get_adaptation_var($right_if,$data_object,$a_if_var)){
							$right_if = $a_if_var;
						}
                        
						if(!is_numeric($left_if)){
							$left_if = "'".addslashes($left_if)."'";
						}
                            
                        $right_if = html_entity_decode($right_if,ENT_QUOTES);
						eval('$result = (('.$left_if.html_entity_decode($matches[3][$index]).$right_if.') ? true : false);');
						if($result){
							$part = str_replace($matches[0][$index],html_entity_decode(htmlentities($matches[6][$index])),$part);
						}else{
							$part = str_replace($matches[0][$index],'',$part);
						}

                        $index++;
                        
                        //end benchmark
						$end_time = microtime(TRUE);
						$this->a_benchmark_write("If loading time:".($end_time - $start_time));
						break;
                    }
                    break;
                case (preg_match_all($a_echo, $part, $matches,PREG_PATTERN_ORDER) ? true : false):
                	//start benchmark
					$start_time = microtime(TRUE);
					
                    //loop all adaptations
                    $index = 0;
                    foreach($matches[2] as $a_func){
						$concept = (strlen($matches[1][$index]) ? $matches[1][$index] : $a_data_page);
						$result = '';
						if($this->get_adaptation_var($matches[1][$index].'$'.$matches[2][$index],$data_object,$get_result)){
							$result = $get_result;
						}
						if(strlen($result)){
							$part = str_replace($matches[0][$index],$result,$part);
						}else{
							$part = str_replace($matches[0][$index],"",$part);
							$this->crv->a_log_write("[echo] Unkown variable: <i>'".htmlentities($matches[2][$index])."'</i><br />");
						}
                        break;
                    }
                     //end benchmark
					$end_time = microtime(TRUE);
					$this->a_benchmark_write("Echo loading time:".($end_time - $start_time));
						
                    break;
                case (preg_match_all($a_top, $part, $matches,PREG_PATTERN_ORDER) ? true : false):
                    //start benchmark
					$start_time = microtime(TRUE);
					
                    //loop all adaptations
                    $index = 0;

                    foreach($matches[2] as $a_func){
                        //get all functions and process
                        $variable = $matches[2][0];
                        $sort = $matches[3][0];
                        
                        $func = $matches[4][0];
                        $options = explode(";",$matches[5][0]);
                        $a_top_arr = $var_calc_arr[$variable];
                        
                        //filter duplicates
                        $filter_arr = array();    
                        $count = count($a_top_arr);
                        for($i=0;$i<$count;$i++){
                        	$needle=$a_top_arr[$i]['concept_name'].$a_top_arr[$i]['var_name'];
                            if(in_array($needle, $filter_arr)){
                                unset($a_top_arr[$i]);
                            }elseif($a_top_arr[$i]['var_name'] != $variable){
                            	unset($a_top_arr[$i]);
                            }else{
                                $filter_arr[] = $a_top_arr[$i]['concept_name'].$a_top_arr[$i]['var_name'];
                            }
                        }
                        //return variable
                        $return = "";
                        
                        switch($sort){
                        	case 'max':
                        		rsort($a_top_arr);
                        		break;
                        	case 'min':
                        		sort($a_top_arr);
                        		break;
                        }
                        
                        $options = explode(";",$matches[5][0]);
                        
                        switch($func){
                            case 'concept':
                                if(is_numeric($options[0])){
                                    $return = $a_top_arr[$options[0]]['concept_name'];
                                    if(isset($options[1])){
										if(preg_match('/([0-9a-zA-Z]+)/si',$options[1],$var_matches)){
											$data_object->$options[1] = $return;
											//write back
											//vars updated
											$vars_updated = array();
											$vars_updated[] = $options[1];
											$this->update_adaption_page_data($data_object,$a_data_page,0,$vars_updated);
										}
                                    }
                                }
                                break;
                            case 'conceptGlobalVar':
                                if(is_numeric($options[0])){
                                    if(isset($options[1])){                                    	
                                        $value = $this->crv->get_a_var_val($options[1],$a_top_arr[$options[0]]['concept_name'],1,0);
                                        $return = $value['value'];
                                    }
                                }
                                break;
                            case 'conceptVar':
                                if(is_numeric($options[0])){
                                    if(isset($options[1])){
                                        $value = $this->crv->get_a_var_val($options[1],$a_top_arr[$options[0]]['concept_name'],0,0);
                                        $return = $value['value'];
                                    }
                                }
                                break;
                            case 'order':
                                foreach($a_top_arr as $key=>$item){
                                    if(isset($options[0])){
                                        if($item['concept_name'] == $options[0]){
                                            $return = $key;
                                        }
                                    }
                                }
                                break;
                            case 'menu':
                                /*
                                start-item
                                max-item
                                link name var
                                link var
                                */
                                //defaults
                                $start = 0;
                                $max = count($a_top_arr);
                                
                                foreach($options as $key=>$option){
                                    if(strlen($option)){
                                        $option_arr = explode(':',$option);
                                        if(isset($option_arr[0]) && isset($option_arr[1])){
                                            switch($option_arr[0]){
                                                case 'start':
                                                    $start = $option_arr[1];
                                                    break;
                                                case 'max':
                                                    $max = $option_arr[1];
                                                    break;
                                                case 'linkVar':
                                                    $linkVar = $option_arr[1];
                                                    break;
                                                case 'linkNameVar':
                                                    $linkNameVar = $option_arr[1];
                                                    break;
                                            }
                                        }
                                    }
                                }
                                //switch options
                                
                                $return = "<ul>";
                               
                                for($i=$start;$i<$max;$i++){
                                    $key = $i;                    				
                                    $item = $a_top_arr[$i];
                                    //--------------------[linkVar]----------
                                    if(isset($linkVar) && isset($option_arr[1])){
                                        $value = $this->crv->get_a_var_val($linkVar,$item['concept_name'],1,0);
                                        $link = ((strlen($value['value'])) ? $value['value'] : "../".$item['concept_name']);
                                     }else{               
                                        $link = "../".$item['concept_name'];
                                    }
                                    //--------------------[linkNameVar]----------
                                    if(isset($linkNameVar)){
                                        $value = $this->crv->get_a_var_val($linkNameVar,$item['concept_name'],1,0);
                                        $linkName = ((strlen($value['value'])) ? $value['value'] : $item['concept_name']);
                                     }else{               
                                        $linkName = $item['concept_name'];
                                    }
                                    $return .= "<li><a href='".$link."'>".$linkName."</a></li>";
                                }
                                $return .= "</ul>";
                                break;
                            case 'navbar':
                                
                                break;
                        }
						$part = str_replace($matches[0][$index],$return,$part);
						
						//end benchmark
					    $end_time = microtime(TRUE);
					    $this->a_benchmark_write("Top loading time:".($end_time - $start_time));
                        break;
                    }
                    break;
                case (preg_match_all($a_expression, $part, $matches, PREG_PATTERN_ORDER) ? true: false):
                	$result = '';
                	$error = '';
                	try{
                		$eval_code = html_entity_decode(html_entity_decode($matches[1][0],ENT_QUOTES));
                		ob_start();
                		eval('$result='.$eval_code.';');
                		$error = ob_get_contents();
						ob_end_clean();
                	} catch (Exception $e) {
   						$this->crv->a_log_write("Expression: Caught exception: ".  $e->getMessage() . "\n");
    				}
    				if(strlen($error)){
    					$this->crv->a_log_write("Expression: ".$error);
    				}
    				$part = str_replace($matches[0][0],$result,$part);
                	break;
                case (preg_match_all($a_hashtag_short, $part, $matches,PREG_PATTERN_ORDER) ? true : false):
                	//start benchmark
					$start_time = microtime(TRUE);
					
                    //loop all adaptations
                    $index = 0;
                    

                    foreach($matches[1] as $a_func){
                        //get all functions and process
                        switch($matches[1][$index]){
                        	case 'interval':
                        		if(strlen($matches[2][$index])){
									 $matches[4][$index] = (is_numeric($matches[4][$index]) ? (int)($matches[4][$index]) : 0);
									 $matches[2][$index] = (explode(";",$matches[2][$index]));
									 $apage = $matches[2][$index][0];
									 $replaceid = (isset($matches[2][$index][1]) ? $matches[2][$index][1] : 'console');
									//ajax js update if 
										$js .= '
										$(document).ready(function() {
											setInterval("ajaxd_'.$div_index.'()",'.$matches[4][$index].');
										});

										function ajaxd_'.$div_index.'() { 
											$.ajax({
												type: "POST",
												url: "../apage/'.$apage.'",
												data: {
													term: "1"
													},
												success: function(data){
													$("#'.$replaceid.'").html(data);
												}
											});
										}';
									}
									$part = str_replace($matches[0][$index],"",$part);
								break;
                        	case 'read':
                        		if(strlen($matches[2][$index])){
									 $matches[4][$index] = (is_numeric($matches[4][$index]) ? (int)($matches[4][$index]) : 0);
									 $matches[2][$index] = (explode(";",$matches[2][$index]));
									 $apage = $matches[2][$index][0];
									 $replaceid = (isset($matches[2][$index][1]) ? $matches[2][$index][1] : 'console');
									//ajax js update if 
										$js .= '
										$(document).ready(function() {
											setTimeout("ajaxd_'.$div_index.'()",'.$matches[4][$index].');
										});

										function ajaxd_'.$div_index.'() { 
											$.ajax({
												type: "POST",
												url: "../apage/'.$apage.'",
												data: {
													term: "1"
													},
												success: function(data){
													$("#'.$replaceid.'").html(data);
												}
											});
										}';
									}
									$part = str_replace($matches[0][$index],"",$part);
								break;
						    case "alink":
						         if(strlen($matches[2][$index])){
									$matches[2][$index] = (explode(";",$matches[2][$index]));
									$apage = $matches[2][$index][0];
                                    $replaceid = (isset($matches[2][$index][1]) ? $matches[2][$index][1] : 'console');
                                    $link_id = $matches[2][$index][2];
                                
                                    if(isset($link_id) && isset($replaceid)){
                                       $js .= "
                                                jQuery(document).ready(function () {
                                                   $(document).on(\"click\",\"a[id='".$link_id."']\", function (e) {
                                                        ajaxd_".$div_index."();
                                                    });
                                                });
                                                function ajaxd_".$div_index."() { 
                                                $.ajax({
                                                    type: \"POST\",
                                                    url: \"../apage/".$apage."\",
                                                    data: {
                                                        term: \"1\"
                                                        },
                                                    success: function(data){
                                                        $(\"#".$replaceid."\").html(data);
                                                    }
                                                });
                                            }"; 
                                        
                                    }
                                }
                                $part = str_replace($matches[0][$index],'',$part);
                                break;
                        	case "processbar":
                        		 if($this->get_adaptation_var($matches[4][$index],$data_object,$a_hashtag_var)){
                                	$matches[4][$index] = $a_hashtag_var;
                            	}
                            	
                            	$result = '';
                                $matches[4][$index] = (is_numeric($matches[4][$index]) ? (float)($matches[4][$index]) : 0);
                                $matches[2][$index] = (is_numeric($matches[2][$index]) ? (float)($matches[2][$index]) : 100);
                                $percentage = round((($matches[4][$index] / $matches[2][$index]) * 100),2);
                                $result = '
								<div class="progress">
								  <div class="progress-bar" role="progressbar" aria-valuenow="'.$percentage.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$percentage.'%;">
									'.$percentage.'%
								  </div>
								</div>
								';
                        		$part = str_replace($matches[0][$index],$result,$part);
                        		break;
							default:
								$this->crv->a_log_write("Check: ".htmlentities($matches[0][$index]));
								$part = str_replace($matches[0][$index],"",$part);
								break;
						}
						$index++;
						$div_index++;
						break;
					}
				
					//end benchmark
					$end_time = microtime(TRUE);
					$this->a_benchmark_write("Hashtag short loading time:".($end_time - $start_time));
				
					break;
                case (preg_match_all($a_hashtag, $part, $matches,PREG_PATTERN_ORDER) ? true : false):
                	//start benchmark
					$start_time = microtime(TRUE);
					
                    //loop all adaptations
                    $index = 0;

                    foreach($matches[1] as $a_func){
                        //get all functions and process
                        switch($matches[1][$index]){
                        	case "date":
                        		 if($this->get_adaptation_var($matches[4][$index],$data_object,$a_hashtag_var)){
                                	$matches[4][$index] = $a_hashtag_var;
                            	}
                            	$result = '';
                                $matches[4][$index] = (is_numeric($matches[4][$index]) ? (int)($matches[4][$index]) : 0);
                                if(strlen(html_entity_decode($matches[3][$index]))){
                                	eval('$result = ((date("'.$matches[2][$index].'")'.html_entity_decode($matches[3][$index]).$matches[4][$index].') ? "'.htmlentities($matches[6][$index]).'" : "");');
                                }
                                $part = str_replace($matches[0][$index],$result,$part);
                        
                                $js .= "";
                        		break;
                            case "fade":
                            	switch($matches[2][$index]){
                            		case 'in':
                            			 if($this->get_adaptation_var($matches[4][$index],$data_object,$a_hashtag_var)){
											$matches[4][$index] = $a_hashtag_var;
										}
										$matches[4][$index] = (is_numeric($matches[4][$index]) ? (int)($matches[4][$index]) : 0);
										$div_id = "a_fadein_".$div_index;
										$part = str_replace($matches[0][$index],"<span id='$div_id' style='display:none;'>".$matches[6][$index]."</span>",$part);
						
										$js .= "
											jQuery(document).ready(function () {
												setTimeout( \"jQuery('#$div_id').show();\", ".$matches[4][$index]." );
											});
										";
                            			break;
                            		case 'out':
                            		    if($this->get_adaptation_var($matches[4][$index],$data_object,$a_hashtag_var)){
											$matches[4][$index] = $a_hashtag_var;
										}
										$matches[4][$index] = (is_numeric($matches[4][$index]) ? (int)($matches[4][$index]) : 0);
										$div_id = "a_fadeout_".$div_index;
										$part = str_replace($matches[0][$index],"<span id='$div_id'>".$matches[6][$index]."</span>",$part);
						
										$js .= "
											jQuery(document).ready(function () {
												setTimeout( \"jQuery('#$div_id').hide();\", ".$matches[4][$index]." );
											});
										";
										$css .= "";
                            			break;
                            	}
                                break;                            
							default:
								$part = str_replace($matches[0][$index],"",$part);
								break;
                        }
                        $index++;
                        $div_index++;
                        break;
                    }
                    
                    //end benchmark
					$end_time = microtime(TRUE);
					$this->a_benchmark_write("Hashtag loading time:".($end_time - $start_time));
					
                    break;
                default:
                	$part = str_replace($g_matches[0][0],'',$part);
                    $this->crv->a_log_write("Check: ".htmlentities($g_matches[0][0]));
                    break;
            }
            $output = str_replace($g_matches[0][0],$part,$output);
	    }
        
        //css and js
        $output = str_replace("{js/}",$js,$output);
        $output = str_replace("{css/}",$css,$output);
        
        return $output;
	}
}
?>