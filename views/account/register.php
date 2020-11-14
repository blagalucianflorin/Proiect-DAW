<?php

session_start();

function value_of_input ($variable)
{
	if (isset ($_SESSION[$variable]))
	{
	    $saved = $_SESSION[$variable];
		unset ($_SESSION[$variable]);
		return $saved;
	}
	return "";
}

?>
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
                    <h4>Register <i class="fas fa-user-plus"></i></h4>
                </div>

                <div class="card-body text-center w-100">

                    <form class="w-100" name="register" method="post" action="/models/users.php">

                        <div class="form-group">
                            <label for="full_name_input">Full Name <i class="fas fa-signature"></i></label>
                            <input type="text" class="form-control" id="full_name_input" name="full_name" value="<?php echo value_of_input ('full_name');?>">
                        </div>

                        <div class="form-group">
                            <label for="email_input">Email address <i class="fas fa-at"></i></label>
                            <input type="email" class="form-control" id="email_input" aria-describedby="emailHelp" name="email" value="<?php echo value_of_input ('email');?>">
                        </div>

                        <div class="form-group">
                            <label for="email_confirm_input">Confirm Email address <i class="fas fa-at"></i><i class="fas fa-check"></i></label>
                            <input type="email" class="form-control" id="email_confirm_input" aria-describedby="emailHelp" name="email_confirm" value="<?php echo value_of_input ('email_confirm');?>">
                        </div>

                        <div class="form-group">
                            <label for="password_input">Password <i class="fas fa-key"></i></label>
                            <input type="password" class="form-control" id="password_input" name="password">
                        </div>

                        <div class="form-group">
                            <label for="password_input">Confirm Password <i class="fas fa-key"></i><i class="fas fa-check"></i></label>
                            <input type="password" class="form-control" id="password_confirm_input" name="password_confirm">
                        </div>

                        <?php if(isset($_SESSION['ERROR_MSG'])){?>
                        <div class="card text-white bg-danger mb-3">
                            <div class="card-body">
                                <p class="card-text"><?php echo $_SESSION['ERROR_MSG']; ?></p>
                            </div>
                        </div>
                        <?php unset($_SESSION['ERROR_MSG']); } ?>

                        <button type="submit" class="btn btn-primary" name="register">Register</button>

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