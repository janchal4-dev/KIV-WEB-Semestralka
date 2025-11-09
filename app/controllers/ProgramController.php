<?php

class ProgramController {

    public function render() {
        require HEADER_FILE;
        require VIEW_PATH . "/programView.php";
        require FOOTER_FILE;
    }
}
