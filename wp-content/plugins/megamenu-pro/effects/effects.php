<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // disable direct access
}

if ( ! class_exists('Mega_Menu_Effects') ) :

/**
 *
 */
class Mega_Menu_Effects {

	/**
	 * Constructor
	 *
	 * @since 1.1
	 */
	public function __construct() {

		add_filter( 'megamenu_effects', array( $this, 'add_fade_up_to_selector'), 10, 2 );
		add_filter( 'megamenu_javascript_localisation', array( $this, 'add_fade_up_effect'), 10 );

	}


	/**
	 * Add the fadeUp animation rules to the JS localisation
	 *
	 * @param array $effects
	 * @since 1.1
	 * @return array
	 */
	public function add_fade_up_effect($effects) {
		$effects['effect']['fadeUp'] = array(
            "in" => array(
                "animate" => array("opacity" => "show", "margin-top" => "0"),
                "css" => array("margin-top" => "10px")
            ),
            "out" => array(
                "animate" => array("opacity" => "hide", "margin-top" => "10px")
            )
		);

		return $effects;
	}


	/**
	 * Add "fade up" as an option to the menu effects
	 *
	 * @since 1.1
	 * @param array $effects
	 * @param string $selected
	 */
	public function add_fade_up_to_selector($effects, $selected) {

		$effects['fadeUp'] = array(
            'label' => __("Fade Up", "megamenupro"),
            'selected' => $selected == 'fadeUp',
		);

		return $effects;

	}

}

endif;