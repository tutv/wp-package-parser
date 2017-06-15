<?php

/**
 * Class Max_WP_Package
 *
 * @since 1.0.0
 */
class Max_WP_Package {
	/**
	 * Headers file.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected static $file_headers = array();

	/**
	 * Metadata.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $metadata = array();

	/**
	 * Package file.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $package_file;

	/**
	 * Package type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $type = '';

	/**
	 * Max_WP_Package constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param $package_file
	 */
	public function __construct( $package_file ) {
		$this->type         = 'plugin';
		$this->package_file = $package_file;
	}

	/**
	 * Parse package.
	 *
	 * @since 1.0.0
	 *
	 *
	 * @return array|false
	 */
	public function parse() {
		if ( ! $this->validate_file() ) {
			return false;
		}

		$zip   = $this->open_package();
		$files = $zip->numFiles;

		for ( $i = 0; $i < $files; $i ++ ) {
			$info = $zip->statIndex( $i );

			$file_name = $this->explore_file( $info['name'] );
			if ( ! $file_name ) {
				continue;
			}

			switch ( $file_name ) {
				case 'readme.txt':
					break;

				case 'style.css':
					$this->type = 'theme';
					break;

				default;
			}
		}

		return true;
	}

	/**
	 * Get package type.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_type() {
		return $this->type;
	}

	/**
	 * Explore file.
	 *
	 * @since 1.0.0
	 *
	 * @param $file_name
	 *
	 * @return bool|string
	 */
	private function explore_file( $file_name ) {
		$data      = pathinfo( $file_name );
		$dirname   = $data['dirname'];
		$depth     = substr_count( $dirname, '/' );
		$extension = ! empty( $data['extension'] ) ? $data['extension'] : false;

		//Skip directories and everything that's more than 1 sub-directory deep.
		if ( $depth > 0 || ! $extension ) {
			return false;
		}

		return $data['filename'] . '.' . $data['extension'];
	}

	/**
	 * Validate package file.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function validate_file() {
		$file = $this->package_file;

		if ( ! file_exists( $file ) || ! is_readable( $file ) ) {
			return false;
		}

		if ( 'zip' !== pathinfo( $file, PATHINFO_EXTENSION ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Open package file.
	 *
	 * @since 1.0.0
	 *
	 * @return false|ZipArchive
	 */
	private function open_package() {
		$file = $this->package_file;

		$zip = new ZipArchive();
		if ( $zip->open( $file ) !== true ) {
			return false;
		}

		return $zip;
	}
}