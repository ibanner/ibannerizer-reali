<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Dynamic Tag - Claiming User Data
 *
 * Elementor dynamic tag that returns data from the claiming user to the Alumnus page.
 *
 * @since 1.0.0
 */
class Elementor_Dynamic_Tag_Claiming_User_Data extends Elementor\Core\DynamicTags\Tag {

	/**
	 * Get dynamic tag name.
	 *
	 * Retrieve the name of the claiming user data tag.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Dynamic tag name.
	 */
	public function get_name() {
		return 'claiming-user-data';
	}

	/**
	 * Get dynamic tag title.
	 *
	 * Returns the title of the claiming user data tag.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Dynamic tag title.
	 */
	public function get_title() {
		return esc_html__( 'Claiming User Data', 'ibn' );
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
		return [ 'post' ];
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
			\Elementor\Modules\DynamicTags\Module::URL_CATEGORY,
			\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
			\Elementor\Modules\DynamicTags\Module::NUMBER_CATEGORY,
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
			'user_email' => esc_html__( 'Email', 'ibn' ),
			'alum_id' => esc_html__( 'Claimed Alumnus ID', 'ibn' ),
			'alum_name' => esc_html__( 'Claimed Alumnus Name', 'ibn' ),
			'email_consent' => esc_html__( 'Email Consent', 'ibn' ),
			'phone_num' => esc_html__( 'Phone Number', 'ibn' ),
			'about_alumni' => esc_html__( 'User Bio', 'ibn' ),
			'website_url' => esc_html__( 'Website URL', 'ibn' ),
			'facebook_url' => esc_html__( 'Facebook URL', 'ibn' ),
			'twitter_url' => esc_html__( 'Twitter URL', 'ibn' ),
			'linkedin_url' => esc_html__( 'LinkedIn URL', 'ibn' ),
			'instagram_url' => esc_html__( 'Instagram URL', 'ibn' ),
		);

		$this->add_control(
			'claiming_user_data',
			[
				'type' => \Elementor\Controls_Manager::SELECT,
				'label' => esc_html__( 'Claiming User Data', 'ibn' ),
				'options' => $data_labels,
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
		$claiming_user_data_selected = $this->get_settings( 'claiming_user_data' );

		if ( ! $claiming_user_data_selected ) {
			return;
		}

		$claiming_user = get_field( 'claiming_user' );

		if ( ! $claiming_user || ! isset( $claiming_user ) ) {
			return;
		}

		if( $claiming_user->has_prop( $claiming_user_data_selected ) ){
			echo $claiming_user->get( $claiming_user_data_selected ) ;
		} else {
			return;
		}
	}

}
