<?php
/**
 * Bootstrap the plugin unit testing environment.
 *
 * @package WordPress
 * @subpackage JSON API
*/

use Yoast\WPTestUtils\WPIntegration;

require_once dirname( __DIR__ ) . '/vendor/yoast/wp-test-utils/src/WPIntegration/bootstrap-functions.php';

$_tests_dir = WPIntegration\get_path_to_wp_test_dir();

// Get access to tests_add_filter() function.
require_once $_tests_dir . 'includes/functions.php';

/**
 * Callback to manually load the plugin
 */
function _manually_load_plugin() {
    require_once ( __DIR__ ) . '/s3-uploads.php';
}

// Add plugin to active mu-plugins to make sure it gets loaded.
tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

tests_add_filter( 's3_uploads_s3_client_params', function ( array $params ) : array {
	$params['endpoint'] = 'http://172.17.0.2:9000';
	return $params;
} );

if ( getenv( 'S3_UPLOADS_BUCKET' ) ) {
	define( 'S3_UPLOADS_BUCKET', getenv( 'S3_UPLOADS_BUCKET' ) );
}

if ( getenv( 'S3_UPLOADS_KEY' ) ) {
	define( 'S3_UPLOADS_KEY', getenv( 'S3_UPLOADS_KEY' ) );
}

if ( getenv( 'S3_UPLOADS_SECRET' ) ) {
	define( 'S3_UPLOADS_SECRET', getenv( 'S3_UPLOADS_SECRET' ) );
}

if ( getenv( 'S3_UPLOADS_REGION' ) ) {
	define( 'S3_UPLOADS_REGION', getenv( 'S3_UPLOADS_REGION' ) );
}

/*
 * Bootstrap WordPress. This will also load the Composer autoload file, the PHPUnit Polyfills
 * and the custom autoloader for the TestCase and the mock object classes.
 */
WPIntegration\bootstrap_it();
