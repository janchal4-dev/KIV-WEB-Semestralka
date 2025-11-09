<?php
//
//class HomeController {
//
//    public function render() {
//        require HEADER_FILE;
//        require VIEW_PATH . "/homeView.php";
//        require FOOTER_FILE;
//    }
//}


class HomeController
{

    public function render()
    {

        $app = new MyApplication();
        $app->renderTwig("welcome.twig", [
            "user" => "Janchal"
        ]);
    }
}
