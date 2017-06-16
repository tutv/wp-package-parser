<?php

use PHPUnit\Framework\TestCase;

/**
 * Class Max_Tests_Plugin_Package
 *
 * @since 1.0.0
 */
class Max_Tests_Plugin_Package extends TestCase {
	/**
	 * Test package not found.
	 *
	 * @since 1.0.0
	 */
	public function testPackageNotFound() {
		$package = new Max_WP_Package( '/path/wrong/abc.zip' );
		$result  = $package->parse();

		$this->assertEquals( false, $result );
		$this->assertEquals( null, $package->get_type() );
	}

	/**
	 * Test package wrong.
	 *
	 * @since 1.0.0
	 */
	public function testPackageWrong() {
		$package = new Max_WP_Package( MAX_TESTS_DIR . '/packages/hello-dolly.1.6.gzip' );
		$result  = $package->parse();

		$this->assertEquals( false, $result );
		$this->assertEquals( null, $package->get_type() );
	}

	/**
	 * Test get type package.
	 *
	 * @since 1.0.0
	 */
	public function testParse() {
		$package = new Max_WP_Package( MAX_TESTS_DIR . '/packages/hello-dolly.1.6.zip' );
		$result  = $package->parse();

		$this->assertEquals( true, $result );
		$this->assertEquals( 'plugin', $package->get_type() );
	}
}