<?php

// if user is not logged in
if ( !isset($_SESSION['active']) || (isset($_SESSION['active']) && $_SESSION['active'] == 0) ){

    // Checking current path
    if (strstr($_SERVER['REQUEST_URI'],'/user') !== false ||
        strstr($_SERVER['REQUEST_URI'],'/admin') !== false ){

        // Avoid cyclic redirect
        if (
            !strstr($_SERVER['REQUEST_URI'],'/user/login') &&
            !strstr($_SERVER['REQUEST_URI'],'/user/register') &&
            !strstr($_SERVER['REQUEST_URI'],'/user/forgot_password')
        ) {
            header('Location: /user/login');
        }
    }
}