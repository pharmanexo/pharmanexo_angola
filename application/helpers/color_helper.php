<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('get_colors')) {
    function get_colors()
    {
        $colors = [];

        for ($i = 0; $i < 27; $i++) {
            $colors[] = substr(md5(rand()), 0, 6);
        }

        return $colors;
    }
}

/* End of file: color_helper.php */
