<?php

namespace app\controllers;

class TourController {
    public function tour1($req, $res) {
        sleep(rand(1, 2));
        return [];
    }

}
