<?php

    session_start();
    if(isset($_SESSION["type"])) {
        session_destroy();
        session_unset();
        header("Location: login");
    } else if(!isset($_SESSION["type"])){
        header("Location: login");
    } else {
        if(strtoupper($_SESSION["type"]) == "ADMIN")
            header("Location: admin");
        else if(strtoupper($_SESSION["type"]) == "REGISTRAR")
            header("Location: registrar");
        else if(strtoupper($_SESSION["type"]) == "INSTRUCTOR")
            header("Location: instructor");
        else if(strtoupper($_SESSION["type"]) == "DEAN")
            header("Location: dean");
        else if(strtoupper($_SESSION["type"]) == "CASHIER")
            header("Location: cashier");
    }