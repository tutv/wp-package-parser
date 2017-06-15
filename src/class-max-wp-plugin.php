<?php

/**
 * Class Max_WP_Plugin
 *
 * @since 1.0.0
 */
class Max_WP_Plugin extends Max_WP_Package {

	/**
	 * Headers file.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected static $file_headers = array(
		'Name'        => 'Plugin Name',
		'PluginURI'   => 'Plugin URI',
		'Version'     => 'Version',
		'Description' => 'Description',
		'Author'      => 'Author',
		'AuthorURI'   => 'Author URI',
		'TextDomain'  => 'Text Domain',
		'DomainPath'  => 'Domain Path',
		'Network'     => 'Network',
	);
}