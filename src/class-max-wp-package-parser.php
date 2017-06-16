<?php


/**
 * Class Max_WP_Package_Parser.
 *
 * @since 1.0.0
 */
class Max_WP_Package_Parser {
	/**
	 * Parse the file contents to retrieve its metadata.
	 *
	 * Searches for metadata for a file, such as a plugin or theme.  Each piece of
	 * metadata must be on its own line. For a field spanning multiple lines, it
	 * must not have any newlines or only parts of it will be displayed.
	 *
	 * @param string $fileContents File contents. Can be safely truncated to 8kiB as that's all WP itself scans.
	 * @param array $headerMap The list of headers to search for in the file.
	 *
	 * @return array
	 */
	protected static function parseHeaders( $fileContents, $headerMap ) {
		$headers = array();

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
}
