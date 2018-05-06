<?php
	include 'inc/header.php';
	include 'lib/User.php';
	Session::checkLogin();
?>
<?php
	$user = new User();
	if ($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['login'])) {
		$usrLogin = $user->userLogin($_POST);
	}
?>
<div class="panel panel-default"> <!-- panel start -->

		<div class="panel-heading">

			<h2>User Login</h2>

		</div>

		<div class="panel-body"> <!-- panel body start -->

			<div class="col-md-6 col-md-offset-3" ">
<?PHP
	if (isset($usrLogin)) {
		echo $usrLogin;
	}
?>
				<form action="" method="POST">

					<div class="form-group">

						<label for="email">Email</label>
					    <input type="text" id="email" name="email" class="form-control"/>

					</div>

					<div class="form-group">

						<label for="password">Password</label>
					    <input type="password" id="password" name="password" class="form-control"/>
					</div>

					<button type="submit" name="login" class="btn btn-success">Login</button>

				</form>

			</div>

		</div> <!-- panel-body end -->

	</div> <!-- panel end -->

<?php
include 'inc/footer.php';
?>