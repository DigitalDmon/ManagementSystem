<?php
namespace includes;

use JetBrains\PhpStorm\NoReturn;

class Redirect {

    public function __construct() {}

    #[NoReturn] function redirectTo($url): void
    {
        header("Location: $url");
        exit;
    }
}