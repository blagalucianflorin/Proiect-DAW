<?php

if (session_id () == '') session_start();

require_once ($_SERVER['DOCUMENT_ROOT'] . '/controllers/users_controller.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/controllers/permissions_controller.php');

?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">

    <a class="navbar-brand" href="/">FMI-Trans<i class="fas fa-train"></i></a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">

        <ul class="navbar-nav mr-auto">

        </ul>

        <ul class="navbar-nav navbar-right">

			<?php if (permissions_controller::is_admin ()) { ?>
                <span class="navbar-text active"><i class="fas fa-user-tie"></i>You're an admin!</span>
			<?php } ?>

			<?php if (users_controller::is_logged_in ()) { ?>

            <li class="nav-item active dropdown">

                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-user"></i>Account
                </a>

                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="/views/tickets/see_tickets.php">See bought tickets</a>
                        <a class="dropdown-item" href="/views/account/account_activity.php">Account activity</a>
					<?php if (permissions_controller::is_admin ()) { ?>
                        <a class="dropdown-item" href="/views/admin/trains/select_route.php">Admin: Add Trains</a>
                        <a class="dropdown-item" href="/views/admin/routes/route_builder.php">Admin: Route Builder</a>
                    <?php } ?>
                </div>

            </li>

			<?php } ?>


            <?php
                $full_name = users_controller::get_full_name ();

                if ($full_name == null) { ?>

                    <li class="nav-item active"><a class="nav-link" href="/views/account/register.php"><i class="fas fa-user-plus"></i>Sign Up</a></li>
                    <li class="nav-item active"><a class="nav-link" href="/views/account/login.php"><i class="fas fa-sign-in-alt"></i>Login</a></li>

                <?php } else { ?>

                    <span class="navbar-text active">Hello, <?php echo $full_name ?></span>
                    <form id="logout_form" class="form-inline" method="post" action="/controllers/users_controller.php">
                        <div class="nav-item active"><a id="logout_button" class="nav-link" href="#" onclick="document.getElementById('logout_form').submit()">
                        <i class="fas fa-sign-out-alt"></i>Logout</a></div>
                        <input name="_action" type="hidden" value="LOGOUT"/>
                    </form>

            <?php } ?>
        </ul>

    </div>
</nav>
<br>