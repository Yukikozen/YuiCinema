<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//echo phpinfo();

if (extension_loaded('imagick')) {
    echo 'Imagick extension is enabled.';
} else {
    echo 'Imagick extension is not enabled.';
}
?>