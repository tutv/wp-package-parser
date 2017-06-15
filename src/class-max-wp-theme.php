<?php

/**
 * Class Max_WP_Theme
 *
 * @since 1.0.0
 */
class Max_WP_Theme extends Max_WP_Package {
	/**
	 * Headers for style.css files.
	 *
	 * @var array
	 */
	protected static $file_headers = array(
		'Name'        => 'Theme Name',
		'ThemeURI'    => 'Theme URI',
		'Description' => 'Description',
		'Author'      => 'Author',
		'AuthorURI'   => 'Author URI',
		'Version'     => 'Version',
		'Template'    => 'Template',
		'Status'      => 'Status',
		'Tags'        => 'Tags',
		'TextDomain'  => 'Text Domain',
		'DomainPath'  => 'Domain Path',
	);
}