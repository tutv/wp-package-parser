<?php


/**
 * Class Max_WP_Package_Parser.
 *
 * @since 1.0.0
 */
abstract class Max_WP_Package_Parser {
	/**
	 * Header map.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $headerMap = array();

	/**
	 * Parse the file contents to retrieve its metadata.
	 *
	 * Searches for metadata for a file, such as a plugin or theme.  Each piece of
	 * metadata must be on its own line. For a field spanning multiple lines, it
	 * must not have any newlines or only parts of it will be displayed.
	 *
	 * @param string $fileContents File contents. Can be safely truncated to 8kiB as that's all WP itself scans.
	 *
	 * @return array
	 */
	protected function parseHeaders( $fileContents ) {
		$headers   = array();
		$headerMap = $this->headerMap;

		//Support systems that use CR as a line ending.
		$fileContents = str_replace( "\r", "\n", $fileContents );

		foreach ( $headerMap as $field => $prettyName ) {
			$found = preg_match( '/^[ \t\/*#@]*' . preg_quote( $prettyName, '/' ) . ':(.*)$/mi', $fileContents, $matches );
			if ( ( $found > 0 ) && ! empty( $matches[1] ) ) {
				//Strip comment markers and closing PHP tags.
				$value             = trim( preg_replace( "/\s*(?:\*\/|\?>).*/", '', $matches[1] ) );
				$headers[ $field ] = $value;
			} else {
				$headers[ $field ] = '';
			}
		}

		return $headers;
	}


	/**
	 * Transform Markdown markup to HTML.
	 *
	 * Tries (in vain) to emulate the transformation that WordPress.org applies to readme.txt files.
	 *
	 * @since 1.0.0
	 *
	 * @param string $text
	 *
	 * @return string
	 */
	protected function applyMarkdown( $text ) {
		//The WP standard for readme files uses some custom markup, like "= H4 headers ="
		$text     = preg_replace( '@^\s*=\s*(.+?)\s*=\s*$@m', "<h4>$1</h4>\n", $text );
		$markdown = new Parsedown();

		return $markdown->parse( $text );
	}
}
