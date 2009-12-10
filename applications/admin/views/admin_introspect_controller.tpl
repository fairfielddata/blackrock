
<?php
Framework::includeLibrary('misc/Status.php');
Framework::includeLibrary('misc/Validator.php');

class Admin{camelize string=$table_name capitalize_first=true}Controller extends FrameworkController {ldelim}	
	var ${$table_name}_model = null;

	public function __construct(){ldelim}
		$this->{$table_name}_model = new {camelize string=$table_name capitalize_first=true}Model();
	{rdelim}
	
	public function __destruct(){ldelim}
		$this->{$table_name}_model = null;
	{rdelim}
	
	public function indexAction(){ldelim}
		$this->listAction();
	{rdelim}
		
	public function addAction(){ldelim}
		$routes = $GLOBALS['Framework']->routes;
		if($_SERVER['REQUEST_METHOD'] == 'POST'){ldelim}
			$this->view->assign($_POST);

			$validator = new Validator();
			$validator = $this->{$table_name}_model->validateDefault($_POST);
			if($validator->is_valid()){ldelim}
				${$table_name|singularize} = $this->{$table_name}_model->populateFromRequest($_POST,$validator->validated_fields());
				${$table_name|singularize}['unique_hash'] = md5(uniqid(rand(),true));

				if(${$table_name|singularize}_id = $this->{$table_name}_model->insertRecord(${$table_name|singularize})){ldelim}
					header("Location: /".$routes['application']."/".$routes['controller']."/list");
					exit;
				{rdelim} else {ldelim}
					$validator->throw_error("Could not complete request, please check your input and try again.",'_');
					$this->view->assign('validation_errors',$validator->errors());
				{rdelim}
			{rdelim} else {ldelim}
				$this->view->assign('validation_errors',$validator->errors());
			{rdelim}
		{rdelim} else {ldelim}
		{rdelim}		
		
		$this->view->assign('page_title',"Add {$table_name|replace:"_":" "|capitalize:true|singularize}");
		$this->view->display('admin_{$table_name}_update.tpl');
	{rdelim}

	public function updateAction(){ldelim}
		$routes = $GLOBALS['Framework']->routes;
		if($_SERVER['REQUEST_METHOD'] == 'POST'){ldelim}
			$this->view->assign($_POST);

			$validator = new Validator();
			$validator->validate("id", array("required|integer" => "Please select a valid record id."));
			$validator = $this->{$table_name}_model->validateDefault($_POST);
			if($validator->is_valid()){ldelim}
				$record_id = intval($_POST['id']);
				//Debugger::out($validator->validated_fields());
				$updated_{$table_name|singularize} = $this->{$table_name}_model->populateFromRequest($_POST,$validator->validated_fields());
				$updated_{$table_name|singularize}['updated'] = 'NOW()';
				//Debugger::out($_POST);
				//Debugger::out($updated_record,1);
				if(${$table_name|singularize}_id = $this->{$table_name}_model->updateRecord($updated_{$table_name|singularize}," {$table_name}.id='$record_id'")){ldelim}
					header("Location: /".$routes['application']."/".$routes['controller']."/list");
					exit;
				{rdelim} else {ldelim}
					$validator->throw_error("Could not complete request, please check your input and try again.",'_');
					$this->view->assign('validation_errors',$validator->errors());
				{rdelim}
			{rdelim} else {ldelim}
				$this->view->assign('validation_errors',$validator->errors());
			{rdelim}
		{rdelim} else {ldelim}
			$validator = new Validator($_GET);
			$validator->validate("id", array("required|integer" => "Please select a valid record id."));
			$record_id = intval($_GET['id']);
			if($validator->is_valid()){ldelim}
				$record = $this->{$table_name}_model->getRecord(" {$table_name}.id='$record_id' ");
				//Debugger::out($record);
				$this->view->assign($record);
			{rdelim}
		{rdelim}

		$this->view->assign('page_title',"Update {$table_name|replace:"_":" "|capitalize:true|singularize}");
		$this->view->display('admin_{$table_name}_update.tpl');
	{rdelim}

	public function listAction(){ldelim}
		$routes = $GLOBALS['Framework']->routes;
		if($_SERVER['REQUEST_METHOD'] == 'POST'){ldelim}
			$this->view->assign($_POST);
			$validator = new Validator($_POST);
			$list_action = strtolower($_POST['list-action']);
			switch($list_action){ldelim}
				case "add":
					header("Location: /".$routes['application']."/".$routes['controller']."/add");
					exit;
				break;
				case "enable":
				case "disable":
				case "delete":
					$validator->validate("selected_record_ids", array("required|array" => "Please select at least one record to update."));
					if($validator->is_valid()){ldelim}
						if(is_array($_POST['selected_record_ids']))
							foreach($_POST['selected_record_ids'] as $record_id){ldelim}
								if($list_action == "enable"){ldelim}
									$updated_record['is_active']  = '1';
									$updated_record['updated']    = 'NOW()';
									$this->{$table_name}_model->updateRecord($updated_record," {$table_name}.id='".intval($record_id)."' ");
								{rdelim} else if($list_action == "disable") {ldelim}
									$updated_record['is_active'] = '0';
									$updated_record['updated']    = 'NOW()';
									$this->{$table_name}_model->updateRecord($updated_record," {$table_name}.id='".intval($record_id)."' ");
								{rdelim} else if($list_action == "delete") {ldelim}
									$this->{$table_name}_model->deleteRecord(intval($record_id));
								{rdelim} else {ldelim}
									$validator->validate("selected_record_ids", array("required|array" => "Invalid action requested, please go back and re-try."));
								{rdelim}
							{rdelim}
						header("Location: /".$routes['application']."/".$routes['controller']."/list");
						exit;
					{rdelim} else {ldelim}
						$this->view->assign('validation_errors',$validator->errors());
					{rdelim}
				break;
				default:
					header("Location: /".$routes['application']."/".$routes['controller']."/list");
					exit;
				break;
			{rdelim}
		{rdelim} else {ldelim}
		{rdelim}

		$records = $this->{$table_name}_model->getRecords();

		$this->view->assign('page_title',"List {$table_name|replace:"_":" "|capitalize:true}");
		$this->view->assign('records',$records);
		$this->view->display('admin_{$table_name}_list.tpl');

	{rdelim}

	public function viewAction(){ldelim}
		$routes = $GLOBALS['Framework']->routes;
		$validator = new Validator($_REQUEST);
		$validator->validate("id", array("required|integer" => "Please select a valid record id."));

		if($validator->is_valid()){ldelim}
			$record_id = intval($_REQUEST['id']);
			$record = $this->{$table_name}_model->getRecord(" {$table_name}.id='$record_id' ");
			$this->view->assign('record',$record);
		{rdelim} else {ldelim}
			$this->view->assign('validation_errors',$validator->errors());
		{rdelim}
		$this->view->assign('page_title',"View Advertiser");
		$this->view->display('admin_{$table_name}_view.tpl');
	{rdelim}

	public function deleteAction(){ldelim}
		$routes = $GLOBALS['Framework']->routes;
		$validator = new Validator($_REQUEST);
		$validator->validate("id", array("required|integer" => "Please select a valid record id."));
		
		if($validator->is_valid()){ldelim}
			$record_id = intval($_REQUEST['id']);
			if($this->{$table_name}_model->deleteRecord($record_id)){ldelim}
				header("Location: /".$routes['application']."/".$routes['controller']."/list");
				exit;
			{rdelim} else {ldelim}
				$validator->throw_error("Could not complete request, please check your input and try again.",'_');
				$this->view->assign('validation_errors',$validator->errors());
			{rdelim}

		{rdelim} else {ldelim}
			$this->view->assign('validation_errors',$validator->errors());
		{rdelim}
		$this->view->assign('page_title',"View Advertiser");
		$this->view->display('admin_{$table_name}_view.tpl');
	{rdelim}

{rdelim}
?>

