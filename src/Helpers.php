<?php
/**
 * Created by PhpStorm.
 * User: rob
 * Date: 03/06/2017
 * Time: 18:00
 */

namespace LorinLee\LaradockCli\Console;


class Helpers
{

    /**
     * @return string
     */
    public static function getLaradockDirectoryName() {
        $full_path = getcwd();
        $full_path_parts = explode('/', $full_path);
        $directory_name = end($full_path_parts);
        return $directory_name . '_laradock';
    }

}
