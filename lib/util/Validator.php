<?php

class Validator
{
	var $full_errors;  //Array of errors indexed by 
	var $is_valid;     //Validity Verdict
	var $fatal_error;  //Prevents further validation of errors i.e stop validation if current test fails
	var $params;       //Additional Parameters
	var $input;        //Array of input to be validated
	var $validated_fields; //Record of fields that have been validated
	
	function Validator(&$input = array()){
		$this->full_errors       = array();
		$this->is_valid          = true;
		$this->fatal_error       = 0;
		$this->params            = array();
		$this->input             = $input;
		$this->validated_fields  = array();
	}
	
	function validate($var_name, $tests = array(), $fatality = 0){
		$this->validated_fields[$var_name] = $var_name;
		@$var = $this->input[$var_name];
		$this->is_valid = false;
		
		if(is_scalar($var)){
			$var = stripslashes($var);
		}
		
		//Fatal Error has occurred in previous validation. Current validation should not proceed
		if($this->fatal_error == 1){
			return false;
		} else if ( $fatality ){
			$this->fatal_error = 1;
		}
		//echo $var."::".$var.NL;
		if( 		
			!is_array($tests) ||
			count($tests) < 1 
		){
			unset($this->full_errors);
			$this->full_errors["unspecified"] = "Validity of submitted information could not be verified";
			return false;
		}
		
		//Preprocess grouped rules
		$new = array();
		foreach( $tests as $_test=>$error ){
			if( strpos($_test,"|") && $arr = explode("|",$_test) ){
				for($in_A = 1; $in_A <= count($arr); $in_A++ )	$new[ $arr[$in_A-1] ] = $error;
				//unset( $temp[$_test] );
			} else 
				$new[$_test] = $error;
		}
		unset($tests);
		$tests = &$new;
		if(is_array($tests)){
			foreach( $tests as $_test => $error ){	
				@list($test,$parameter) = explode("::",$_test);
				switch($test){
					case "none":
					break;
					case "inrange":
						//echo print_r($this->params[$parameter],1)."::".$var.NL;
						if ( is_array($this->params[$parameter]) && array_search($var,$this->params[$parameter]) !== FALSE ) {
						} else {
							$this->throw_error($error,$var_name);return;
						}
					break;
					case "required":
						if( !isset($var)) {
							$this->throw_error($error,$var_name);return;
						} else if( is_array($var) && count($var) < 1 ){
							$this->throw_error($error,$var_name);return;
						} else if( $var == "" ) {
							$this->throw_error($error,$var_name);return;
						} else if( is_scalar($var) && !strlen($var) ){
							$this->throw_error($error,$var_name);return;
						} else {
						}
					break;
					case "numeric":
						if(is_numeric(trim($var))) {
						} else {
							$this->throw_error($error,$var_name);return;
						}
					break;
					case "integer":
						if(ctype_digit(trim($var))) {
						} else {
							$this->throw_error($error,$var_name);return;
						}
					break;
					case "notrequired":
						if(empty($var) && $var!==0){
						} else {
							$this->throw_error($error,$var_name);return;
						}
					break;
					case "match":
						if(	$this->params[$parameter] == $var ){
							$this->assign($parameter,$this->params[$parameter]);
						} else {
							$this->throw_error($error,$var_name);return;
						}
					break;
					case "nomatch":
						if(	$this->params[$parameter] != $var ){
						} else {
							$this->throw_error($error,$var_name);return;
						}
					break;
					case "array":
						if(is_array($var) && count($var)>0 ){
						} else {
							$this->throw_error($error,$var_name);return;
						}
					break;
					case "regexp":
						if(ereg($parameter,$var) ){
							$is_valid = true;
						} else {
							$this->throw_error($error,$var_name);return;
						}
					break;
					case "pregexp":
						if(preg_match($parameter,$var) ){
							$is_valid = true;
						} else {
							$this->throw_error($error,$var_name);return;
						}
					break;
					case "unique":
						if( !isset($parameter) ){
							$this->throw_error($error,$var_name);return;
						} else if( !is_array($this->params[$parameter]) ) {
							$this->throw_error($error,$var_name);return;
						} else if ( array_search($var,$this->params[$parameter],1) !== FALSE ) {
							$this->throw_error($error,$var_name);return;
						} else {
						}
					break;
					case "email":
						"/^[-!#$%&\'*+\\.\/0-9=?A-Z^_`{|}~]+";
						if(preg_match('/^[A-z0-9_\-\+\.]+\@([A-z0-9_-]+\.)+[A-z]{2,4}$/si', trim($var) )){
						} else {
							$this->throw_error($error,$var_name);return;
						}
					break;
					case "url":
						//TODO: Better URL Validation Algo
						if( preg_match('|^http(s)?://[a-z0-9-]+(\.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', trim($var) ) ){
						} else {
							$this->throw_error($error);return;
						}
					break;
					case "phone":
						if( strlen ( preg_replace('/\D/','', $var) ) >= 10 ){
						} else {
							$this->throw_error($error,$var_name);return;
						}
					break;
					case "minchars":
						if(strlen($var) >= $parameter){
							$is_valid = true;
						} else {
							$this->throw_error($error,$var_name);return;
						}
					break;
					case "maxchars":
						if(strlen($var) <= $parameter){
						} else {
							$this->throw_error($error,$var_name);return;
						}
					break;
					case "file":
						if($_FILES[$var_name] && ($_FILES[$var_name]['size'] > 0) && ($_FILES[$var_name]['error'] == 0) ){
						} else {
							$this->throw_error($error,$var_name);return;
						}
					break;
					case "maxsize":
						if($_FILES[$var] && ($_FILES[$var]['size'] < $parameter) && ($_FILES[$var]['error'] == 0) ){
						} else {
							$this->throw_error($error,$var_name);return;
						}
					break;
					case "filetype":
						if($_FILES[$var] && ($_FILES[$var]['size'] > 0 ) && ($_FILES[$var]['error'] == 0)){
							$type = get_unix_file_properties($_FILES[$var]["tmp_name"]);
							$mime_type = $_FILES[$var]["type"];
							//echo "P:".$parameter.NL."T:".$type.NL."M:".$mime_type.NL;
							if($parameter == "image"){
								if(	
									(strpos( $type ,"GIF") !== FALSE) 
									|| (strpos( $type ,"JPEG") !== FALSE) 
									|| (strpos( $type ,"JFIF") !== FALSE)
									|| (strpos( $type ,"PNG") !== FALSE)
								){
								} else {
									$this->throw_error($error,$var_name);return;
								}	
							} else if($parameter == "music"){
								if(	strpos( $type ,"audio stream") !== FALSE ){	
								} else if(	strpos( $mime_type ,"audio") !== FALSE	){	
								} else {
									$this->throw_error($error,$var_name);return;
								}	
							} else if($parameter == "video"){
								if(	strpos( $type ,"stream data") !== FALSE	){	
								} else if(	strpos( $mime_type ,"video") !== FALSE	){	
								} else {
									$this->throw_error($error,$var_name);return;
								}	
							} else {
								$this->throw_error("Type: ".$parameter."::".$type." not supported");return;
							}
						} else {
							$this->throw_error($error,$var_name);return;
						}
					break;
					case "creditcard":					
						//$this->assign($var,$var);
						if($this->cc_validate($var,$parameter) ){
						}
						else {
							$this->throw_error($error,$var_name);return;
						}
					break;
					case "equals":
						if($var == $parameter){
						} else {
							$this->throw_error($error,$var_name);return;
						}
					break;
					case "bool":
						if($this->params[$parameter]){
							$this->throw_error($error,$var_name);return;
						}
					break;
					default:
						unset($this->full_errors);
						$this->full_errors["invalid_test"] = $var."::".$test.":Submitted information could not be validated.";
					break;
				}
			}
		} else {
			$this->is_valid = true;
			return true;
		}
		if( 
			(is_array($this->full_errors) && count($this->full_errors) > 0)		
		){
			//var_dump($this->full_errors);
			//var_dump($this->is_valid);
			$this->is_valid = false; // We should never end up here
			//var_dump($this->full_errors);
			//var_dump($this->is_valid);
		} else {
			$this->fatal_error = 0;
			$this->is_valid = true;
			return true;
		}
	}

