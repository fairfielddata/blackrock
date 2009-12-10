<?php
class Debugger {
	static function out($var,$exit = false){
		$ip = $_SERVER['REMOTE_ADDR'];
		if(strpos($ip,".") !== FALSE){
			echo "<small><pre>".htmlentities(print_r($var,1),ENT_NOQUOTES,'UTF-8')."<hr size=1></pre></small>\n\n";
			ob_flush();
			flush();
			if ($exit){
				exit;
			}
		} else {
			return;
		}
	}
}