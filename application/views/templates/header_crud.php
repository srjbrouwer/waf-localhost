<?
//menu function
function build_menu_parents($menu,$position, &$menu_parents = array()){
	if(isset($menu)){
		//get all parents and unset wrong position
		foreach($menu as $key=>$menu_item){
			if($menu_item['position']==$position){
				if($menu_item['parent_id']){
					$menu_parents[$menu_item['parent_id']] = 1;
				}
			}else{
				unset($menu[$key]);
			}
		}
	}
	return $menu;
}
function build_menu_childs(&$menu, $parent_id){
	$result = "";
	$childs = array();
	foreach($menu as $key=>$menu_item){
		if($parent_id == $menu_item['parent_id']){
			$childs[$menu_item['order'].$menu_item['id']] = $menu_item;
			unset($menu[$key]);
		}
	}
	ksort($childs);
	foreach($childs as $key=>$child){
		$active = (($_SERVER['REQUEST_URI'] == $child['link']) ? ' class="active" ' : '');
		$result .= "<li><a ".$active."href=".$child['link'].">".$child['name']."</a></li>";
	}
	return $result;
}
function build_menu($menu,$position,$dropdown=true){
	$result = "";
	$menu_parents = array();
	$menu = build_menu_parents($menu,$position,$menu_parents);
	if(isset($menu)){
		//get all root menu items
		sort($menu);
		$key = 0;
		while(count($menu)){
			if(isset($menu[$key])){
				$menu_item = $menu[$key];
				//get correct position
				if(isset($menu_parents[$menu_item['id']])){
					if($dropdown){
						$result .= "
						<li class=\"dropdown\">
							<a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\">".$menu_item['name']." <span class=\"caret\"></span></a>
							<ul class=\"dropdown-menu\" role=\"menu\">
						";
						$result .= build_menu_childs($menu, $menu_item['id']);
						$result .= "
							</ul>
						</li>";
					}else{
						$result .= "
						<li class=\"\" style=\"list-style-type: none;\">
							<h2>".$menu_item['name']."</h2>
							<ul class=\"menu\" role=\"menu\">
						";
						$result .= build_menu_childs($menu, $menu_item['id']);
						$result .= "
							</ul>
						</li>";					
					}
					unset($menu[$key]);
				}elseif(!$menu_item['parent_id']){
					//child
					$active = (($_SERVER['REQUEST_URI'] == $menu_item['link']) ? ' class="active" ' : '');
					$result .= "<li><a ".$active."href=".$menu_item['link'].">".$menu_item['name']."</a></li>";
					unset($menu[$key]);
				}
			}
			$key++;
		}
	}
	return $result;
}
?>
<!DOCTYPE html>
<html>
<head>
	<base href="http://<?=$_SERVER['HTTP_HOST']?>/waf/">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="language" content="nl" />
    	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" media="screen"/>
	<link rel="stylesheet" type="text/css" href="css/style.css" media="screen"/>
	<link rel="stylesheet" type="text/css" href="css/admin.css" media="screen"/>
	<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.10.4.custom.css" media="screen"/>
    <?
	//-----------------------------------[Extra Scripts]--------------------------------------
	if(isset($css_extra)){
		foreach ($css_extra as $value) {
    			?>
       		 		<link rel="stylesheet" type="text/css" href="<?=$value?>"/>
        		<?
		}
    	}
	?>
	<title>Adaptation Layer</title>
	<script src="js/jquery-2.1.0.min.js"></script>
    <script src="js/jquery-ui-1.10.4.custom.js"></script>
	<script src="js/bootstrap.js"></script>
	<script src="plugins/typeahead/typeahead.bundle.js"></script>
	<script src="file/style"></script>
	<?php 
	if(isset($crud->css_files)){
		foreach($crud->css_files as $file): 
	?>
		<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
	<?php
		endforeach;
	}
	?>
	<?php
	if(isset($crud->js_files)){
		foreach($crud->js_files as $file): 
	?>
		<script src="<?php echo $file; ?>"></script>
	<?php 
		endforeach;
	}
	?>
</head>
<body>
	<div id="wrap">
	    <!-- Fixed navbar -->
		<div class="navbar navbar-default navbar-fixed-top" role="navigation">
      		<div class="container">
        		<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="page/home">Back to website</a>
				</div>
				<div class="navbar-collapse collapse">
					<ul class="nav navbar-nav">
					<?php
					echo build_menu($menu,'left');
					?>
				  </ul>

				  <ul class="nav navbar-nav navbar-right">
					<?php
					echo build_menu($menu,'right');
					?>
					</ul>
				</div><!--/.nav-collapse -->
		  </div>
	</div>
	<div class="container" id="main-container">
		<div class="page-header">
			<h1><?=$title?></h1>
		</div>