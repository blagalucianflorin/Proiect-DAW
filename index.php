<?php

if (session_id () == '') session_start();

require_once ($_SERVER['DOCUMENT_ROOT'] . '/controllers/permissions_controller.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/controllers/controller.php');

controller::redirect ('/views/tickets/search_trains.php');

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
                    <h4>Overview <i class="fas fa-list"></i></h4>
                </div>

                <div class="card-body w-100">

                    <h5>Scope</h5>
                    <p>The purpose of this page is to provide users with the ability to buy train tickets</p>
                    <br>

                    <h5>Main page</h5>
                    <p>This is the main page of the site and will contain the following:</p>
                    <ul>
                        <li><p>A button to access the <b>'search tickets'</b> page</p></li>
                        <li><p>(mock) News</p></li>
                        <li><p>A login form</p></li>
                        <li><p>Contact info</p></li>
                    </ul>
                    <br>

                    <h5>DEBUG</h5>
                    <p>To view pages other than the login and register ones, use the links in 'DEBUG' dropdown present in the navbar.</p>


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

        <div class="col-sm-3"></div>
    </div>
</div>

<?php require_once ($_SERVER['DOCUMENT_ROOT'] . '/views/includes/footer.php'); ?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html>