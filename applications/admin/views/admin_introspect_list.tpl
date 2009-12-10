
{ldelim}include file='admin_header.tpl'{rdelim}
<h1>{$table_name|replace:"_":" "|capitalize:true}</h1>
<form class="rating" action="{ldelim}$framework.self{rdelim}" method="POST">
	<fieldset style="margin:0 0.25em;">
		<div class="administration_breadcrumbs">
			<a href="/{ldelim}$framework.application{rdelim}/index">Administration</a>
			&gt; {$table_name|replace:"_":" "|capitalize:true} List
		</div>
		<br />

		{ldelim}if $view.validation_errors{rdelim}
		<ul class="validation_errors">
		{ldelim}foreach from=$view.validation_errors key=field item=validation_error{rdelim}
			<li>{ldelim}$validation_error{rdelim}</li>
		{ldelim}/foreach{rdelim}
		</ul>
		{ldelim}/if{rdelim}

		<legend>{$table_name|replace:"_":" "|capitalize:true} List</legend>
		{ldelim}if $view.records|@is_array AND $view.records|@count{rdelim}
		<table class="tabulated" cellpadding="0" cellspacing="0" width="100%">
		<tr class="header">
{foreach from=$table.columns key=column_name item=column}
			<th>{$column_name|lower|replace:"_":" "|capitalize:true}</th>
{/foreach}
			<th width="80">Actions</th>
			<th width="13">&nbsp;</th>
		</tr>
		{ldelim}foreach from=$view.records item=record{rdelim}
		<tr>
{foreach from=$table.columns key=column_name item=column}
{if $column->name eq 'id'}
			<td align="center"><a class="action" href="/{ldelim}$framework.application{rdelim}/{ldelim}$framework.controller{rdelim}/view?id={ldelim}$record.id{rdelim}">{ldelim}$record.{$column_name|lower}|strip_tags|truncate:50:'...':true:true{rdelim}</a></td>
{elseif $column->type eq 'text'}
			<td>{ldelim}$record.{$column_name|lower}|strip_tags|truncate:50:'...':true:true{rdelim}</td>
{elseif $column->type eq 'tinyint' AND $column->max_length eq '1' }
			<td>{ldelim}if $record.{$column_name|lower} eq 1{rdelim}Yes{ldelim}else{rdelim}No{ldelim}/if{rdelim}</td>
{elseif $column->type eq 'datetime'}
			<td>{ldelim}$record.{$column_name|lower}|date_format:"%Y-%m-%d %H:%I:%S"{rdelim}</td>
{else}
			<td>{ldelim}$record.{$column_name|lower}{rdelim}</td>
{/if}
{/foreach}
			<td nowrap="nowrap">
				<ul>
					<li><a class="action" href="/{ldelim}$framework.application{rdelim}/{ldelim}$framework.controller{rdelim}/view?id={ldelim}$record.id{rdelim}">View</a></li>
					<li><a class="action" href="/{ldelim}$framework.application{rdelim}/{ldelim}$framework.controller{rdelim}/update?id={ldelim}$record.id{rdelim}">Update</a></li>
					<li><a class="action" href="javascript:;" onClick="javascript:if(confirm('Delete Record?')){ldelim}ldelim{rdelim} window.location='/{ldelim}$framework.application{rdelim}/{ldelim}$framework.controller{rdelim}/delete?id={ldelim}$record.id{rdelim}' {ldelim}rdelim{rdelim};">Delete</a></li>
				</ul>
			</td>
			<td>
				{literal}<input type="checkbox" name="selected_record_ids[{$record.id}]" value="{$record.id}" {if $view.selected_record_ids[$record.id]}checked="checked"{/if}>{/literal}
			</td>
		</tr>
		{ldelim}/foreach{rdelim}
		<tr class="footer">
			<td colspan="{php}echo (count($this->_tpl_vars['table']['column_names']) + 2 - ceil(count($this->_tpl_vars['table']['column_names'])/2));{/php}" style="text-align:left;">
				Results: ({ldelim}$view.records|@count{rdelim})
			</td>
			<td colspan="{php}echo ceil(count($this->_tpl_vars['table']['column_names'])/2);{/php}" style="text-align:right;">
				<input type="submit" name="list-action" value="Add">
				<input type="submit" name="list-action" value="Enable">
				<input type="submit" name="list-action" value="Disable">
				<input type="submit" name="list-action" value="Delete">
			</td>
		</tr>
		</table>
		<br />
		<div class="administration_breadcrumbs">
			<a href="/{ldelim}$framework.application{rdelim}/index">Administration</a>
			&gt; {$table_name|replace:"_":" "|capitalize:true} List
		</div>
		{ldelim}else{rdelim}
		<p>Your request returned no results <a href="/{ldelim}$framework.application{rdelim}/{ldelim}$framework.controller{rdelim}/add">Add Record</a></p>
		{ldelim}/if{rdelim}
	</fieldset>
</form>
{ldelim}include file='admin_footer.tpl'{rdelim}
