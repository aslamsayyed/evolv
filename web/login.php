<?php
	ob_start();
	session_start();
	$login_error = false;
	require_once '../vendor/propel/propel1/runtime/lib/Propel.php';
	if (@include('../src/Tonic/Autoloader.php')) { // use Tonic autoloader
		#new Tonic\Autoloader('myNamespace'); // add another namespace
		} elseif (!@include('../vendor/autoload.php')) { // use Composer autoloader
		die('Could not find autoloader');
	}
	Propel::init("../build/conf/evolv-conf.php");
	set_include_path("../build/classes" . PATH_SEPARATOR . get_include_path());
	
	if (isset($_POST['email']) && isset($_POST['password']))
	{		
		$query = PropelQuery::from("evolv\\orm\\User");
		$data = $query
		->filterByEmail($_POST['email'])
		->filterByPassword(md5($_POST['password']))
		->findOne();
		if($data){
			$_SESSION['UserId'] = $data->getId();
			$_SESSION['Name'] = $data->getName();
			ob_clean();
			header( 'Location: index.php' );
			die;
		}
		else{
			$login_error = true;
		}
	}
	if (isset($_GET['lo']) && $_GET['lo'] == "Y")
	{
		$_SESSION = array();
		session_unset();
		session_destroy();
		header( 'Location: login.php');
		die;
	}	
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Evolv</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Evolv">
		<meta name="author" content="Mutant Technologies">
		
		<!-- Le styles -->
		<link href="framework/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
		<style type="text/css">
			body {
			padding-top: 40px;
			padding-bottom: 40px;
			background-color: #f5f5f5;
			}
			
			.form-signin {
			max-width: 400px;
			padding: 19px 29px 29px;
			margin: 0 auto 20px;
			background-color: #fff;
			border: 1px solid #e5e5e5;
			-webkit-border-radius: 5px;
			-moz-border-radius: 5px;
			border-radius: 5px;
			-webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
			-moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
			box-shadow: 0 1px 2px rgba(0,0,0,.05);
			}
			.form-signin .form-signin-heading,
			.form-signin .checkbox {
			margin-bottom: 10px;
			}
			.form-signin input[type="text"],
			.form-signin input[type="password"] {
			font-size: 16px;
			height: auto;
			margin-bottom: 15px;
			padding: 7px 9px;
			}
			
		</style>
		<!-- Favicons -->
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="favicon.png">
		<link rel="favicon.png">
			
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-md-6 col-md-offset-3">
					<form class="form-signin" action="/web/login.php" method="post">
						<h4 class="form-signin-heading">Evolv</h4>
						<?php if($login_error){
							echo '<div style="color:#E00000" >Invalid email or password</div>';
						}
						?>
							<hr />
						<div class="form-group">
							<input class="form-control" name="email" placeholder="Email address" required type="email" autocomplete='off'>
						</div>
						<div class="form-group">
							<input class="form-control" name="password" placeholder="Password" required type="password" autocomplete='off'>
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-primary">Log In</button>
						</div>
						
					</form>
				</div>
			</div>
		</div> <!-- /container -->
		
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="framework/jquery/1.10.2/jquery.min.js"></script>
		<script src="framework/bootstrap/3.0.3/js/bootstrap.js"></script>
		
	</body>
</html>