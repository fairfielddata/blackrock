<?php
Framework::includeLibrary('misc/Status.php');
Framework::includeLibrary('misc/Validator.php');
Framework::includeLibrary('misc/Inflector.php');

class AdminController extends FrameworkController {	
	var $framework_applications_path = "C:/wwwroot/tenantlandlordratings/applications/";
	
	public function __construct(){
		//$this->framework_applications_path = "C:/wwwroot/tenantlandlordratings/applications/";
	}
	
	public function __destruct(){
	}
	
	public function indexAction(){
		$routes = $GLOBALS['Framework']->routes;
		$admin_controller_paths = glob("../applications/".$routes['application']."/controllers/Admin*Controller.php");
		foreach($admin_controller_paths as $admin_controller_path){
			$admin_controller['name'] = str_replace(array("Controller.php"),"",basename($admin_controller_path));
			$admin_controller['path'] = $admin_controller_path;
			$admin_controller['description'] = ucwords(Framework::uncamelize(str_replace(array("Controller.php"),"",basename($admin_controller_path)),' '));
			$admin_controller['url']  = "/".$routes['application']."/".Framework::uncamelize($admin_controller['name']);
			$admin_controllers[$admin_controller['name']] = $admin_controller;			
		}
		//Debugger::out($admin_controllers);
		ksort($admin_controllers);
		//Debugger::out($admin_controllers);
		foreach($admin_controllers as $admin_controller_name => $admin_controller){
			//Debugger::out($admin_controller);
			$controller_contents = file($admin_controller['path']);
			foreach($controller_contents as $controller_line){
				$public_function_line = "";
				if(strpos($controller_line,"function") !== FALSE && strpos($controller_line,"public") !== FALSE){
					$public_function_line = $controller_line;
					if(preg_match('#function(.*)Action#i',$public_function_line, $matches)){
						//Debugger::out($public_function_line);
						$public_function = Framework::uncamelize(trim($matches[1])," ");
						$admin_controllers[$admin_controller_name]['actions'][$public_function] = $admin_controller['url']."/".$public_function;
					}
				}
			}			
		}
		//Debugger::out($admin_controllers);
		$this->view->assign('page_title',"Administration Root");
		$this->view->assign('admin_controllers',$admin_controllers);
		$this->view->display('admin_index.tpl');	}
	
	public function loginAction(){
		header("Location: /admin/admin/index");
		exit;
	}
	
	public function listUsersAction(){
		
	}
	
