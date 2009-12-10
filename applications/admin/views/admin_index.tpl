{include file='admin_header.tpl'}
<h1>Administration Root</h1>
<form class="rating" action="{$framework.self}" method="POST">
	<fieldset style="margin:0 0.25em;">
		<legend>List of Admin Actions</legend>

		<p>Please note, many of these controller actions below require request parameters to execute correctly.</p>
		{foreach from=$view.admin_controllers item=admin_controller}
		<p><strong>{$admin_controller.description}</strong><br />
		{foreach from=$admin_controller.actions key=action item=admin_controller_action_url}
		<ul>
			<li><a href="{$admin_controller_action_url}">{$controller} {$action|ucwords}</a></li>
		</ul>
		{/foreach}
		</p>		
		<br />
		{foreachelse}
		<p>No action/controllers defined</p>
		{/foreach}
		[ <a href="javascript:;" onClick="javascript:toggle('admin_documentation');">Documentation</a> ]
		<div id="admin_documentation" style="display:none; visible:hidden;">
		{include file='admin_documentation.tpl'}
		</div>
		<br />
	</fieldset>
</form>
{include file='admin_footer.tpl'}