	function throw_error($error,$error_on = "",$fatal_error = false){
		$this->is_valid = false;
		$this->fatal_error = $fatal_error;
		if($error_on == "")
			$this->full_errors["unspecified"] = $error;
		else 
			$this->full_errors[$error_on] = $error;
	}
	
	function errors(){
		return $this->full_errors;
	}
	
	function is_valid(){
		//var_dump($this->full_errors);
		return ((count($this->full_errors)<1));
	}
	
	function cc_validate($card_number, $card_type)
	{
		$result = false;
		switch(strtoupper($card_type))
		{
			case "VISA":
			case "V":
				if (ereg('^4[0-9]{12}([0-9]{3})?$', $card_number) && $this->luhn_10($card_number))
				{
					$result = true;
				}
				break;
			
			case "MASTERCARD":
			case "M":
				if (ereg('^5[1-5][0-9]{14}$', $card_number) && $this->luhn_10($card_number))
				{
					$result = true;
				}
				break;
			
			case "AMERICAN EXPRESS":
			case "AMERICANEXPRESS":
			case "AMEX":
			case "A":
				if (ereg('^3[47][0-9]{13}$', $card_number) && $this->luhn_10($card_number))
				{
					$result = true;
				}
				break;
			
			case "DISCOVER CARD":
			case "DISCOVER":
			case "D":
				if (ereg('^6011[0-9]{12}$', $card_number) && $this->luhn_10($card_number))
				{
					$result = true;
				}
				break;
			
			default:
			break;
		}
		
		return $result;
	}
	
	private function luhn_10($cardnumber){
		$cardnumber=preg_replace("/\D|\s/", "", $cardnumber);  # strip any non-digits
		$cardlength=strlen($cardnumber);
		$parity=$cardlength % 2;
		$sum=0;
		for ($i=0; $i<$cardlength; $i++) {
			$digit=$cardnumber[$i];
			if ($i%2==$parity) $digit=$digit*2;
			if ($digit>9) $digit=$digit-9;
			$sum=$sum+$digit;
		}
		$valid=($sum%10==0);
		return $valid;
	}
	
	static function formatUSPhoneNumber($phone_number){
		$phone_number = preg_replace("/\D+/","",$phone_number);
		//Debugger::out($phone_number);
		$phone_number = substr($phone_number,max(strlen($phone_number)-10,0));
		//Debugger::out($phone_number);
		$phone_number = "(".substr($phone_number,0,3).") ".substr($phone_number,3,3)."-".substr($phone_number,6,10);
		//Debugger::out($phone_number);
		return $phone_number;
	}
	
	public function validated_fields(){
		return $this->validated_fields;
	}
}

?>