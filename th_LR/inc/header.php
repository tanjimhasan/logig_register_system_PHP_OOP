<?php
	$filepath = realpath(dirname(__FILE__));
	include_once $filepath.'/../lib/Session.php';
	Session::init();
?>
<!DOCTYPE>
<html>
<head>
	<title>Log in Register System</title>
	<link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="inc/bootstrap.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="inc/bootstrap.min.js"></script>
	<script type="text/javascript" src="inc/jquery.min.js"></script>
</head>
<?php
if (isset($_GET['action']) && $_GET['action'] == "logout") {
	Session::destroy();
}
?>

<body>
<div class="container">
	<nav class="navbar navbar-default">
			<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="index.php">Log in Register System PHP & PDO</a>
			</div>
			<ul class="nav navbar-nav pull-right">
			<?php
				$id = Session::get("id");
				$userlogin = Session::get("login");
				if ($userlogin == true) {
					
			?>	<li><a href="index.php">Home</a></li>
				<li><a href="profile.php?id=<?php echo $id; ?>">Profile</a></li>
				<li><a href="?action=logout">Log Out</a></li>
				<?php
					# code...
				}else{
				?>
				<li><a href="login.php">Log in</a></li>
				<li><a href="register.php">Register</a></li>
				<?php
					}
				?>
			</ul>
		</div>
	</nav>