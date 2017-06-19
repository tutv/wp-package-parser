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
	 * Max_WP_Package constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param $package_file
	 */
	public function __construct( $package_file ) {
		$this->package_file = $package_file;
		$this->parse();
	}

	/**
	 * Get slug.
	 *
	 * @since 1.0.0
	 *
	 * @return string|null
	 */
	public function get_slug() {
		$metadata = $this->get_metadata();

		if ( ! isset( $metadata['slug'] ) ) {
			return null;
		}

		return $metadata['slug'];
	}

	/**
	 * Get metadata.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_metadata() {
		$metadata = $this->metadata;

		return $metadata;
	}

	/**
	 * Parse package.
	 *
	 * @since 1.0.0
	 *
	 *
	 * @return bool
	 */
	private function parse() {
		if ( ! $this->validate_file() ) {
			return false;
		}

		$plugin_parser = new Max_WP_Plugin_Parser();
		$theme_parser  = new  Max_WP_Theme_Parser();

		$slug  = null;
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
				$headers = $plugin_parser->parse_plugin_file( $content );

				if ( $headers ) {
					//Add plugin file
					$plugin_file       = $slug . '/' . $file_name;
					$headers['plugin'] = $plugin_file;

					$this->type     = 'plugin';
					$this->metadata = array_merge( $this->metadata, $headers );
				}

				continue;
			}

			if ( $file_name === 'readme.txt' ) {
				$data = $plugin_parser->parser_readme( $content );
				unset( $data['name'] );
				$data['readme'] = true;
				$this->metadata = array_merge( $data, $this->metadata );

				continue;
			}

			if ( $file_name === 'style.css' ) {
				$headers = $theme_parser->parse_style( $content );
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

		$this->metadata['slug'] = $slug;

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
