<?php

if (session_id () == '') session_start();

require_once ($_SERVER['DOCUMENT_ROOT'] . '/controllers/controller.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/controllers/users_controller.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/controllers/users_activity_controller.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/views/includes/essentials.php');

if (users_controller::is_logged_in () == false)
	controller::redirect ('/');

?>

<!doctype html>
<html lang="en" style="height: 90%">

<?php require_once ($_SERVER['DOCUMENT_ROOT'] . '/views/includes/head.php'); ?>

<body class="h-100" style="background: #E8E8E8">

<?php require_once ($_SERVER['DOCUMENT_ROOT'] . '/views/includes/header.php'); ?>

<div class="container-fluid h-100">
	<div class="row h-100 justify-content-center align-items-center">
		<div class="col-sm-3"></div>

		<div class="col-sm-6">

			<div class="card border-primary" style="width: auto;">

				<div class="card-header">
					<h4>Account activities <i class="fas fa-sign-in-alt"></i></h4>
				</div>

				<div class="card-body text-center w-100">

					<div class="table-responsive">
						<table class="table table-stripped table-bordered" style="display: table">
							<thead>
							<tr>
								<th scope="col">Action</th>
								<th scope="col">Date</th>
								<th scope="col">IP</th>
							</tr>
							</thead>
							<tbody>
							<?php
							$activities = users_activity_controller::get_activities (users_controller::is_logged_in ());

							foreach ($activities as $activity) { ?>

								<tr>
									<th scope="row"><?php echo $activity['action'] ?></th>
									<td><?php echo $activity['date'] ?></td>
									<td><?php echo $activity['ip'] ?></td>
								</tr>

							<?php } ?>

							</tbody>
						</table>
					</div>

				</div>

			</div>
			<br>

		</div>

		<div class="col-sm-3"></div>
	</div>
</div>

<?php require_once ($_SERVER['DOCUMENT_ROOT'] . '/views/includes/footer.php'); ?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html>