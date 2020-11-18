<?php

if (session_id () == '') session_start();

require_once ($_SERVER['DOCUMENT_ROOT'] . '/controllers/controller.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/controllers/users_controller.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/controllers/routes_controller.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/controllers/tickets_controller.php');
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
        <div class="col-sm-1"></div>

        <div class="col-sm-10">

            <div class="card border-primary" style="width: auto;">

                <div class="card-header">
                    <h4>See Tickets <i class="fas fa-list"></i></h4>
                </div>

                <div class="card-body w-100">

					<?php
					$tickets = tickets_controller::get_tickets ();
					unset($_SESSION['tickets']);
					if (count ($tickets) != 0) { ?>
                        <div class="table-responsive">
                        <table class="table table-stripped table-bordered" style="display: table">
                        <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Train</th>
                            <th scope="col">Price</th>
                            <th scope="col">Wagon</th>
                            <th scope="col">Seat</th>
                            <th scope="col">Class</th>
                            <th scope="col">Purchase Date</th>
                            <th scope="col">Start Station</th>
                            <th scope="col">Start Date</th>
                            <th scope="col">End Station</th>
                            <th scope="col">End Date</th>
                        </tr>
                        </thead>
                        <tbody>
						<?php
						foreach ($tickets as $ticket) { ?>

                            <tr>
                                <th scope="row"><?php echo $ticket['ticket_id'] ?></th>
                                <td><?php echo $ticket['train_id'] ?></td>
                                <td><?php echo $ticket['price'] ?></td>
                                <td><?php echo $ticket['wagon'] ?></td>
                                <td><?php echo $ticket['seat'] ?></td>
                                <td><?php echo $ticket['class'] ?></td>
                                <td><?php echo $ticket['purchase_date'] ?></td>
                                <td><?php echo $ticket['start_station'] ?></td>
                                <td><?php echo $ticket['start_date'] ?></td>
                                <td><?php echo $ticket['end_station'] ?></td>
                                <td><?php echo $ticket['end_date'] ?></td>
                            </tr>


						<?php } ?>

                        </tbody>
                        </table>
                        </div>

					    <?php } else {?>

                        <div class="card text-white bg-danger mb-3">
                            <div class="card-body"><p>You haven't bought any tickets</p></div>
                        </div>

					<?php } ?>

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