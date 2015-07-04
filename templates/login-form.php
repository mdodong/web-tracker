<?php
include( TEMPLATE_PATH . "/inc/header.inc.php" );
?>
<div class="container">
			<div class="row">
				<div class="col-md-2"></div>
				<div class="col-md-4"><br>
					<div class="panel panel-info">
					<div class="panel-heading">
						<h4 class="panel-title">Login</h4>
					</div>
					<div class="panel-body">
						<?php if(isset($_GET['login_error'])) : ?>

						<label class="label label-danger">Invalid Login</label>
						<?php else : ?>
						<label class="hidden">Login</label>
						<?php endif; ?>
						<form method="POST" action="?view=login&login=submit">
						<label for="username">Username</label>
						<input id="username"  name="ctt_username" type="text" class="form-control">
				
						<label for="password">Password</label>
						<input id="password"  name="ctt_password" type="password" class="form-control">
						<br>
						<input type="submit" class="form-control btn-info" value="Login">
						</form>
					</div>
				</div>
				<div class="col-md-2"></div>
			</div>
<?php
include( TEMPLATE_PATH . "/inc/footer.inc.php" );
?>
