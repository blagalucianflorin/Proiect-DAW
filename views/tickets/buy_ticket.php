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
                    <h4>Buy Ticket Overview <i class="fas fa-list"></i></h4>
                </div>

                <div class="card-body w-100">

                    <h5>Entry point:</h5>
                    <p>Will get to this page after selecting a ticket from the <b>'Search Ticket'</b> page.</p>
                    <br>

                    <h5>Exit point:</h5>
                    <p>After buying a ticket, user will be redirected to the <b>'See Bought Tickets'</b> page.</p>
                    <br>

                    <h5>Behaviour:</h5>
                    <p>Will send a <b>POST</b> request to the <b>'ticket_controller'</b> with the form data.</p>
                    <p>If data send is invalid, the page will reload.</p>
                    <p>If data is valid, an email is sent to the user with a receipt.</p>
                    <br>

                    <h5>Data:</h5>
                    <ul>
                        <li><p>Ticked number <b>(hidden)</b></p></li>
                        <li><p>Wagon class (user input)</p></li>
                        <li><p>Number of tickets (user input)</p></li>
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