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