<?php

class Status extends FrameworkView
{
	var $view    = null;
	var $title   = null;
	var $message = null;
	var $urls    = null;
	
	function __construct(){
		Framework::startSession();
	} 
	
	function returnStatus($message){
		$_SESSION['status'] = $message;
		header("Location: /tlr/status/message");
		exit;
	}
}
