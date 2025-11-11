<?php

class LogoutController {

    public function render()
    {
        session_destroy();
        header("Location: index.php?page=home");
        exit;
    }
}
