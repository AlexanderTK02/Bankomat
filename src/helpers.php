<?php

function require_role($role)
{
    if (
        !isset($_SESSION["role"]) ||
        $_SESSION["role"] !== $role
    ) {
        header("Location: ?page=login");
        exit;
    }
}

function check_csrf()
{
    if (
        !isset($_POST["csrf_token"]) ||
        !isset($_SESSION["csrf_token"]) ||
        $_POST["csrf_token"] !== $_SESSION["csrf_token"]
    ) {
        die("CSRF-token ogiltig");
    }
}
