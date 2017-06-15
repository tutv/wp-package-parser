<?php

error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

require_once '../wp-package-parser.php';

$tests_dir = dirname( __FILE__ );
define( 'MAX_TESTS_THEME_PACKAGES', $tests_dir . '/packages/twentyseventeen.1.3.zip' );
define( 'MAX_TESTS_PLUGIN_PACKAGES', $tests_dir . '/packages/hello-dolly.1.6.zip' );