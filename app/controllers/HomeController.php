<?php

class HomeController {

    public function render()
    {
        $app = new MyApplication();
        $app->renderTwig("home.twig", [
            "currentPage" => "home"
        ]);
    }
}