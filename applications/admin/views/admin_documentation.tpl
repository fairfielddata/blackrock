<h1>Framework Documentation</h1>
<ol> 
	<li>
		<h2>Overview</h2>
		<p></p>
		<ol>
			<li>
				<h3>Where is everything?</h3>
				<p>Your applications are separated from your document root.</p>
				<p>Here's a description of the layout</p>
				<p>
					<pre>
					/docs (DOCUMENT_ROOT)
						/.htaccess (rewrites requests to index.php, sets php include)
						/index.php (routes requests to the framework)
						
					/applications
						/store
							/models (access to the database) and behaviours
							/controllers
							/views
							/data
					/libs
						/Framework.php (Big Poppa)
							/Framework::Framework
							/Framework::FrameworkModel
							/Framework::FrameworkView
							/Framework::FrameworkControllers
					</pre>
			</p>
			</li>		
			<li>
				<h3>How do I do stuff?</h3>
				<p>The default URL scheme is: 
				<pre>
				Application/Controller/Action
				</pre>
				</p>
				<p>A couple of examples:
				<pre>/store/products/list</pre>
				</p>				
				<p>
				Maps to:
				<pre>
				/applications
					/store (Our Application)
						/controllers	
							/ProductsController.php (Controller that was called)
								/ProductsController::listAction() (
				</pre>
				</p>
	</li>
</ol>