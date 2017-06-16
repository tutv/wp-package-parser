<?php

use PHPUnit\Framework\TestCase;

/**
 * Class Max_Tests_Theme_Package
 *
 * @since 1.0.0
 */
class Max_Tests_Theme_Package extends TestCase {
	/**
	 * Package file.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $package_file;

	/**
	 * Max_Tests_Theme_Package constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param null $name
	 * @param array $data
	 * @param string $dataName
	 */
	public function __construct( $name = null, array $data = [], $dataName = '' ) {
		$this->package_file = MAX_TESTS_DIR . '/packages/twentyseventeen.1.3.zip';

		parent::__construct( $name, $data, $dataName );
	}

	/**
	 * Test get type package.
	 *
	 * @since 1.0.0
	 */
	public function testGetTypePackage() {
		$package = new Max_WP_Package( $this->package_file );
		$result  = $package->parse();

		$this->assertEquals( 'theme', $package->get_type() );
		$this->assertEquals( true, $result );
	}
}