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
                    <h4>Search Ticket Overview <i class="fas fa-list"></i></h4>
                </div>

                <div class="card-body w-100">

                    <h5>Scope:</h5>
                    <p>This page provides the user with a form to search for tickets.</p>
                    <p>A list of tickets is also presented after performing a search.</p>
                    <br>

                    <h5>Entry point:</h5>
                    <p>Will get to this page from the main page.</p>
                    <br>

                    <h5>Exit point:</h5>
                    <p>After finding a ticket and selecting it, user will be redirected to the <b>'Buy Ticket'</b> page.</p>
                    <br>

                    <h5>Search Data:</h5>
                    <ul>
                        <li><p>Start station.</p></li>
                        <li><p>Destination station.</p></li>
                        <li><p>Date.</p></li>
                    </ul>
                    <br>

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