<?php

if (session_id () == '') session_start();

require_once ($_SERVER['DOCUMENT_ROOT'] . '/controllers/permissions_controller.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/controllers/trains_controller.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>
<!doctype html>
<html lang="en" style="height: 90%">

<?php require_once ($_SERVER['DOCUMENT_ROOT'] . '/views/includes/head.php'); ?>

<body class="h-100" style="background: #E8E8E8">

<?php require_once ($_SERVER['DOCUMENT_ROOT'] . '/views/includes/header.php'); ?>

<div class="container-fluid h-100">
    <div class="row h-100 justify-content-center align-items-center">
        <div class="col-sm-1"></div>

        <div class="col-sm-3">

            <div class="card border-primary" style="width: auto;">

                <div class="card-header">
                    <h4>Search Trains <i class="fas fa-list"></i></h4>
                </div>

                <div class="card-body w-100">

                    <form method="post" action="/controllers/trains_controller.php">

                        <div class="form-group">
                            <label for="start_station">Start Station</label>
                            <input type="text" class="form-control" id="start_station" name="start_station">
                        </div>

                        <div class="form-group">
                            <label for="end_station">End Station</label>
                            <input type="text" class="form-control" id="end_station" name="end_station">
                        </div>

                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="text" class="form-control" id="date" name="date">
                        </div>

                        <input type="hidden" name="_action" value="SEARCH_TRAIN">
                        <input type="submit" class="btn btn-primary" value="Search">
                    </form>

                </div>

            </div>
            <br>

        </div>

        <div class="col-sm-7">

            <div class="card border-primary" style="width: auto;">

                <div class="card-header">
                    <h4>Search Results <i class="fas fa-list"></i></h4>
                </div>

                <div class="card-body w-100">

                    <div class="table-responsive">
                        <table class="table table-stripped table-bordered" style="display: table">
                            <thead>
                            <tr>
                                <th scope="col">Train ID</th>
                                <th scope="col">Start Station</th>
                                <th scope="col">End Station</th>
                                <th scope="col">Start Date</th>
                                <th scope="col">End Date</th>
                                <th scope="col">Price</th>
                                <th scope="col">Buy 1st Class</th>
                                <th scope="col">Buy 2nd Class</th>
                            </tr>
                            </thead>
                            <tbody>
							<?php
                            if (!isset($_SESSION['trains']))
                            {
                                unset($_POST['start_station']);
								unset($_POST['end_station']);
								unset($_POST['date']);
                            }
							$trains = isset($_SESSION['trains']) ? $_SESSION['trains'] : trains_controller::get_all_trains ();
                            unset($_SESSION['trains']);

							foreach ($trains as $key => $train) {

							    $user_id            = users_controller::is_logged_in ();
							    $train_id           = $train['train_id'];
								$first_class_seats  = false;
								$second_class_seats = false;

							    if ($user_id != null)
								{
									$first_class_seats = trains_controller::has_seats_available ($train_id, 1);
									$second_class_seats = trains_controller::has_seats_available ($train_id, 2);

									$first_class_seats = $first_class_seats && !trains_controller::user_has_seat ($train_id, $user_id);
									$second_class_seats = $second_class_seats && !trains_controller::user_has_seat ($train_id, $user_id);
								}

								$price = ($train['end_station'][1] - $train['start_station'][1]) * $train['km_cost'];
								?>


                                <tr>
                                    <th scope="row"><?php echo $train['train_id']; ?></th>
                                    <td><?php echo $train['start_station'][0]; ?></td>
                                    <td><?php echo $train['end_station'][0]; ?></td>
                                    <td><?php echo $train['start_station'][2]; ?></td>
                                    <td><?php echo $train['end_station'][2]; ?></td>
                                    <td><?php echo $price; ?></td>
                                    <td>
                                        <form method="post" action="/controllers/tickets_controller.php">

                                            <input type="hidden" name="train_id" value="<?php echo $train['train_id']; ?>">
                                            <input type="hidden" name="price" value="<?php echo $price; ?>">
                                            <input type="hidden" name="start_station" value="<?php echo $train['start_station'][0]; ?>">
                                            <input type="hidden" name="end_station" value="<?php echo $train['end_station'][0]; ?>">
                                            <input type="hidden" name="start_date" value="<?php echo $train['start_station'][2]; ?>">
                                            <input type="hidden" name="end_date" value="<?php echo $train['end_station'][2]; ?>">
                                            <input type="hidden" name="class" value="1">

                                            <input type="hidden" name="_action" value="BUY_TICKET">

                                            <input type="submit" class="btn btn-primary <?php
                                                if (!$first_class_seats) echo 'disabled';
                                            ?>" value="Buy" <?php
											if (!$first_class_seats) echo 'disabled';
											?>>
                                        </form>
                                    </td>

                                    <td>
                                        <form method="post" action="/controllers/tickets_controller.php">

                                            <input type="hidden" name="train_id" value="<?php echo $train['train_id']; ?>">
                                            <input type="hidden" name="price" value="<?php echo $price; ?>">
                                            <input type="hidden" name="start_station" value="<?php echo $train['start_station'][0]; ?>">
                                            <input type="hidden" name="end_station" value="<?php echo $train['end_station'][0]; ?>">
                                            <input type="hidden" name="start_date" value="<?php echo $train['start_station'][2]; ?>">
                                            <input type="hidden" name="end_date" value="<?php echo $train['end_station'][2]; ?>">
                                            <input type="hidden" name="class" value="2">

                                            <input type="hidden" name="_action" value="BUY_TICKET">

                                            <input type="submit" class="btn btn-primary <?php
											if (!$second_class_seats) echo 'disabled';
											?>" value="Buy" <?php
											if (!$second_class_seats) echo 'disabled';
											?>>
                                        </form>
                                    </td>
                                </tr>

							<?php } ?>

                            </tbody>
                        </table>
                    </div>

					<?php if(isset($_SESSION['ERROR_MSG'])){?>
                        <div class="card text-white bg-danger mb-3">
                            <div class="card-body">
                                <p class="card-text"><?php echo $_SESSION['ERROR_MSG']; ?></p>
                            </div>
                        </div>
                    <?php unset($_SESSION['ERROR_MSG']); } ?>

					<?php if(isset($_SESSION['NORMAL_MSG'])){?>
                        <div class="card text-white bg-info mb-3">
                            <div class="card-body">
                                <p class="card-text"><?php echo $_SESSION['NORMAL_MSG']; ?></p>
                            </div>
                        </div>
                    <?php unset($_SESSION['NORMAL_MSG']); } ?>

                </div>

            </div>
            <br>

        </div>

        <div class="col-sm-1"></div>
    </div>
</div>

<?php require_once ($_SERVER['DOCUMENT_ROOT'] . '/views/includes/footer.php'); ?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html>