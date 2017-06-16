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

		$this->assertEquals( null, $package->get_type() );
		$this->assertEquals( null, $package->get_slug() );
		$this->assertEquals( array(), $package->get_metadata() );
	}

	/**
	 * Test package wrong.
	 *
	 * @since 1.0.0
	 */
	public function testPackageWrong() {
		$package = new Max_WP_Package( MAX_TESTS_DIR . '/packages/hello-dolly.1.6.gzip' );

		$this->assertEquals( null, $package->get_type() );
		$this->assertEquals( null, $package->get_slug() );
		$this->assertEquals( array(), $package->get_metadata() );
	}

	/**
	 * Test get type package.
	 *
	 * @since 1.0.0
	 */
	public function testParse() {
		$package = new Max_WP_Package( MAX_TESTS_DIR . '/packages/hello-dolly.1.6.zip' );

		$this->assertEquals( 'plugin', $package->get_type() );
		$this->assertEquals( 'hello-dolly', $package->get_slug() );
	}

	/**
	 * Test get metadata.
	 *
	 * @since 1.0.0
	 */
	public function testGetMetadata() {
		$package = new Max_WP_Package( MAX_TESTS_DIR . '/packages/hello-dolly.1.6.zip' );

		$metadata = $package->get_metadata();
		$this->assertEquals( 'Hello Dolly', $metadata['name'] );
		$this->assertEquals( 'hello-dolly/hello.php', $metadata['plugin'] );
		$this->assertEquals( '4.6', $metadata['requires'] );
		$this->assertEquals( '4.7', $metadata['tested'] );
		$this->assertEquals( '1.6', $metadata['version'] );
		$this->assertEquals( 'hello-dolly', $metadata['slug'] );
	}
}