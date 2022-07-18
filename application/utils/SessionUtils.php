<?php
    session_start();

    function getLoginUser() {
        return isset($_SESSION[_LOGINUSER]) ? $_SESSION[_LOGINUSER] : "";
    }

    function getIuser() {
        return isset(getLoginUser()->iuser) ? getLoginUser()->iuser : "";
    }

    function getMainimgSrc() {
        return getIuser() . "/" . getLoginUser()->mainimg;
    }