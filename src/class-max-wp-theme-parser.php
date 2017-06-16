<?php

/**
 * Class Max_WP_Theme
 *
 * @since 1.0.0
 */
class Max_WP_Theme_Parser extends Max_WP_Package_Parser {
	/**
	 * Headers for style.css files.
	 *
	 * @var array
	 */
	protected static $headersMap = array(
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

	/**
	 * Parse file style.css
	 *
	 * @param $fileContents
	 *
	 * @return null
	 */
	public static function parse_style( $fileContents ) {
		$headers = self::parseHeaders( $fileContents, self::$headersMap );

		$headers['Tags'] = array_filter( array_map( 'trim', explode( ',', strip_tags( $headers['Tags'] ) ) ) );

		//If it doesn't have a name, it's probably not a valid theme.
		if ( empty( $headers['Name'] ) ) {
			return null;
		} else {
			return $headers;
		}
	}
}