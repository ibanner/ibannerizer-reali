<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Dynamic Tag - Alumni Name Format
 *
 * Elementor dynamic tag that returns displays alumni name in a desired format.
 *
 * @since 1.0.0
 */
class Elementor_Dynamic_Tag_Alumni_Name_Format extends Elementor\Core\DynamicTags\Tag {

	/**
	 * Get dynamic tag name.
	 *
	 * Retrieve the name of the dynamic data tag.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Dynamic tag name.
	 */
	public function get_name() {
		return 'alumni-name-format';
	}

	/**
	 * Get dynamic tag title.
	 *
	 * Returns the title of the dynamic data tag.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Dynamic tag title.
	 */
	public function get_title() {
		return esc_html__( 'Alumni Name Format', 'efw-alumni' );
	}

	/**
	 * Get dynamic tag groups.
	 *
	 * Retrieve the list of groups the claiming user data tag belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Dynamic tag groups.
	 */
	public function get_group() {
		return [ 'efw-utility' ];
	}

	/**
	 * Get dynamic tag categories.
	 *
	 * Retrieve the list of categories the claiming user data tag belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Dynamic tag categories.
	 */
	public function get_categories() {
		return [
			\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
			\Elementor\Modules\DynamicTags\Module::POST_META_CATEGORY,
			 ];
	}

	/**
	 * Register dynamic tag controls.
	 *
	 * Add input fields to allow the user to customize the claiming user data tag settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @return void
	 */
	protected function register_controls() {
		$data_labels = array(
			'full_original' => esc_html__( 'Original full name', 'efw-alumni' ),
			// 'full_original_nickname' => esc_html__( 'Original full name with nicknames', 'efw-alumni' ),
			'full_current' => esc_html__( 'Current full name', 'efw-alumni' ),
			// 'full_current_nickname' => esc_html__( 'Current full name with nicknames', 'efw-alumni' ),
		);

		$this->add_control(
			'alumni_name_format',
			[
				'type' => \Elementor\Controls_Manager::SELECT,
				'label' => esc_html__( 'Format', 'efw-alumni' ),
				'options' => $data_labels,
                'default' => 'full_current',
			]
		);

        $this->add_control(
			'alumni_nickname',
			[
				'type' => \Elementor\Controls_Manager::SELECT,
				'label' => esc_html__( 'Nickname?', 'efw-alumni' ),
				'options' => [
					'yes' => esc_html__( 'Include', 'efw-alumni' ),
					'no' => esc_html__( 'Don\'t include', 'efw-alumni' ),
				],
				'default' => 'no',
			]
		);

        $this->add_control(
			'alumni_rip',
			[
				'type' => \Elementor\Controls_Manager::SELECT,
				'label' => esc_html__( 'RIP', 'efw-alumni' ),
				'options' => [
					'yes' => esc_html__( 'Include', 'efw-alumni' ),
					'no' => esc_html__( 'Don\'t include', 'efw-alumni' ),
				],
				'default' => 'no',
			]
		);
        
		$this->add_control(
			'user_override',
			[
				'type' => \Elementor\Controls_Manager::SELECT,
				'label' => esc_html__( 'Claiming User Names', 'efw-alumni' ),
				'options' => [
					'yes' => esc_html__( 'Override', 'efw-alumni' ),
					'no' => esc_html__( 'Ignore', 'efw-alumni' ),
				],
				'default' => 'no',
			]
		);
	}

	/**
	 * Render tag output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function render() {
		$format = $this->get_settings( 'alumni_name_format' );
        $nickname = ( 'yes' == $this->get_settings( 'alumni_nickname' ) ? 1 : 0 );
        $rip = ( 'yes' == $this->get_settings( 'alumni_rip' ) ? 1 : 0 );
        $user_override = ( 'yes' == $this->get_settings( 'user_override' ) ? 1 : 0 );

		echo efw_get_alumnus_name( get_the_ID() , $format , $nickname , $rip , $user_override );
	}

}
