<?php

class UserSettingsController {

    public function render() {
        require HEADER_FILE;
        require VIEW_PATH . "/userSettingsView.php";
        require FOOTER_FILE;
    }
}
