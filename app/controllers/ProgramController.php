<?php

class ProgramController {

    public function render()
    {
        $app = new MyApplication();
        $app->renderTwig("program.twig", [
            "currentPage" => "program",
            "user" => $_SESSION["user"] ?? null
        ]);
    }
}
