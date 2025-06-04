<?php
/**
 * Plugin Name:       AVA Landing Page Optimizer
 * Plugin URI:        https://magnoliaaiexperts.com/
 * Description:       Optimizes and integrates the AVA landing page assets and provides shortcodes for sections.
 * Version:           1.0.0
 * Author:            Magnolia AI Experts
 * Author URI:        https://magnoliaaiexperts.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       ava-lp
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Define constants
 */
define( 'AVA_LP_VERSION', '1.0.0' );
define( 'AVA_LP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'AVA_LP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Enqueue scripts and styles.
 * This function takes the CSS and JS from your static landing page
 * and loads them correctly into WordPress.
 */
function ava_lp_enqueue_assets() {
    // Enqueue Google Fonts (if you decide to use them globally, otherwise include in main CSS)
    // wp_enqueue_style( 'ava-lp-google-fonts', 'https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;500;700&display=swap', array(), null );

    // Enqueue Landing Page Styles
    // It's best to move all the <style> content from your HTML into a separate CSS file (e.g., assets/css/landing-page.css)
    wp_enqueue_style(
        'ava-lp-styles', // Handle
        AVA_LP_PLUGIN_URL . 'assets/css/landing-page.css', // Path to your CSS file within the plugin
        array(), // Dependencies
        AVA_LP_VERSION, // Version
        'all' // Media
    );

    // Enqueue GSAP
    wp_enqueue_script(
        'gsap-js',
        'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/gsap.min.js',
        array(),
        '3.11.4',
        true // Load in footer
    );

    // Enqueue Three.js (if used globally, otherwise only on pages where needed)
    wp_enqueue_script(
        'three-js',
        'https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js',
        array(),
        'r128',
        true // Load in footer
    );

    // Enqueue Particles.js
    wp_enqueue_script(
        'particles-js',
        'https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js',
        array(),
        '2.0.0',
        true // Load in footer
    );

    // Enqueue Typed.js
    wp_enqueue_script(
        'typed-js',
        'https://cdnjs.cloudflare.com/ajax/libs/typed.js/2.0.12/typed.min.js',
        array(),
        '2.0.12',
        true // Load in footer
    );

    // Enqueue Custom Landing Page Scripts
    // Move the <script> content from your HTML into a separate JS file (e.g., assets/js/landing-animations.js)
    wp_enqueue_script(
        'ava-lp-scripts', // Handle
        AVA_LP_PLUGIN_URL . 'assets/js/landing-animations.js', // Path to your JS file
        array('gsap-js', 'particles-js', 'typed-js'), // Dependencies
        AVA_LP_VERSION,
        true // Load in footer
    );

    // Pass PHP variables to JavaScript (e.g., for Typed.js strings if you want them editable in WP)
    // Example:
    // wp_localize_script( 'ava-lp-scripts', 'avaLpVars', array(
    //     'typedStrings' => array(
    //         esc_html__('Stop Losing Customers After Hours.', 'ava-lp'),
    //         esc_html__('Turn Website Visitors into Booked Jobs.', 'ava-lp'),
    //         esc_html__('The AI Employee That Never Sleeps.', 'ava-lp'),
    //         esc_html__('Your Local Automation Partner for $99.', 'ava-lp')
    //     ),
    //     'ajax_url' => admin_url( 'admin-ajax.php' ) // If you need AJAX
    // ));
}
add_action( 'wp_enqueue_scripts', 'ava_lp_enqueue_assets' );


/**
 * Shortcode for the Call to Action (CTA) section.
 * Usage: [ava_cta_section]
 *
 * This is a basic example. You would make title, text, button_text, button_url
 * attributes of the shortcode or pull them from theme options/custom fields.
 */
function ava_lp_cta_section_shortcode( $atts ) {
    // Default attributes
    $atts = shortcode_atts(
        array(
            'title' => 'Don\'t Lose Another Customer.',
            'text' => 'Ready to grow your business, save time, and improve customer satisfaction? Secure your $99/mo rate before all summer slots are gone.',
            'button_text' => 'Get Started for $99',
            'button_url' => 'https://magnoliaaiexperts.com/ava/client-profile-creation/',
        ),
        $atts,
        'ava_cta_section'
    );

    // Sanitize output
    $title = esc_html( $atts['title'] );
    $text = esc_html( $atts['text'] );
    $button_text = esc_html( $atts['button_text'] );
    $button_url = esc_url( $atts['button_url'] );

    ob_start(); // Start output buffering
    ?>
    <section class="cta" id="signup"> <!-- Ensure this ID is unique if used on multiple pages or handled by JS -->
        <div class="container">
            <h2 class="fade-in"><?php echo $title; ?></h2>
            <p class="fade-in"><?php echo $text; ?></p> 
            <a href="<?php echo $button_url; ?>" id="signup-btn-shortcode" class="cta-button pulse"><?php echo $button_text; ?></a>
            <!-- Note: IDs should be unique. If this shortcode is used multiple times on a page,
                 the 'signup-btn-shortcode' ID might cause issues if JS targets it specifically.
                 Consider using classes for JS targeting if the button needs unique JS interaction per instance.
            -->
        </div>
    </section>
    <?php
    return ob_get_clean(); // Return the buffered output
}
add_shortcode( 'ava_cta_section', 'ava_lp_cta_section_shortcode' );

/**
 * Shortcode for the Particles.js background.
 * Usage: [ava_particles_bg]
 * This is useful if you only want the particles on specific pages.
 */
function ava_lp_particles_bg_shortcode() {
    // Ensure particles.js is enqueued if this shortcode is used.
    // You might add a conditional enqueue here or ensure it's globally enqueued.
    if ( ! wp_script_is( 'particles-js', 'enqueued' ) ) {
        wp_enqueue_script('particles-js');
    }
     // Ensure the main plugin script that initializes particlesJS is also enqueued
    if ( ! wp_script_is( 'ava-lp-scripts', 'enqueued' ) ) {
        wp_enqueue_script('ava-lp-scripts');
    }

    ob_start();
    ?>
    <div id="particles-js" style="position: fixed; width: 100%; height: 100%; top: 0; left: 0; z-index: 0; pointer-events: none;"></div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'ava_particles_bg', 'ava_lp_particles_bg_shortcode' );


/**
 * Activation hook
 * (Runs once when the plugin is activated)
 */
function ava_lp_activate() {
    // You could set default options here if needed
    // flush_rewrite_rules(); // If you register custom post types or taxonomies
}
register_activation_hook( __FILE__, 'ava_lp_activate' );

/**
 * Deactivation hook
 * (Runs once when the plugin is deactivated)
 */
function ava_lp_deactivate() {
    // Clean up options or other data if needed
    // flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'ava_lp_deactivate' );

/**
 * TODO for further development:
 * 1. Create `assets/css/landing-page.css` and move all styles from the HTML <style> block into it.
 * 2. Create `assets/js/landing-animations.js` and move all JavaScript from the HTML <script> block into it.
 * - Update paths in `ava_lp_enqueue_assets()` accordingly.
 * - Ensure the JS in `landing-animations.js` correctly initializes particles on the `#particles-js` div if the shortcode is used.
 * 3. For content like hero text, features, testimonials:
 * - Consider creating Theme Customizer options (good for global landing page settings).
 * - Or, use Advanced Custom Fields (ACF) plugin to create custom fields on a specific page.
 * - Or, register Custom Post Types (e.g., 'testimonial', 'feature') if these are numerous and need to be managed like posts.
 * 4. Create more shortcodes for other sections (e.g., [ava_hero_section], [ava_features_grid], etc.).
 * - Make their content dynamic via shortcode attributes, theme options, or by querying custom fields/post types.
 * 5. If this plugin is intended to create a full landing page template:
 * - You might create a custom page template within a theme that pulls in these shortcodes or directly outputs the HTML.
 * - Or, the plugin could filter `the_content` on a specific page slug to inject the landing page.
 * 6. Ensure all text strings are translatable using WordPress localization functions (e.g., __(), _e(), esc_html__()).
 */
