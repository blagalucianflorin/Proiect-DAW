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
					<h4>Add train <i class="fas fa-plus"></i></h4>
				</div>

				<div class="card-body w-100">

					<form method="post" action="/controllers/trains_controller.php">
						<div class="form-group row">
							<label for="staticEmail" class="col-sm-2 col-form-label">Route ID</label>
							<div class="col-sm-10">
								<input type="text" readonly class="form-control-plaintext" name="route_id" value="<?php echo $_GET['route_id'] ?>">
							</div>

							<div class="form-group">
								<label for="formGroupExampleInput1">Cost per KM</label>
								<input type="number" class="form-control" id="formGroupExampleInput1" placeholder="1" name="km_cost" step="0.1">
							</div>

							<div class="form-group">
								<label for="formGroupExampleInput2">Class 1 Wagons</label>
								<input type="number" class="form-control" id="formGroupExampleInput2" placeholder="1" name="first_class_wagons">
							</div>

							<div class="form-group">
								<label for="formGroupExampleInput3">Class 2 Wagons</label>
								<input type="number" class="form-control" id="formGroupExampleInput3" placeholder="1" name="second_class_wagons">
							</div>

							<div class="form-group">
								<label for="formGroupExampleInput4">Start Date</label>
								<input type="text" class="form-control" id="formGroupExampleInput4" placeholder="2020-11-20 09:20" name="start_date">
							</div>

							<div class="table-responsive">
								<table class="table table-stripped table-bordered" style="display: table">
									<thead>
									<tr>
										<th scope="col">Station</th>
										<th scope="col">Distance</th>
										<th scope="col">Minutes To distance</th>
										<th scope="col">Add</th>
									</tr>
									</thead>
									<tbody>
							<?php
								$stations = routes_controller::get_route_stations ($_GET['route_id']);

								foreach ($stations as $station) { ?>

									<tr>
										<th scope="row"><?php echo $station[0] ?></th>
										<td><?php echo $station[1] ?></td>
										<td><?php echo $station[2] ?></td>
										<td>
											<input type="checkbox" name="stations[]" value="<?php echo $station[0] . " " . $station[1] . " " . $station[2]  ?>">
										</td>
									</tr>

								<?php } ?>

							</tbody>
							</table>
						</div>
						</div>

						<input type="hidden" name="_action" value="ADD_TRAIN">

						<input type="submit" class="btn btn-primary" value="Add">

					</form>

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