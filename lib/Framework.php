<?php
class Framework {
	public $application;
	public $model;
	public $view;
	public $controller;
	public $action;

	public $application_path;
	public $model_path;
	public $view_path;
	public $controller_path;

	public function Framework(){
		$this->routes      = NULL;
		$this->application = "";
		$this->model       = "";
		$this->view        = "";
		$this->controller  = "";
		$this->action      = "";

		$this->application_path = "";
		$this->model_path       = "";
		$this->view_path        = "";
		$this->controller_path  = "";
	}

	static function includeLibrary($library){
		$library_path  = '../lib/'.$library;
		if(is_file($library_path)){
			include_once($library_path);
		} else {
			throw new FrameworkException('Failed To Include Library: '.$library);
		}
	}

	public function includeModel($model){
		$model_path  = $this->model_path.$model.".php";
		if(is_file($model_path)){
			include_once($model_path);
		} else {
			throw new FrameworkException('Failed To Include Model: '.$model);
		}
	}

	public function dispatchRequest($request){
		$routes_description = array_shift(array_keys($request));
		@list($routes['application'],$routes['controller'],$routes['action']) = explode("/",$routes_description);
		
		//If route fragment not defined, use global config defaults
		@$this->application = $framework->application = $routes['application'] ? strtolower($routes['application']) : $GLOBALS['CONFIGS']['DEFAULT_APPLICATION'];
		@$this->controller  = $framework->controller  = $routes['controller']  ? strtolower($routes['controller'])  : $GLOBALS['CONFIGS']['DEFAULT_CONTROLLER'];
		@$this->action      = $framework->action      = $routes['action']      ? strtolower($routes['action'])      : $GLOBALS['CONFIGS']['DEFAULT_ACTION'];
		@$this->self        = $framework->self        = "/".$framework->application."/".$framework->controller."/".$framework->action;
		@$this->routes      = $framework->routes      = array("application" => $framework->application, "controller" => $framework->controller, "action" => $framework->action);        
		$this->application_path  = '../applications/'.$this->application;
		$this->controller_path   = $this->application_path.'/controllers';
		$this->view_path         = $this->application_path.'/views';
		$this->model_path        = $this->application_path.'/models';

		$controller_file   = $this->controller_path.'/'.Framework::camelize($this->controller).'Controller.php';
		$controller_name = Framework::camelize($this->controller).'Controller';
		$action_name     = Framework::camelize($this->action,false).'Action';

		if(is_dir($this->model_path)){
			foreach(glob($this->model_path."/*Model.php") as $model){
				include_once($model);
			}
		}
		if(is_file($this->application_path.'/config.php')){
			include_once($this->application_path.'/config.php');
		}
		if(is_file($this->application_path.'/initialize.php')){
			include_once($this->application_path.'/initialize.php');
		}
		if(is_file($controller_file)){
			include_once($controller_file);
		} else {
			throw new FrameworkException('Invalid Application/Controller: '.$controller_name.' in '.$controller_file);
		}
		$Controller = new $controller_name;

		$this->view = new FrameworkView();
		$this->view->template_dir = $this->view_path;
		$this->view->compile_dir  = @$GLOBALS['CONFIGS']['SMARTY_COMPILE_DIR'] ? @$GLOBALS['CONFIGS']['SMARTY_COMPILE_DIR'] : '../applications/'.$this->application.'/data/smarty/templates_c/';
		$this->view->config_dir   = $this->view_path;
		$this->view->caching      = $GLOBALS['CONFIGS']['SMARTY_ENABLE_CACHING'];
		$this->view->cache_dir    = @$GLOBALS['CONFIGS']['SMARTY_CACHE_DIR'] ? @$GLOBALS['CONFIGS']['SMARTY_CACHE_DIR'] : '../applications/'.$this->application.'/data/smarty/cache/';
		$this->view->root_assign('framework',get_object_vars($framework));

		//Allow access to view from the controllers
		$Controller->view = &$this->view;
		
		//Validate existence and callability of Controller->Action
		$callable_name = "";
		$call = array($Controller,$action_name);
		if(is_callable($call,false,$callable_name)){
			try {
				$Controller->$action_name();
			} catch (FrameworkException $e) {
				throw new FrameworkException('Framework Execution Failed: '.$controller_name.'->'.$action_name.'()');
			}
		} else {
			throw new FrameworkException('Invalid Action: '.$controller_name.'->'.$action_name.'()');
		}
	}

	static function camelize($string,$capitalize_first=true){
		//Debugger::out($string);
		$string = preg_replace('/\W+/',' ',$string);
		$string = ucwords($string);
		$string = preg_replace('/\W+/','',$string);

		if(!$capitalize_first){
			$string{0} = strtolower($string{0});
		}
		return $string;
	}

	static function uncamelize($string="",$join_using = '-'){
		$string{0} = strtolower($string{0});
		$string = preg_replace("/([A-Z])/", $join_using."$1", lcfirst($string));
		return strtolower($string);
	}

	static function startSession(){
		if(!session_id()){
			if(!session_start()){
				throw new FrameworkException('Session Start Failed: Could not initialize session.');
			}
		}
	}
	
}

class FrameworkModel {
	public $db;
	public $table;

