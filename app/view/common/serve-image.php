<?php
    function get_image_path($image_path) {
        $dirname = __DIR__.$image_path;
        $image = glob($dirname);

        if (count($image) > 0) {

        }
    }
?>