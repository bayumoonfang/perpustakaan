<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/userguide3/general/hooks.html
|
*/

$hook['pre_system'][] = [
	'class'    => 'App_Autoloader',
	'function' => 'register',
	'filename' => 'App_Autoloader.php',
	'filepath' => 'hooks',
	'params'   => [],
];
$hook['post_controller_constructor'][] = [
	'class'    => 'App_Autoloader',
	'function' => 'set_input_flash_data',
	'filename' => 'App_Autoloader.php',
	'filepath' => 'hooks',
	'params'   => [],
];
