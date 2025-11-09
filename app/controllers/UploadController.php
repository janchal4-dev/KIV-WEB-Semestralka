<?php

class UploadController {

    public function render() {
        require HEADER_FILE;
        require VIEW_PATH . "/uploadView.php";
        require FOOTER_FILE;
    }
}
