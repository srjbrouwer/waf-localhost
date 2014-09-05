<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<ol class="breadcrumb">
<?
$breadcrumbs = explode('/',$_SERVER['REQUEST_URI']);
$breadcrumbs_prev = "";
foreach($breadcrumbs as $key=>$item){
	if(strlen($item)){
		$active = (((count($breadcrumbs)-1) == $key) ? ' class="active "' : '');
		echo "<li><a ".$active."href='http://".$_SERVER['SERVER_NAME'].$breadcrumbs_prev."/".$item."'>".ucfirst($item)."</a></li>";
		$breadcrumbs_prev .= "/".$item;
	}
}
?>
</ol>
<div class="col-md-8">
	<?=$crud->output?>
	<br />
</div>
<div class="col-md-4">
	<?=build_menu($menu,'left',false)?>
</div>
