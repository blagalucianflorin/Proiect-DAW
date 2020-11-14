<!doctype html>
<html lang="en" style="height: 90%">


<?php
$path = $_SERVER['DOCUMENT_ROOT'];
$path .= "/views/includes/head.php";
include_once($path);
?>

<body class="h-100" style="background: #E8E8E8">

<?php
$path = $_SERVER['DOCUMENT_ROOT'];
$path .= "/views/includes/header.php";
include_once($path);
?>

<div class="container-fluid h-100">
    <div class="row h-100 justify-content-center align-items-center">
        <div class="col-sm-3"></div>

        <div class="col-sm-6">

            <div class="card border-primary" style="width: auto;">

                <div class="card-header">
                    <h4>Login <i class="fas fa-sign-in-alt"></i></h4>
                </div>

                <div class="card-body text-center w-100">

                    <form class="w-100" name="login">

                        <div class="form-group">
                            <label for="email_input">Email address</label>
                            <input type="email" class="form-control" id="email_input" aria-describedby="emailHelp">
                            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                        </div>

                        <div class="form-group">
                            <label for="password_input">Password</label>
                            <input type="password" class="form-control" id="password_input">
                        </div>

                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="remeber_check">
                            <label class="form-check-label" for="remeber_check">Remember me</label>
                        </div>

                        <h5>A captcha will be present</h5>

                        <button type="submit" class="btn btn-primary">Login</button>

                    </form>

                </div>

            </div>
            <br>

        </div>

        <div class="col-sm-3"></div>
    </div>
</div>

<?php
$path = $_SERVER['DOCUMENT_ROOT'];
$path .= "/views/includes/footer.php";
include_once($path);
?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html>