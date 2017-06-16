<?php

use PHPUnit\Framework\TestCase;

/**
 * Class Max_Tests_Theme_Package
 *
 * @since 1.0.0
 */
class Max_Tests_Theme_Package extends TestCase {
	/**
	 * Test parse package.
	 *
	 * @since 1.0.0
	 */
	public function testParsePackageSuccess() {
		$package = new Max_WP_Package( MAX_TESTS_DIR . '/packages/twentyseventeen.1.3.zip' );
		$result  = $package->parse();

		$this->assertEquals( 'theme', $package->get_type() );
		$this->assertEquals( true, $result );
		$this->assertEquals( 'twentyseventeen', $package->get_slug() );
	}

	/**
	 * Test parse package wrong.
	 *
	 * @since 1.0.0
	 */
	public function testParsePackageWrong() {
		$package = new Max_WP_Package( MAX_TESTS_DIR . '/packages/twentyseventeen.wrong.zip' );
		$result  = $package->parse();

		$this->assertEquals( null, $package->get_type() );
		$this->assertEquals( false, $result );
		$this->assertEquals( array(), $package->get_metadata() );
		$this->assertEquals( null, $package->get_slug() );

	}

	/**
	 * Test path wrong.
	 *
	 * @since 1.0.0
	 */
	public function testPathWrong() {
		$package = new Max_WP_Package( 'path/wrong/test.zip' );
		$result  = $package->parse();

		$this->assertEquals( null, $package->get_type() );
		$this->assertEquals( false, $result );
		$this->assertEquals( array(), $package->get_metadata() );
		$this->assertEquals( null, $package->get_slug() );
	}
}