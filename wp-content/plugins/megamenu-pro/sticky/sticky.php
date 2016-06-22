<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // disable direct access
}

if ( ! class_exists('Mega_Menu_Sticky') ) :

/**
 *
 */
class Mega_Menu_Sticky {


	/**
	 * Constructor
	 *
	 * @since 1.0
	 */
	public function __construct() {

        add_action( 'megamenu_page_sticky', array( $this, 'sticky_page' ) );
        add_filter( 'megamenu_menu_tabs', array( $this, 'add_sticky_tab' ) );
        add_filter( 'megamenu_wrap_attributes', array( $this, 'add_sticky_attribute' ), 10, 5 );
		add_filter( 'megamenu_scss_variables', array( $this, 'add_sticky_scss_vars'), 10, 4 );
		add_filter( 'megamenu_load_scss_file_contents', array( $this, 'append_sticky_scss'), 10 );

	}


	/**
	 *
	 */
	public function add_sticky_scss_vars( $vars, $location, $theme, $menu_id ) {

		$saved_settings = get_option('megamenu_settings');

		$opacity = isset( $saved_settings['sticky']['opacity'] ) ? $saved_settings['sticky']['opacity'] : 0.9;

		$vars['sticky_menu_opacity'] = $opacity;

		return $vars;

	}


	/**
	 * Add the sticky CSS to the main SCSS file
	 *
	 * @since 1.0
	 * @param string $scss
	 * @return string
	 */
	public function append_sticky_scss( $scss ) {

		$path = trailingslashit( plugin_dir_path( __FILE__ ) ) . 'scss/sticky.scss';

		$contents = file_get_contents( $path );

 		return $scss . $contents;

	}


	/**
	 *
	 */
	public function add_sticky_attribute( $attributes, $menu_id, $menu_settings, $settings, $current_theme_location ) {

		if ( isset( $settings['sticky']['location']) && $settings['sticky']['location'] == $current_theme_location ) {

			$attributes['data-sticky'] = 'true';

            if ( isset( $settings['sticky']['mobile']) ) {
                $attributes['data-sticky-mobile'] = $settings['sticky']['mobile'];
            } else {
                $attributes['data-sticky-mobile'] = 'false';
            }

            if ( isset( $settings['sticky']['offset']) ) {
                $attributes['data-sticky-offset'] = $settings['sticky']['offset'];
            } else {
                $attributes['data-sticky-offset'] = 0;
            }

		}

		return $attributes;
	}


	/**
	 *
	 */
	public function add_sticky_tab($tabs) {

		$tabs['sticky'] = __("Sticky Menu", "megamenupro");

		return $tabs;

	}


	/**
	 *
	 */
	public function sticky_page( $saved_settings ) {

		$sticky_location = isset( $saved_settings['sticky']['location'] ) ? $saved_settings['sticky']['location'] : 'disabled';
		$sticky_opacity = isset( $saved_settings['sticky']['opacity'] ) ? $saved_settings['sticky']['opacity'] : 0.9;
        $sticky_mobile = isset( $saved_settings['sticky']['mobile'] ) ? $saved_settings['sticky']['mobile'] : 'false';
        $sticky_offset = isset( $saved_settings['sticky']['offset'] ) ? $saved_settings['sticky']['offset'] : 0;


		?>

        <div class='menu_settings'>

            <form action="<?php echo admin_url('admin-post.php'); ?>" method="post">
                <input type="hidden" name="action" value="megamenu_save_settings" />
                <input type="hidden" name="tab" value="sticky" />
                <?php wp_nonce_field( 'megamenu_save_settings' ); ?>

                <h4 class='first'><?php _e("Sticky Menu", "megamenupro"); ?></h4>

                <table>
                    <tr>
                        <td class='mega-name'>
                            <?php _e("Theme Location", "megamenupro"); ?>
                            <div class='mega-description'>
                            	<?php _e("Select the theme location to make sticky", "megamenupro"); ?>
                            </div>
                        </td>
                        <td class='mega-value'>
                            <?php

                            	$theme_locations = get_registered_nav_menus();

                            	if ( ! count( $theme_locations ) ) {
                            		_e("No menu theme locations found", "megamenupro");
                            	} else {
                            		echo "<select name='settings[sticky][location]'>";
                            		echo "<option value='disabled'>" . __("Disabled", "megamenupro") . "</option>";

                            		foreach ( $theme_locations as $key => $value ) {
                            			echo "<option value='{$key}' " . selected( $key, $sticky_location, false ) . ">" . $value . "</option>";
                            		}

                            		echo "</select>";
                            	}
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class='mega-name'>
                            <?php _e("Sticky Menu Opacity", "megamenupro"); ?>
                            <div class='mega-description'>
                            	<?php _e("Set the transparency of the menu when sticky (values 0.2 - 1.0). Default: 0.9.", "megamenupro"); ?>
                            </div>
                        </td>
                        <td class='mega-value'>
                            <input type='number' step='0.1' min='0.2' max='1' name='settings[sticky][opacity]' value='<?php echo $sticky_opacity; ?>' />
                        </td>
                    </tr>
                    <tr>
                        <td class='mega-name'>
                            <?php _e("Sticky On Mobile", "megamenupro"); ?>
                            <div class='mega-description'>
                                <?php _e("Stick the menu to the top of the page when the page is viewed on a mobile?", "megamenupro"); ?>
                            </div>
                        </td>
                        <td class='mega-value'>
                            <select name='settings[sticky][mobile]'>
                                <option value='true' <?php selected('true', $sticky_mobile) ?>><?php _e("Yes", "megamenupro"); ?></option>
                                <option value='false' <?php selected('false', $sticky_mobile) ?>><?php _e("No", "megamenupro"); ?></option>
                            </select>
                            <div class='mega-description'><?php _e("Only set to 'Yes' if your menu is short enough to fully fit on the screen without completely covering the page content.", "megamenupro"); ?></div>
                        </td>
                    </tr>
                    <tr>
                        <td class='mega-name'>
                            <?php _e("Sticky Offset", "megamenupro"); ?>
                            <div class='mega-description'>
                                <?php _e("Distance between top of window and top of menu when stuck. Default: 0.", "megamenupro"); ?>
                            </div>
                        </td>
                        <td class='mega-value'>
                            <input type='number' name='settings[sticky][offset]' value='<?php echo $sticky_offset; ?>' /><span class='mega-after'>px</span>
                        </td>
                    </tr>
                </table>

                <?php

                    submit_button();

                ?>
            </form>
        </div>

        <?php
	}

}

endif;