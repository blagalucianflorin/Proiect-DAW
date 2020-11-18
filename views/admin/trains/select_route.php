<?php

if (session_id () == '') session_start();

require_once ($_SERVER['DOCUMENT_ROOT'] . '/controllers/permissions_controller.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/controllers/controller.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/controllers/users_controller.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/controllers/routes_controller.php');

if (permissions_controller::is_admin () == false)
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
					<h4>Routes <i class="fas fa-list"></i></h4>
				</div>

				<div class="card-body w-100">


					<?php
					$routes = routes_controller::get_routes ();
					if (count ($routes) != 0) { ?>
					<div class="table-responsive">
						<table class="table table-stripped table-bordered" style="display: table">
							<thead>
							<tr>
								<th scope="col">ID</th>
								<th scope="col">Start Station</th>
								<th scope="col">End Station</th>
								<th scope="col">Link</th>
							</tr>
							</thead>
							<tbody>
							<?php
							foreach ($routes as $route) { ?>

							<tr>
								<th scope="row"><?php echo $route['route_id'] ?></th>
								<td><?php echo $route['start_station'] ?></td>
								<td><?php echo $route['end_station'] ?></td>
								<td><a href="<?php echo "/views/admin/trains/add_trains.php?route_id=" . $route['route_id'] ?>">Select</a></td>
							</tr>

					<?php } ?>

                            </tbody>
                        </table>
                    </div>

					<?php } else {?>

						<div class="card text-white bg-danger mb-3">
							<div class="card-body">
								<p class="card-text">No routes available right now</p>
							</div>
						</div>

					<?php } ?>

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