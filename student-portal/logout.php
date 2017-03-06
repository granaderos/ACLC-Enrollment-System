<?php
    session_start();
    if(isset($_SESSION["studentId"])) {
        session_destroy();
        session_unset();
        header("Location: login");
    }