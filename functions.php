<?php
/**
 * Storefront engine room
 *
 * @package storefront
 */

/**
 * Assign the Storefront version to a var
 */
$theme              = wp_get_theme( 'storefront' );
$storefront_version = $theme['Version'];

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 980; /* pixels */
}

$storefront = (object) array(
	'version'    => $storefront_version,

	/**
	 * Initialize all the things.
	 */
	'main'       => require 'inc/class-storefront.php',
	'customizer' => require 'inc/customizer/class-storefront-customizer.php',
);

require 'inc/storefront-functions.php';
require 'inc/storefront-template-hooks.php';
require 'inc/storefront-template-functions.php';
require 'inc/wordpress-shims.php';

if ( class_exists( 'Jetpack' ) ) {
	$storefront->jetpack = require 'inc/jetpack/class-storefront-jetpack.php';
}

if ( storefront_is_woocommerce_activated() ) {
	$storefront->woocommerce            = require 'inc/woocommerce/class-storefront-woocommerce.php';
	$storefront->woocommerce_customizer = require 'inc/woocommerce/class-storefront-woocommerce-customizer.php';

	require 'inc/woocommerce/class-storefront-woocommerce-adjacent-products.php';

	require 'inc/woocommerce/storefront-woocommerce-template-hooks.php';
	require 'inc/woocommerce/storefront-woocommerce-template-functions.php';
	require 'inc/woocommerce/storefront-woocommerce-functions.php';
}

if ( is_admin() ) {
	$storefront->admin = require 'inc/admin/class-storefront-admin.php';

	require 'inc/admin/class-storefront-plugin-install.php';
}

/**
 * NUX
 * Only load if wp version is 4.7.3 or above because of this issue;
 * https://core.trac.wordpress.org/ticket/39610?cversion=1&cnum_hist=2
 */
if ( version_compare( get_bloginfo( 'version' ), '4.7.3', '>=' ) && ( is_admin() || is_customize_preview() ) ) {
	require 'inc/nux/class-storefront-nux-admin.php';
	require 'inc/nux/class-storefront-nux-guided-tour.php';

	if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '3.0.0', '>=' ) ) {
		require 'inc/nux/class-storefront-nux-starter-content.php';
	}
}

/**
 * Note: Do not add any custom code here. Please use a custom plugin so that your customizations aren't lost during updates.
 * https://github.com/woocommerce/theme-customisations
 */

/* Custom code */

add_filter( 'woocommerce_placeholder_img_src', 'custom_woocommerce_placeholder', 10 );
function custom_woocommerce_placeholder( $image_url ) {
  return '/wp-content/themes/tg-storefront/assets/images/tg-placeholder.svg'; //custom placeholder
}

function register_extra_menus() {
	register_nav_menu( 'footer_social_menu', 'Footer social menu');
	register_nav_menu( 'footer_nav_menu', 'Footer navigation menu');
}
add_action( 'after_setup_theme', 'register_extra_menus' );

function show_footer_menus() {
	wp_nav_menu( array(
		'theme_location' => 'footer_social_menu',
		'container' => 'nav',
		'container_class' => 'social',
	) );
	wp_nav_menu( array(
		'theme_location' => 'footer_nav_menu',
		'container' => 'nav',
		'container_class' => 'footer',
	) );
}
add_action( 'storefront_footer', 'show_footer_menus', 15 );

function register_extra_widget_space() {
	register_sidebar( array(
		'name' => __( 'Footer single centered', 'storefront' ),
		'id' => 'footer_center',
		'class' => 'footer_center',
	) );
}
add_action( 'after_setup_theme', 'register_extra_widget_space' );
function show_extra_widget_space() {
	echo('<div class="widgets_footer_center"><ul>');
	dynamic_sidebar('footer_center');
	echo('</ul></div>');
}
add_action( 'storefront_footer', 'show_extra_widget_space', 12 );

function enqueue_scripts() {
	wp_enqueue_script( 'focus-visible', get_template_directory_uri() . '/assets/js/focus-visible.min.js', '', '', true);
}
add_action ('wp_enqueue_scripts', 'enqueue_scripts');

function storefront_cart_link() {
	?>
        <a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'storefront' ); ?>">
<svg xmlns="http://www.w3.org/2000/svg" width="34.285" height="56.929" viewBox="0 0 9.071 15.062">
  <defs>
    <marker orient="auto" refY="0" refX="0" id="b" overflow="visible">
      <path d="M1.96 0a2 2 0 11-4.001-.001A2 2 0 011.96 0z" fill-rule="evenodd" stroke="#000" stroke-width=".5332"/>
    </marker>
    <marker orient="auto" refY="0" refX="0" id="a" overflow="visible">
      <path d="M1.96 0a2 2 0 11-4.001-.001A2 2 0 011.96 0z" fill-rule="evenodd" stroke="#000" stroke-width=".5332"/>
    </marker>
  </defs>
  <path fill="none" stroke="#000" stroke-width=".217" d="M.109 4.024h8.854v10.93H.109z"/>
  <path d="M168.574 134.045s-.26-5.15 2.456-5.15c2.717 0 2.457 5.15 2.457 5.15" fill="none" stroke="#000" stroke-width=".265" marker-start="url(#a)" marker-end="url(#b)" transform="translate(-166.495 -128.763)"/>
  <text style="line-height:1.25;-inkscape-font-specification:Oswald;font-variant-ligatures:normal;font-variant-caps:normal;font-variant-numeric:normal;font-feature-settings:normal;text-align:end" x="171.052" y="140.952" font-weight="400" font-size="25.4" font-family="Oswald" letter-spacing="0" word-spacing="0" text-anchor="end" stroke-width=".265" transform="translate(-166.495 -128.763)">
    <tspan x="171.052" y="140.952" style="-inkscape-font-specification:'sans-serif, Normal';font-variant-ligatures:normal;font-variant-caps:normal;font-variant-numeric:normal;font-feature-settings:normal;text-align:center" font-size="5.644" font-family="sans-serif" writing-mode="lr" text-anchor="middle"><?php echo wp_kses_data( WC()->cart->get_cart_contents_count() ); ?></tspan>
  </text>
</svg>	
	</a>
        <?php
}

/**
 *  * @snippet       Remove Additional Information Tab @ WooCommerce Single Product Page
 *   * @how-to        Get CustomizeWoo.com FREE
 *    * @author        Rodolfo Melogli
 *     * @testedwith    WooCommerce 3.8
 *      * @donate $9     https://businessbloomer.com/bloomer-armada/
 *       */
  
add_filter( 'woocommerce_product_tabs', 'bbloomer_remove_product_tabs', 9999 );
  
function bbloomer_remove_product_tabs( $tabs ) {
	    unset( $tabs['additional_information'] ); 
	        return $tabs;
}