	public function introspectAction(){
		$is_post = ($_SERVER['REQUEST_METHOD'] == "POST");
		try {
			$db = NewADOConnection($GLOBALS['CONFIGS']['DB_DSN']);
			$db->SetFetchMode(ADODB_FETCH_ASSOC);
		} catch (Exception $e) {
			throw new FrameworkException('Database Connection Failed: '.$e->getMessage());
		}
		if($is_post){
			$introspect_action = strtolower(substr($_POST['introspect_action'],8,4));
			
			$generated_file_name['model']         = $_POST['generated_file_name']['model'].".".date("YmdHIs");
			$generated_file_content['model']      = $_POST['generated_file_content']['model'];
			$generated_file_name['controller']    = $_POST['generated_file_name']['controller'].".".date("YmdHIs");
			$generated_file_content['controller'] = $_POST['generated_file_content']['controller'];
			$generated_file_name['list']          = $_POST['generated_file_name']['list'].".".date("YmdHIs");
			$generated_file_content['list']       = $_POST['generated_file_content']['list'];
			$generated_file_name['update']        = $_POST['generated_file_name']['update'].".".date("YmdHIs");
			$generated_file_content['update']     = $_POST['generated_file_content']['update'];
			$generated_file_name['view']          = $_POST['generated_file_name']['view'].".".date("YmdHIs");
			$generated_file_content['view']       = $_POST['generated_file_content']['view'];
			//Debugger::out($generated_file_name);
			$to_installs = array();
			switch($introspect_action){
				case "mode":
					$to_installs['model']      = $_POST['application'].'/models/'.$generated_file_name['model'];
				break;
				case  "cont":
					$to_installs['controller'] = $_POST['application'].'/controllers/'.$generated_file_name['controller'];
				break;
				case  "list":
					$to_installs['list']       = $_POST['application'].'/views/'.$generated_file_name['list'];
				break;
				case  "upda":
					$to_installs['update']     = $_POST['application'].'/views/'.$generated_file_name['update'];
				break;
				case  "view":
					$to_installs['view']       = $_POST['application'].'/views/'.$generated_file_name['view'];
				break;
				case  "all ":
					$to_installs['model']      = $_POST['application'].'/models/'.$generated_file_name['model'];
					$to_installs['controller'] = $_POST['application'].'/controllers/'.$generated_file_name['controller'];
					$to_installs['list']       = $_POST['application'].'/views/'.$generated_file_name['list'];
					$to_installs['update']     = $_POST['application'].'/views/'.$generated_file_name['update'];
					$to_installs['view']       = $_POST['application'].'/views/'.$generated_file_name['view'];
				break;
				default:
					"FAIL";
					exit;
				break;
			}
			//Debugger::out($to_installs);
			if(count($to_installs) > 0){
				foreach($to_installs as $what_to_install => $where_to_install){
					$output_path = $this->framework_applications_path.$where_to_install;
					$status[] = "Writing ".strlen($generated_file_content[$what_to_install])." bytes to $output_path";
					$bytes_written = file_put_contents($output_path,$generated_file_content[$what_to_install]);
					//$bytes_written = "";
					if($bytes_written !== FALSE ){
						$status[] = " + SUCCEEDED - Wrote ".$bytes_written." bytes";
					} else {
						$status[] = " - FAILED ";
					}
					$output_path = "";
				}
			} else {
				$status[] = " Errr...nothing to do really...";
			}
			$this->view->assign('status',$status);
		}
		
		//Get Tables
		$tables = array();
		$tables_names = $db->MetaTables();
		//Debugger::out($tables);
		//Create Array of Tables
		foreach($tables_names as $index => $table_name){
			//Debugger::out($table_name);
			$tables[$table_name]['column_names'] = $db->MetaColumnNames($table_name);
			$tables[$table_name]['columns']      = $db->MetaColumns($table_name);
			$tables[$table_name]['primary_keys'] = $db->MetaPrimaryKeys($table_name);
		}
		//Debugger::out($tables);
		$this->view->assign('tables',$tables);
		$this->view->assign('tables_dump',"<pre>".print_r($tables,1)."</pre>");
		$this->view->register_function('camelize',array('AdminController','to_camel_case'));
		$this->view->register_modifier('singularize',array('AdminController','singularize'));
		$this->view->register_modifier('pluralize',array('AdminController','pluralize'));
		$this->view->assign('page_title',"Introspection");
		$this->view->display('admin_introspect.tpl');
	}
	
	/**
	* Translates a camel case string into a string with underscores (e.g. firstName -&gt; first_name)
	* @param    string   $str    String in camel case format
	* @return   string   $str Translated into underscore format
	*/
	function from_camel_case($params) {
		$str = $params['string'];
		$str[0] = strtolower($str[0]);
		$func = create_function('$c', 'return "_" . strtolower($c[1]);');
		return preg_replace_callback('/([A-Z])/', $func, $str);
	}
 
	/**
	* Translates a string with underscores into camel case (e.g. first_name -&gt; firstName)
	* @param    string   $str                     String in underscore format
	* @param    bool     $capitalise_first_char   If true, capitalise the first char in $str
	* @return   string   $str translated into camel caps
	*/
	function to_camel_case($params) {
		$str = $params['string'];
		$capitalise_first_char = ($params['capitalize_first']) ? true : false;
		if($capitalise_first_char) {
			$str[0] = strtoupper($str[0]);
		}
		$func = create_function('$c', 'return strtoupper($c[1]);');
		return preg_replace_callback('/_([a-z])/', $func, $str);
	}

	/**
	*/
	function pluralize($str) {
		return  Inflector::pluralize($str);
	}

	/**
	*/
	function singularize($str) {
		return  Inflector::singularize($str);
	}
}
?>