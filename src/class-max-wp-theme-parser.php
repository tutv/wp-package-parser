<?php

/**
 * Class Max_WP_Theme
 *
 * @since 1.0.0
 */
class Max_WP_Theme_Parser extends Max_WP_Package_Parser {
	/**
	 * Header map.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $headerMap = array(
		'name'        => 'Theme Name',
		'theme_uri'   => 'Theme URI',
		'description' => 'Description',
		'author'      => 'Author',
		'author_uri'  => 'Author URI',
		'version'     => 'Version',
		'template'    => 'Template',
		'status'      => 'Status',
		'tags'        => 'Tags',
		'text_domain' => 'Text Domain',
		'domain_path' => 'Domain Path',
	);

	/**
	 * Parse file style.css
	 *
	 * @param $fileContents
	 *
	 * @return null
	 */
	public function parse_style( $fileContents ) {
		$headers = $this->parseHeaders( $fileContents );

		$headers['tags'] = array_filter( array_map( 'trim', explode( ',', strip_tags( $headers['tags'] ) ) ) );

		//If it doesn't have a name, it's probably not a valid theme.
		if ( empty( $headers['name'] ) ) {
			return null;
		} else {
			return $headers;
		}
	}
}