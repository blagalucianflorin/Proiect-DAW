<?php

if (session_id () == '') session_start();

require_once ($_SERVER['DOCUMENT_ROOT'] . '/controllers/controller.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/controllers/users_controller.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/views/includes/essentials.php');

if (users_controller::is_logged_in () != false)
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
                    <h4>Login <i class="fas fa-sign-in-alt"></i></h4>
                </div>

                <div class="card-body text-center w-100">

                    <form class="w-100" name="login" method="post" action="/controllers/users_controller.php">

                        <div class="form-group">
                            <label for="email_input">Email address</label>
                            <input type="email" class="form-control" id="email_input" aria-describedby="emailHelp" name="email" value="<?php echo value_of_input ('email');?>">
                        </div>

                        <div class="form-group">
                            <label for="password_input">Password</label>
                            <input type="password" class="form-control" id="password_input" name="password">
                        </div>

                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="remeber_check" name="remember_me" value="yes">
                            <label class="form-check-label" for="remeber_check">Remember me</label>
                        </div>

                        <h5>A captcha will be present</h5>

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

                        <input name="_action" type="hidden" value="LOGIN"/>

                        <button type="submit" class="btn btn-primary">Login</button>

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