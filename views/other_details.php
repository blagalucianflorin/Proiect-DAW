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
                    <h4>Other details<i class="fas fa-list"></i></h4>
                </div>

                <div class="card-body w-100">

                    <h5>Users</h5>
                    <p>The users will be either: Guest (default), Normal, Admin.</p>
                    <br>

                    <h5>Entities</h5>
                    <p>The following entities will be present:</p>
                    <ul>
                        <li><p>Train routes</p></li>
                        <li><p>Users</p></li>
                        <li><p>Tickets</p></li>
                    </ul>
                    <br>

                    <h5>Database</h5>
                    <p>The database will store the following data:</p>
                    <ul>
                        <li><p>Users (id, email, password, salt, name)</p></li>
                        <li><p>Routes (id, start point, end point, intermediate stations, price/km, date:hours)</p></li>
                        <li><p>Tickets (id, user_id, route_id, start point, end point, price, date:hours)</p></li>
                    </ul>


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