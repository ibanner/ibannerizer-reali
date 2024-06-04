<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class EFW_Loginout_url extends \Elementor\Core\DynamicTags\Tag {

	public function get_name() {
		return 'efw-loginout-url';
	}

	public function get_title() {
		return esc_html__( 'Log-in/Log-out URL', 'efw-alumni' );
	}

	public function get_group() {
		return [ 'site' ];
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::URL_CATEGORY ];
	}

	protected function register_controls() {
		$this->add_control(
			'redirect',
			[
				'type' => \Elementor\Controls_Manager::SELECT,
				'label' => esc_html__( 'Redirect', 'efw-alumni' ),
				'options' => [
					'home' => esc_html__( 'Homepage', 'efw-alumni' ),
					'current' => esc_html__( 'Current page', 'efw-alumni' ),
				],
				'default' => 'home',
			]
		);
	}

	public function render() {
		$redirect = ('current' == $this->get_settings( 'redirect' ) ? $_SERVER['REQUEST_URI'] : site_url() );
		if ( ! is_user_logged_in() ) {
			echo esc_url( wp_login_url( $redirect ) );
		} else {
			echo esc_url( wp_logout_url( $redirect ) );
		}
	}

}