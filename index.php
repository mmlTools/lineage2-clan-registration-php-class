<?php
##################################################
# Copyright ©Darksoke's Coding Services
# Discord: https://discord.gg/YCUpNz77j
#
# Redistribution of this code is not permitted
# Do not touch the code below unless you know
# what you are doing
##################################################

include "ClanRegistrationModule/index.php";
$request = new \ClanRegistrationModule\index();

?>
<!DOCTYPE html>
<!--[if IE 7 ]><html class="ie ie7 lte9 lte8 lte7" lang="ro"><![endif]-->
<!--[if IE 8]><html class="ie ie8 lte9 lte8" lang="ro">	<![endif]-->
<!--[if IE 9]><html class="ie ie9 lte9" lang="ro"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html class="noIE" lang="ro">
<head>
    <title>L2 Clan Registration Script</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="site_style.css">
    <?= $request::getStyle() ?>

</head>
<body>
<div class="main">
    <div class="container-fluid">
        <div class="row main-content">
            <div class="col-8">
                <div class="row">
                    <div class="col-6">
                        <?= $request::getList(['subTitle' => ["My awesome subtitle", true]]) ?>
                    </div>
                    <div class="col-6">
                        <?= $request::getForm() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<footer class="footer">
    <div class="container text-center">
        <p>Copyright ©<a href="https://discord.gg/YCUpNz77jW">Darksoke's Coding Services</a> 2019</p>
    </div>
</footer>
<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Popper JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>
</html>