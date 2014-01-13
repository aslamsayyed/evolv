<?php 
?>
<!DOCTYPE html>
<html lang="en" ng-app="evolvApp"><head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Evolv">
	<meta name="author" content="Mutant Technologies">
	<title>Evolv</title>
	
	<!-- Bootstrap core CSS -->
	<link href="framework/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
	
	<!-- Documentation extras -->
	<link href="framework/bootstrap/docs-assets/css/docs.css" rel="stylesheet">
	<link href="framework/bootstrap/docs-assets/css/pygments-manni.css" rel="stylesheet">
	<!--[if lt IE 9]><script src="framework/bootstrap/docs-assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
	
	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->
	
	<!-- Angularjs -->
	<script src="framework/angularjs/1.2.6/angular.min.js"></script>
	<script src="framework/angularjs/1.2.6/angular-animate.min.js"></script>
	<script src="framework/angularjs/1.2.6/angular-resource.min.js"></script>
	<script src="framework/angularjs/1.2.6/angular-route.min.js"></script>
	<script src="framework/angularjs/1.2.6/angular-sanitize.min.js"></script>
	<script src="framework/angularjs/0.9.0/ui-bootstrap-tpls-0.9.0.js"></script>
	<!--script src="js/animations.js"></script-->
	<!--script src="js/filters.js"></script-->
	
	<!-- Application -->
	<link href="css/app.css" rel="stylesheet">
	<script src="js/app.js"></script>
	<script src="js/controllers.js"></script>
	<script src="js/services.js"></script>
	
	<!-- Favicons -->
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="favicon.png">
	<link rel="favicon.png">
	<style type="text/css"></style><style id="holderjs-style" type="text/css"></style>
	</head>
	<body>
		<!--a class="sr-only" href="#content">Skip to main content</a-->
		<!-- Docs master nav -->
		<header class="navbar navbar-inverse navbar-fixed-top bs-docs-nav" role="banner">
			<div class="container">
				<div class="navbar-header">
					<button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a href="#" class="navbar-brand">Evolv</a>
				</div>
				<nav class="collapse navbar-collapse bs-navbar-collapse pull-right" role="navigation"  ng-controller="HeaderInfoCtrl">
					<ul class="nav navbar-nav">
						<li>
							<a href="#/"><span class="glyphicon glyphicon-home"></span> Home</a>
						</li>
						<li>
							<a href="#/myaccount"><span class="glyphicon glyphicon-user"></span> {{headerInfo.name}}</a>
						</li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li>
							<a href="login.php?lo=Y">Logout</a>
						</li>
					</ul>
				</nav>
			</div>
		</header>
		
		<div class="container bs-docs-container">
			<div class="row">
				<div class="col-md-3">
					<div class="bs-sidebar hidden-print affix-top" role="complementary">
						<ul class="nav bs-sidenav">
							<li class="active">
								<a>Menu</a>
								<ul class="nav">
									<li><a href="#/user">Users</a></li>
									<li><a href="#/project">Projects</a></li>
									<li><a href="#/sprint">Sprints</a></li>
									<li><a href="#/task">Tasks</a></li>
									<li><a href="#/tag">Tags</a></li>
								</ul>
							</li>
						</ul>
					</div>
				</div>
				<div class="col-md-9" role="main"  ng-view id="views">
				</div>
			</div>
			
			<!-- Footer
			================================================== -->
			<!--footer class="bs-footer" role="contentinfo">
				<div class="container">
					Prosource
				</div>
			</footer!-->
			
			<!-- Bootstrap core JavaScript
			================================================== -->
			<!-- Placed at the end of the document so the pages load faster -->
			<script src="framework/jquery/1.10.2/jquery.min.js"></script>
			<script src="framework/bootstrap/3.0.3/js/bootstrap.js"></script>
			<script src="framework/bootstrap/docs-assets/js/holder.js"></script>
			<script src="framework/bootstrap/docs-assets/js/application.js"></script>
		</div>
	</body>
</html>