	public function connect(){
		try {
			$this->db = NewADOConnection($GLOBALS['CONFIGS']['DB_DSN']);
			$this->db->SetFetchMode(ADODB_FETCH_ASSOC);
		} catch (Exception $e) {
			throw new FrameworkException('Database Connection Failed: '.$e->getMessage());
		}
	}

	public function setTableToModel($table){
		$this->table = $table;
	}

	public function getRecords($constraints="",$order="",$limit=""){
		$this->connect();
		if($this->db->IsConnected()){
			if(empty($constraints))
				$constraints = $this->table.".id <> 0 ";
			return $this->db->GetAll("SELECT ".$this->table.".* FROM ".$this->table." WHERE $constraints $order $limit ");
		} else {
			return false;
		}
	}

	public function getRecord($constraints="",$order=""){
		$this->connect();
		if($this->db->IsConnected())
			return $this->db->GetRow("SELECT ".$this->table.".* FROM ".$this->table." WHERE $constraints $order ");
		else
			return false;
	}

	public function insertRecord($record){
		$this->connect();
		$record['created'] = date("Y-m-d H:i:s");
		$record['updated'] = date("Y-m-d H:i:s");
		if($this->db->IsConnected() && $this->db->AutoExecute($this->table,$record,'INSERT')){
			return $this->db->Insert_ID();
		} else {
			return false;
		}
	}

	public function duplicateRecord($id){
		$this->connect();
		$existing_record = $this->getRecord(" ".$this->table.".id='$id' ");
		$duplicate_record = $existing_record;
		
		unset($duplicate_record['id']);
		if($existing_record['unique_hash'])
			$duplicate_record['unique_hash'] = md5(uniqid(rand(),true));

		$duplicate_record['created'] = date("Y-m-d H:i:s");
		$duplicate_record['updated'] = date("Y-m-d H:i:s");

		if($this->db->IsConnected() && $this->db->AutoExecute($this->table,$duplicate_record,'INSERT')){
			return $this->db->Insert_ID();
		} else {
			return false;
		}
	}

	public function updateRecord($record,$constraints){
		$this->connect();
		$record['updated'] = date("Y-m-d H:i:s");
		if($this->db->IsConnected())
			return $this->db->AutoExecute($this->table,$record,'UPDATE', $constraints);
		else
			return false;
	}

	public function deleteRecord($id){
		$this->connect();
		if($this->db->IsConnected())
			return $this->db->Execute("DELETE FROM ".$this->table." WHERE ".$this->table.".id='$id' ");
		else
			return false;
	}

	public function deleteRecords($constraints){
		$this->connect();
		if($this->db->IsConnected())
			return $this->db->Execute("DELETE FROM ".$this->table." WHERE $constraints ");
		else
			return false;
	}

	public function populateFromRequest($request,$valid_request_fields=array()){
		$this->connect();
		if($this->db->IsConnected()){
			$record = array();
			$column_names = $this->db->MetaColumnNames($this->table);
			$columns      = $this->db->MetaColumns($this->table);
			$primary_keys = $this->db->MetaPrimaryKeys($this->table);
			
			//Debugger::out($columns);
			//Debugger::out($this->table);
			if(is_array($columns)){
				foreach($columns as $key => $column){
					$default_value = $column->has_default ? $column->default_value : "";
					if(isset($valid_request_fields[$column->name])){
						$record[$column->name] = (@$request[$column->name]) ? @$request[$column->name] : $default_value;
					}
				}
			}
			//Debugger::out($record,1);
			return $record;
		} else {
			return false;
		}
	}
}

class FrameworkController {
}

class FrameworkView extends Smarty {
	private $headers = null;
	public $_tpl_vars;
	public $_framework_tpl_vars;

	public function __construct(){
	}

	public function assign($key,$value=null,$sanitize_flags=""){
		if($sanitize_flags === ""){
			$sanitize_flags = HTML;
		}
		if(is_scalar($key)){
			$this->sanitize_template_data($value,$sanitize_flags);
			$this->_framework_tpl_vars[$key] = $value;
		} else if(is_array($key) && count($key)) {
			array_walk_recursive($key,array($this,'sanitize_template_data'),$sanitize_flags);
			foreach($key as $assigned_key => $assigned_value){
				$this->_framework_tpl_vars[$assigned_key] = $assigned_value;
			}
		} else if(is_object($key) && count(get_object_vars($key)) ) {
			$object_data = get_object_vars($key);
			array_walk_recursive($object_data,array($this,'sanitize_template_data'),$sanitize_flags);
			foreach($object_data as $assigned_key => $assigned_value){
				$this->_framework_tpl_vars[$assigned_key] = $assigned_value;
			}
		}
		$this->root_assign('view',$this->_framework_tpl_vars);
	}

	public function root_assign($key,$value){
		parent::assign($key,$value);
	}
	
	public function root_append($key,$value){
		parent::append($key,$value);
	}
	
	public function sanitize_template_data(&$value,$key,$sanitize_parameters = ""){
		$value = sanitize($value,$sanitize_parameters);
	}
}

class FrameworkException extends Exception {
}
/*
Improve Exception Handling

Debugger ->


*/
?>