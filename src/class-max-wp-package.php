<?php

include_once 'libs/Parsedown.php';
include_once 'class-max-wp-package-parser.php';
include_once 'class-max-wp-plugin-parser.php';
include_once 'class-max-wp-theme-parser.php';

/**
 * Class Max_WP_Package
 *
 * @since 1.0.0
 */
class Max_WP_Package {
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
	private $type = null;

	/**
	 * Get slug.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $slug = null;

	/**
	 * Max_WP_Package constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param $package_file
	 */
	public function __construct( $package_file ) {
		$this->package_file = $package_file;
	}

	/**
	 * Get slug.
	 *
	 * @since 1.0.0
	 *
	 * @return string|null
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * Get metadata.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_metadata() {
		return $this->metadata;
	}

	/**
	 * Parse package.
	 *
	 * @since 1.0.0
	 *
	 *
	 * @return bool
	 */
	public function parse() {
		if ( ! $this->validate_file() ) {
			return false;
		}

		$slug = null;

		$zip   = $this->open_package();
		$files = $zip->numFiles;

		for ( $index = 0; $index < $files; $index ++ ) {
			$info = $zip->statIndex( $index );

			$file = $this->explore_file( $info['name'] );
			if ( ! $file ) {
				continue;
			}

			$slug      = $file['dirname'];
			$file_name = $file['name'] . '.' . $file['extension'];
			$content   = $zip->getFromIndex( $index );

			if ( $file['extension'] === 'php' ) {
				$headers = Max_WP_Plugin_Parser::parsePluginFile( $content );

				if ( $headers ) {
					$this->type     = 'plugin';
					$this->metadata = $headers;
				}

				continue;
			}

			if ( $file_name === 'readme.txt' ) {
				$data           = Max_WP_Plugin_Parser::parser_readme( $content );
				$this->metadata = array_merge( $this->metadata, $data );

				continue;
			}

			if ( $file_name === 'style.css' ) {
				$headers = Max_WP_Theme_Parser::parse_style( $content );
				if ( $headers ) {
					$this->type     = 'theme';
					$this->metadata = $headers;
				}
			}
		}

		if ( empty( $this->type ) ) {
			$this->metadata = array();

			return false;
		}

		//Parse ok
		$this->slug = $slug;

		return true;
	}

	/**
	 * Get package type.
	 *
	 * @since 1.0.0
	 *
	 * @return string|null
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
	 * @return bool|array
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

		return array(
			'dirname'   => $dirname,
			'name'      => $data['filename'],
			'extension' => $data['extension']
		);
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
