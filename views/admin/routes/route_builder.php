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
					<h4>Route builder <i class="fas fa-list"></i></h4>
				</div>

				<div class="card-body w-100">

					<form id="stations_form" method="post" action="/controllers/routes_controller.php">

						<input type="hidden" name="_action" value="ADD_ROUTE">
						<input type="submit" class="btn btn-primary" value="Add Route">
						<br>
						<br>

						<div id="form_group">
							<div class="form-row" id="form_row">
								<div class="col">
									<input type="text" class="form-control" name="station_name[]" placeholder="Station Name">
								</div>
								<div class="col">
									<input type="text" class="form-control" name="distance[]" placeholder="KM from first station" value="0">
								</div>
								<div class="col">
									<input type="text" class="form-control" name="time[]" placeholder="Time from first station" value="0">
								</div>
							</div>
							<br>
						</div>

						<br>

					</form>

					<button onclick="add_station_form_row()" class="btn btn-primary">Add new station</button>

					<script>
                        function add_station_form_row() {
                            new_form = "<div class=\"form-row\">" +
                                "<div class=\"col\">" +
                                "<input type=\"text\" class=\"form-control\" name=\"station_name[]\" placeholder=\"Station Name\">" +
                                "</div>" +
                                "<div class=\"col\">" +
                                "<input type=\"text\" class=\"form-control\" name=\"distance[]\" placeholder=\"KM from first station\">" +
                                "</div>" +
                                "<div class=\"col\">" +
                                "<input type=\"text\" class=\"form-control\" name=\"time[]\" placeholder=\"Time from first station\">" +
                                "</div>" +
                                "</div><br>";

                            document.getElementById('form_group').innerHTML += new_form;
                        }
					</script>

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