<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vadym
 * Date: 1/11/13
 * Time: 3:47 PM
 * To change this template use File | Settings | File Templates.
 */
if(!function_exists('__')){
    function __($string) {
        $x_ls = $_SESSION['x_ls'];
        return $x_ls->__($string);
    }
}
