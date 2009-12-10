
{ldelim}include file='admin_header.tpl'{rdelim}
<h1>View {$table_name|replace:"_":" "|capitalize:true}</h1>
<form class="rating" action="{ldelim}$framework.self{rdelim}" method="POST" enctype="multipart/form-data">
	<fieldset style="margin:0 0.25em;">
		<legend>{$table_name|replace:"_":" "|capitalize:true} ID: {ldelim}$view.record.id{rdelim} </legend>
		<div class="administration_breadcrumbs">
			<a href="/{ldelim}$framework.application{rdelim}/index">Administration</a>
			&gt; <a href="/{ldelim}$framework.application{rdelim}/{ldelim}$framework.controller{rdelim}/list">{$table_name|replace:"_":" "|capitalize:true} List</a>
			&gt; {$table_name|replace:"_":" "|capitalize:true} View
		</div>
		<br />

		{ldelim}if $view.validation_errors{rdelim}
		<ul class="validation_errors">
		{ldelim}foreach from=$view.validation_errors key=field item=validation_error{rdelim}
			<li>{ldelim}$validation_error{rdelim}</li>
		{ldelim}/foreach{rdelim}
		</ul>
		{ldelim}/if{rdelim}
		
		<table>
{foreach from=$table.column_names item=column_name}
			<tr>
				<td>{$column_name|replace:"_":" "|capitalize:true}</td> 
				<td>{ldelim}$view.record.{$column_name}{rdelim}</td>
			</tr>
{/foreach}
		</table>
		<br />
		<div class="administration_breadcrumbs">
			<a href="/{ldelim}$framework.application{rdelim}/index">Administration</a>
			&gt; <a href="/{ldelim}$framework.application{rdelim}/{ldelim}$framework.controller{rdelim}/list">{$table_name|replace:"_":" "|capitalize:true} List</a>
			&gt; {$table_name|replace:"_":" "|capitalize:true} View
		</div>
{ldelim}include file='admin_footer.tpl'{rdelim}
