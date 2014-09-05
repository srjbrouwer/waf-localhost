<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<ol class="breadcrumb">
<?
$breadcrumbs = explode('/',$_SERVER['REQUEST_URI']);
$breadcrumbs_prev = "";
foreach($breadcrumbs as $key=>$item){
	if(strlen($item)){
		$active = (((count($breadcrumbs)-1) == $key) ? ' class="active "' : '');
		echo "<li><a ".$active."href='..".$breadcrumbs_prev."/".$item."'>".ucfirst($item)."</a></li>";
		$breadcrumbs_prev .= "/".$item;
	}
}
?>
</ol>
<div class="col-md-8">
	<div class="alert alert-success" role="alert">
		<h1>Hello <?=$user['user_fname']?> <?=$user['user_lname']?></h1>
		<p>This is the back-end of the adaptive web-application.</p>
	</div>
	<div style="padding: 20px;margin: 20px 0; border: 1px solid #eee; border-left-width: 5px; border-radius: 3px;border-left-color: #5bc0de;">
		<p>The menu on the right side give you the ability to change the CMS and Adaptation tables. <h2>CMS</h2>The CMS is very basic, containing a user-module, menu-module and page-module. A page always needs to be defined in the database even if you want to write a page in a file instead of the database.<h2>Adaptation</h2>The adaptation menu shows links to the global adaptation settings, concepts, variables, relationships and the values of the variables.</p>
	</div>
</div>
<div class="col-md-4">
	<?=build_menu($menu,'left',false)?>
</div>
