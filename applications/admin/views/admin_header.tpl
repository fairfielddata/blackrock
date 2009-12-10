<!DOCTYPE html PUBLIC
  "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Admin | {$view.page_title}</title>
		{*<link type="text/css" rel="stylesheet" href="/style/reset.css" />*}
		{*<link type="text/css" rel="stylesheet" href="/style/default-base.css" />*}
		{literal}
		<style>
			img	{ border:0px; }
			.clear { clear:both; }
			form { margin:0; }
			body, p, td, div, span { font-family: Arial,Helvetica, sans-serif; font-size:8.5pt; font-weight:normal; color:#333; }
			td { font-family: Arial,Helvetica, sans-serif; font-size:8pt; font-weight:normal; color:#333; }
			body { margin:1em; }
			h1 { font-size:1.6em; color:#333; margin:6px 0; font-weight:bold; }
			h2 { font-size:1.4em; color:#333; margin:5px 0; font-weight:bold; }
			h3 { font-size:1.2em; font-weight:bold; color:#333; }
			h4 { font-weight:bold; color:#333; }
			p { margin:0 0 10px 0; }
			strong { color:#000; font-weight:bold; }
			pre { font-family:fixed-width; font-size:8	pt; border:1px solid #ddd; background:#eee; padding:1em; }
			form { margin:0; padding:0; }
			fieldset { border:1px solid #ddd; padding:1em; width:97%; }
			legend { color:#666; font-weight: bold; border:1px solid #ddd; padding:0.75em; }
			textarea, input, select { font-size:8pt; }
			input[type="text"], select { width:45%; }
			textarea { width:90%; height:5em; font-family:monospace; }
			table { }
			tr th { font-size:8pt; color:#333; margin:2px 0; font-weight:normal; padding: 5px; background-color:#e0e0e0; text-align:left; }
			tr.header th { font-size:8.5pt; color:#333; margin:2px 0; font-weight:bold; padding: 5px; background-color:#e0e0e0; text-align:left; }
			tr.footer td { font-size:8.5pt; color:#333; margin:2px 0; font-weight:bold; padding: 5px; background-color:#e0e0e0; text-align:left; }
			tr.footer td input { font-size:8pt; color:#333; }
			tr.odd { background-color:#f3f3f3; }
			tr.even { background-color:#ffffff; }
			td { padding:2px; margin:0; }
			ul { padding-left:1em; margin:0 2px; }			
				ul.list_actions li, ul.list_actions li a { font-size:7.5pt; margin: 1px 0; }			
			
			div.toggle { height: 2.5em; line-height: 2em; width:150px; display:inline; }
			div.generated_code { margin:5px 0 15px 0;  }
			input.generated_code_action { font-size:9pt; width:250px; font-family:monospace; text-align:left; }
			div.administration_breadcrumbs { padding:3px 0; margin:0px; font-size:8pt; }
			div.administration_breadcrumbs a { font-size:8pt; }
		</style>
		<script type="text/javascript">
		function toggle(id) {
			var element = document.getElementById(id);
			if(element.style.visibility == "visible") {
				//alert('Hiding');
	   		element.style.visibility = "hidden";
	   		element.style.display = "none";
	  		}
			else {
				//alert('Unhiding');
				element.style.visibility = "visible";
	   		element.style.display = "block";
			}
		}
		function browseGeneratedCode(textarea_element){
			textarea_element.select();
			textarea_element.style.height='55em';
		}
		function unBrowseGeneratedCode(textarea_element){
			textarea_element.style.height='8em';
		}
		</script>
		{/literal}
	</head>
	<body>
	<div>