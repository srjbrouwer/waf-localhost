<?
function curPageURL() {
	$pageURL = 'http';
	if (isset($_SERVER["HTTPS"])) {$pageURL .= "s";}
		$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}
function startup_load_classes(){
	$dir    = getcwd().'/includes';
	$files = scandir($dir, 1);
	foreach($files as $file){
		if( (substr($file,0,6)=='class.') && (substr($file,-4,4)=='.php') ) {
			include_once($file);
		}
	}
}
?>