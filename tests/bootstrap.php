<?php

error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

$tests_dir = dirname( __FILE__ );
define( 'MAX_TESTS_DIR', $tests_dir );
require_once MAX_TESTS_DIR . '/../wp-package-parser.php';