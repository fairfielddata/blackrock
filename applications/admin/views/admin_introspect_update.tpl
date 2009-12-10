
{ldelim}include file='admin_header.tpl'{rdelim}
<h1>{$table_name|replace:"_":" "|capitalize:true}</h1>
<form class="rating" action="{ldelim}$framework.self{rdelim}" method="POST" enctype="multipart/form-data">
	<fieldset style="margin:0 0.25em;">
		<legend>{$table_name|replace:"_":" "|capitalize:true} Data</legend>
		<div class="administration_breadcrumbs">
			<a href="/{ldelim}$framework.application{rdelim}/index">Administration</a>
			&gt; <a href="/{ldelim}$framework.application{rdelim}/{ldelim}$framework.controller{rdelim}/list">{$table_name|replace:"_":" "|capitalize:true} List</a>
			&gt; {$table_name|replace:"_":" "|capitalize:true} Add/Update
		</div>
		<br />

		{ldelim}if $view.validation_errors{rdelim}
		<ul class="validation_errors">
		{ldelim}foreach from=$view.validation_errors key=field item=validation_error{rdelim}
			<li>{ldelim}$validation_error{rdelim}</li>
		{ldelim}/foreach{rdelim}
		</ul>
		{ldelim}/if{rdelim}
		
{foreach from=$table.columns key=column_name item=column}
{if 
	strtolower($column_name) eq 'id' 
	OR strtolower($column_name) eq 'unique_hash' 
	OR strtolower($column_name) eq 'created' 
	OR strtolower($column_name) eq 'updated'
}		<input type="hidden" id="{$column_name|lower}" name="{$column_name|lower}" value="{ldelim}$view.{$column_name|lower}{rdelim}" {if $column->max_length gt 0}maxlength="{$column->max_length}"{/if} />
{else} 
{if $column->type eq 'text'}
		<label for="{$column_name|lower}">{$column_name|replace:"_":" "|lower|capitalize:true}</label><br />
		<textarea id="{$column_name|lower}" name="{$column_name|lower}">{ldelim}$view.{$column_name|lower}{rdelim}&lt/textarea&gt<br />
		<br />
{elseif $column->type eq 'tinyint' AND $column->max_length eq '1' }
		<input type="checkbox" id="{$column_name|lower}" name="{$column_name|lower}" value="1" {ldelim}if $view.{$column_name|lower} eq '1'{rdelim}checked="checked"{ldelim}/if{rdelim} />
		<label for="{$column_name|lower}">{$column_name|replace:"_":" "|lower|capitalize:true}</label><br />
		<br />
{else}
		<label for="{$column_name|lower}">{$column_name|replace:"_":" "|lower|capitalize:true}</label><br />
		<input type="text" id="{$column_name|lower}" name="{$column_name|lower}" value="{ldelim}$view.{$column_name|lower}{rdelim}" {if $column->max_length gt 0}maxlength="{$column->max_length}"{/if} /><br />
		<br />
{/if}
{/if}
{/foreach}
		<br />
		<input type="submit" id="submit" name="submit" value="Submit" /><br />
		<br />
		<div class="administration_breadcrumbs">
			<a href="/{ldelim}$framework.application{rdelim}/index">Administration</a>
			&gt; <a href="/{ldelim}$framework.application{rdelim}/{ldelim}$framework.controller{rdelim}/list">{$table_name|replace:"_":" "|capitalize:true} List</a>
			&gt; {$table_name|replace:"_":" "|capitalize:true} Add/Update
		</div>
	</fieldset>
</form>
{ldelim}include file='admin_footer.tpl'{rdelim}
