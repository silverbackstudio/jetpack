<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 */

function twentysixteen_jetpack_setup() {
	/**
	 * Add theme support for Responsive Videos.
	 */
	add_theme_support( 'jetpack-responsive-videos' );
}
add_action( 'after_setup_theme', 'twentysixteen_jetpack_setup' );

function twentysixteen_init_jetpack() {
	/**
	 * Add our compat CSS file for custom widget stylings and such.
	 * Set the version equal to filemtime for development builds, and the JETPACK__VERSION for production
	 * or skip it entirely for wpcom.
	 */
	$version = false;
	if ( method_exists( 'Jetpack', 'is_development_version' ) ) {
		$version = Jetpack::is_development_version() ? filemtime( plugin_dir_path( __FILE__ ) . 'twentysixteen.css' ) : JETPACK__VERSION;
	}
	wp_enqueue_style( 'twentysixteen-jetpack', plugins_url( 'twentysixteen.css', __FILE__ ), array(), $version );
	wp_style_add_data( 'twentysixteen-jetpack', 'rtl', 'replace' );
}
add_action( 'init', 'twentysixteen_init_jetpack' );

/**
 * Alter gallery widget default width.
 */
function twentysixteen_gallery_widget_content_width( $width ) {
	return 390;
}
add_filter( 'gallery_widget_content_width', 'twentysixteen_gallery_widget_content_width' );

/**
 * Remove sharing and likes from custom excerpt.
 */
function twentysixteen_remove_share() {
	if ( has_excerpt() ) {
	    remove_filter( 'the_excerpt', 'sharing_display', 19 );
	    if ( class_exists( 'Jetpack_Likes' ) ) {
	        remove_filter( 'the_excerpt', array( Jetpack_Likes::init(), 'post_likes' ), 30, 1 );
	    }
	}
}
add_action( 'loop_start', 'twentysixteen_remove_share' );