<?php
/**
 * Enqueue scripts & styles.
 *
 * @package     Hester
 * @author      Peregrine Themes
 * @since       1.0.0
 */

/**
 * Enqueue and register scripts and styles.
 *
 * @since 1.0.0
 */
class Hester_Enqueue_Scripts {

	/**
	 * Check if debug is on
	 *
	 * @var boolean
	 */
	private $is_debug;

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->is_debug = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;
		add_action( 'wp_enqueue_scripts', array( $this, 'hester_enqueues' ) );
		add_action( 'wp_print_footer_scripts', array( $this, 'hester_skip_link_focus_fix' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'hester_block_editor_assets' ) );
	}

	/**
	 * Enqueue styles and scripts.
	 *
	 * @since 1.0.0
	 */
	public function hester_enqueues() {
		// Script debug.
		$hester_dir    = $this->is_debug ? 'dev/' : '';
		$hester_suffix = $this->is_debug ? '' : '.min';

		// fontawesome enqueue.
		wp_enqueue_style(
			'FontAwesome',
			HESTER_THEME_URI . '/assets/css/all' . $hester_suffix . '.css',
			false,
			'5.15.4',
			'all'
		);
		// Enqueue theme stylesheet.
		wp_enqueue_style(
			'hester-styles',
			HESTER_THEME_URI . '/assets/css/style' . $hester_suffix . '.css',
			false,
			HESTER_THEME_VERSION,
			'all'
		);

		// Enqueue IE specific styles.
		wp_enqueue_style(
			'hester-ie',
			HESTER_THEME_URI . '/assets/css/compatibility/ie' . $hester_suffix . '.css',
			false,
			HESTER_THEME_VERSION,
			'all'
		);

		wp_style_add_data( 'hester-ie', 'conditional', 'IE' );

		// Enqueue HTML5 shiv.
		wp_register_script(
			'html5shiv',
			HESTER_THEME_URI . '/assets/js/' . $hester_dir . 'vendors/html5' . $hester_suffix . '.js',
			array(),
			'3.7.3',
			true
		);

		// Load only on < IE9.
		wp_script_add_data(
			'html5shiv',
			'conditional',
			'lt IE 9'
		);

		// Flexibility.js for crossbrowser flex support.
		wp_enqueue_script(
			'hester-flexibility',
			HESTER_THEME_URI . '/assets/js/' . $hester_dir . 'vendors/flexibility' . $hester_suffix . '.js',
			array(),
			HESTER_THEME_VERSION,
			false
		);

		wp_add_inline_script(
			'hester-flexibility',
			'flexibility(document.documentElement);'
		);

		wp_script_add_data(
			'hester-flexibility',
			'conditional',
			'IE'
		);

		// Register Hester slider.
		wp_register_script(
			'hester-slider',
			HESTER_THEME_URI . '/assets/js/' . $hester_dir . 'hester-slider' . $hester_suffix . '.js',
			array( 'imagesloaded' ),
			HESTER_THEME_VERSION,
			true
		);

		// Load comment reply script if comments are open.
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// Enqueue main theme script.
		wp_enqueue_script(
			'hester',
			HESTER_THEME_URI . '/assets/js/' . $hester_dir . 'hester' . $hester_suffix . '.js',
			array( 'jquery', 'imagesloaded' ),
			HESTER_THEME_VERSION,
			true
		);

		// Comment count used in localized strings.
		$comment_count = get_comments_number();

		// Localized variables so they can be used for translatable strings.
		$localized = array(
			'ajaxurl'               => esc_url( admin_url( 'admin-ajax.php' ) ),
			'nonce'                 => wp_create_nonce( 'hester-nonce' ),
			'responsive-breakpoint' => intval( hester_option( 'main_nav_mobile_breakpoint' ) ),
			'sticky-header'         => array(
				'enabled' => hester_option( 'sticky_header' ),
				'hide_on' => hester_option( 'sticky_header_hide_on' ),
			),
			'strings'               => array(
				/* translators: %s Comment count */
				'comments_toggle_show' => $comment_count > 0 ? esc_html( sprintf( _n( 'Show %s Comment', 'Show %s Comments', $comment_count, 'hester' ), $comment_count ) ) : esc_html__( 'Leave a Comment', 'hester' ),
				'comments_toggle_hide' => esc_html__( 'Hide Comments', 'hester' ),
			),
		);

		wp_localize_script(
			'hester',
			'hester_vars',
			apply_filters( 'hester_localized', $localized )
		);

		// Enqueue google fonts.
		hester()->fonts->enqueue_google_fonts();

		// Add additional theme styles.
		do_action( 'hester_enqueue_scripts' );
	}

	/**
	 * Skip link focus fix for IE11.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function hester_skip_link_focus_fix() {
		?>
		<script>
			! function() {
				var e = -1 < navigator.userAgent.toLowerCase().indexOf("webkit"),
					t = -1 < navigator.userAgent.toLowerCase().indexOf("opera"),
					n = -1 < navigator.userAgent.toLowerCase().indexOf("msie");
				(e || t || n) && document.getElementById && window.addEventListener && window.addEventListener("hashchange", function() {
					var e, t = location.hash.substring(1);
					/^[A-z0-9_-]+$/.test(t) && (e = document.getElementById(t)) && (/^(?:a|select|input|button|textarea)$/i.test(e.tagName) || (e.tabIndex = -1), e.focus())
				}, !1)
			}();
		</script>
		<?php
	}

	/**
	 * Enqueue assets for the Block Editor.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function hester_block_editor_assets() {

		// RTL version.
		$rtl = is_rtl() ? '-rtl' : '';

		// Minified version.
		$min = $this->is_debug ? '' : '.min';
		// Enqueue block editor styles.
		wp_enqueue_style(
			'hester-block-editor-styles',
			HESTER_THEME_URI . '/inc/admin/assets/css/hester-block-editor-styles' . $rtl . $min . '.css',
			false,
			HESTER_THEME_VERSION,
			'all'
		);

		// Enqueue google fonts.
		hester()->fonts->enqueue_google_fonts();

		// Add dynamic CSS as inline style.
		wp_add_inline_style(
			'hester-block-editor-styles',
			apply_filters( 'hester_block_editor_dynamic_css', hester_dynamic_styles()->get_block_editor_css() )
		);
	}
}
