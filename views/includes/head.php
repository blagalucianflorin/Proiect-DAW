<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/security/recaptcha.php');

?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="viewport" content= "width=device-width, user-scalable=no">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <title>FMI-Trans</title>

    <link rel="icon" type="image/png" href="/views/resources/favicon.png"/>

    <script src="https://kit.fontawesome.com/ad6afdf9a9.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="/views/styles/global.css">

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <script type="text/javascript">
        var onloadCallback = function() {
            $(".g-recaptcha").each(function() {
                var el = $(this);
                grecaptcha.render($(el).attr("id"), {
                    "sitekey" : <?php echo recaptcha::invisible_public_key (); ?>,
                    "callback" : function(token) {
                        $(el).parent().find(".g-recaptcha-response").val(token);
                        $(el).parent().submit();
                    }
                });
            });
        };
    </script>

    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>

</head>