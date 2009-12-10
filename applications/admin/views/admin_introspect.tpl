{include file='admin_header.tpl'}
<h1>Framework Introspect</h1>
	<fieldset style="margin:0 0.25em;">
		<legend>Database Tables</legend>

		<div class="administration_breadcrumbs">
			<a href="/{$framework.application}/index">Administration</a>
			&gt; Data Introspection
		</div>

		{if $view.validation_errors}
		<ul class="validation_errors">
		{foreach from=$view.validation_errors key=field item=validation_error}
			<li>{$validation_error}</li>
		{/foreach}
		</ul>
		{/if}
		{if $view.status}
		<ul class="status">
		{foreach from=$view.status key=field item=status_item}
			<li>{$status_item}</li>
		{/foreach}
		</ul>
		{/if}

		<br />
		<hr size="1" />
		<a href="javascript:;" onClick="javascript:toggle('div_tables_dump');">Full Table Data Dump</a>
		<div id="div_tables_dump" style="display:none; visibility:hidden; clear:both;">
		{$view.tables_dump}
		</div>
		<hr size="1" />
		<br />
		
		{foreach from=$view.tables key=table_name item=table}
		<h2>{camelize string=$table_name capitalize_first=true} Management (CRUD: {$table_name}) </h2>
		<form class="rating" action="{$framework.self}" method="POST">
			<input type="hidden" name="introspect_source" value="{$table_name}">
			<input type="hidden" name="application" value="/{$framework.application}">
			
			<div class="toggle">[ <a href="javascript:;" onClick="javascript:toggle('div_{$table_name}');">+</a> ] View {$table_name|replace:"_":" "|capitalize} CRUD</div>
			
			{* <a href="javascript:;" onClick="javascript:toggle('div_{$table_name}');">View {$table_name|replace:"_":" "|capitalize} Gen</a> *}
			<div id="div_{$table_name}" style="display:none; visibility:hidden; clear:both;">
			{* <div id="div_{$table_name}" style="clear:both;"> *}
				<!-- DATA MODEL CONTROLLER -->
				<div class="generated_code">
					{capture name='model_file_name'}{strip}{camelize string=$table_name capitalize_first=true}Model.php{/strip}{/capture}
					<input type="hidden" name="generated_file_name[model]" value="{$smarty.capture.model_file_name}" />
					<strong>Model:</strong> {$smarty.capture.model_file_name}<br />
					<textarea name="generated_file_content[model]" onFocus="javascript:browseGeneratedCode(this);" onBlur="javascript:unBrowseGeneratedCode(this);" >{include file='admin_introspect_model.tpl'}</textarea><br />
					<input class="generated_code_action" type="submit" name="introspect_action" value="Install Model Class" >
				</div>
				
				<!-- ADMIN CONTROLLER -->
				<div class="generated_code">
					{capture name='controller_file_name'}{strip}Admin{camelize string=$table_name capitalize_first=true}Controller.php{/strip}{/capture}
					<input type="hidden" name="generated_file_name[controller]" value="{$smarty.capture.controller_file_name}" />
					<strong>Admin Controller:</strong> {$smarty.capture.controller_file_name}<br />
					<textarea name="generated_file_content[controller]" onFocus="javascript:browseGeneratedCode(this);" onBlur="javascript:unBrowseGeneratedCode(this);" >{include file='admin_introspect_controller.tpl'}</textarea><br />
					<input class="generated_code_action" type="submit" name="introspect_action" value="Install Controller Class" >
				</div>
				
				<!-- LIST TEMPLATE -->
				<div class="generated_code">
					{capture name='list_file_name'}{strip}admin_{$table_name|lower}_list.tpl{/strip}{/capture}
					<input type="hidden" name="generated_file_name[list]" value="{$smarty.capture.list_file_name}" />
					<strong>List Template:</strong> {$smarty.capture.list_file_name}<br />
					<textarea name="generated_file_content[list]" onFocus="javascript:browseGeneratedCode(this);" onBlur="javascript:unBrowseGeneratedCode(this);" >{include file='admin_introspect_list.tpl'}</textarea><br />
					<input class="generated_code_action" type="submit" name="introspect_action" value="Install List Template" >
				</div>
				
				<!-- UPDATE TEMPLATE -->
				<div class="generated_code">
					{capture name='update_file_name'}{strip}admin_{$table_name|lower}_update.tpl{/strip}{/capture}
					<input type="hidden" name="generated_file_name[update]" value="{$smarty.capture.update_file_name}" />
					<strong>Update Template:</strong>{$smarty.capture.update_file_name}<br />
					<textarea  name="generated_file_content[update]"onFocus="javascript:browseGeneratedCode(this);" onBlur="javascript:unBrowseGeneratedCode(this);" >{include file='admin_introspect_update.tpl'}</textarea><br />
					<input class="generated_code_action" type="submit" name="introspect_action" value="Install Update Template" >
				</div>
				
				<!-- VIEW TEMPLATE -->
				<div class="generated_code">
					{capture name='view_file_name'}{strip}admin_{$table_name|lower}_view.tpl{/strip}{/capture}
					<input type="hidden" name="generated_file_name[view]" value="{$smarty.capture.view_file_name}" />
					<strong>View Template:</strong> {$smarty.capture.view_file_name}<br />
					<textarea name="generated_file_content[view]" onFocus="javascript:browseGeneratedCode(this);" onBlur="javascript:unBrowseGeneratedCode(this);" >{include file='admin_introspect_view.tpl'}</textarea><br />
					<input class="generated_code_action" type="submit" name="introspect_action" value="Install View Template" >
				</div>
		
				{*
				<!-- ADDITIONAL CODE GENERATORS TEMPLATE -->
				<div class="generated_code">
					{capture name='CODE_file_name'}{strip}{$table_name|lower}CODE.tpl{/strip}{/capture}
					<input type="hidden" name="generated_file_name[CODE]" value="{$smarty.capture.CODE_file_name}" />
					<strong>View Template:</strong> {$smarty.capture.CODE_file_name}<br />
					<textarea name="generated_file_content[CODE]" onFocus="javascript:browseGeneratedCode(this);" onBlur="javascript:unBrowseGeneratedCode(this);" >{include file='admin_introspect_view.tpl'}</textarea><br />
					<input class="generated_code_action" type="submit" name="introspect_action" value="Install CODE" >
				</div>
				*}
		
				<br />
				<input class="generated_code_action" type="submit" name="introspect_action" value="Install All /{$framework.application}/" >
				<br />
				<br />
			</div>
			<div class="toggle">[ <a href="javascript:;" onClick="javascript:toggle('div_{$table_name}');">-</a> ] Hide {$table_name|replace:"_":" "|capitalize} CRUD</div>
			<hr size="1" />
		</form>
		<br />
		{/foreach}
	<br />
	<br />
	<div class="administration_breadcrumbs">
		<a href="/{$framework.application}/index">Administration</a>
		&gt; Data Introspection
	</div>
</fieldset>
{include file='admin_footer.tpl'}