<?php
    function get_image_path($image_path) {
        $dirname = __DIR__.$image_path;
        $images = glob($dirname);

        if (count($images) > 0) {
            return $images[0];
        }
    }
?>