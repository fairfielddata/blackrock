
<?php
class {camelize string=$table_name capitalize_first=true}Model extends FrameworkModel {ldelim}
	/**
	* Constructor
	*/
	public function __construct(){ldelim}
		$this->setTableToModel("{$table_name}");
	{rdelim}

	/**
	* Destructor
	*/
	public function __destruct(){ldelim}
	{rdelim}

	/**
	* Validator
	*/
	public function validateDefault($_REQUEST_CONTAINER){ldelim}
		$validator = new Validator($_REQUEST_CONTAINER);
{foreach from=$table.columns key=column_name item=column}
		$validator->validate('{$column_name|lower}', array("required{if $column->type eq 'tinyint' OR $column->type eq 'integer'}|integer{/if}{if $column->max_length gt 0}|maxchars::{$column->max_length}{/if}" =>"Please provide a valid {$column_name|lower|replace:"_":" "|capitalize:true}."));
{/foreach}

		return $validator;
	{rdelim}
{rdelim}
?>
