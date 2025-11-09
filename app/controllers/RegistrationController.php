<?php

class RegistrationController {

    public function render() {
        require HEADER_FILE;
        require VIEW_PATH . "/registrationView.php";
        require FOOTER_FILE;
    }
}
