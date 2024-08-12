<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Dynamic Tag - Award Data
 *
 * Elementor dynamic tag that holds information about the current award post.
 *
 * @since 1.0.0
 */
class Elementor_Dynamic_Tag_Award_Data extends Elementor\Core\DynamicTags\Tag {

	/**
	 * Get dynamic tag name.
	 *
	 * Retrieve the name of the award data tag.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Dynamic tag name.
	 */
	public function get_name() {
		return 'award-data';
	}

	/**
	 * Get dynamic tag title.
	 *
	 * Returns the title of the award data tag.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Dynamic tag title.
	 */
	public function get_title() {
		return esc_html__( 'Award Data', 'efw-alumni' );
	}

	/**
	 * Get dynamic tag groups.
	 *
	 * Retrieve the list of groups the award data tag belongs to.
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
	 * Retrieve the list of categories the award data tag belongs to.
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
	 * Add input fields to allow the user to customize the award data tag settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @return void
	 */
	protected function register_controls() {
		$data_labels = array(
			'award_recipient' => esc_html__( 'Award Recipient ID', 'efw-alumni' ),
			'recipient_alum_url' => esc_html__( 'Recipient Page URL', 'efw-alumni' ),
			'recipient_groups' => esc_html__( 'Recipient Page URL', 'efw-alumni' ),
            'award_name' => esc_html__( 'Name on Awarding', 'efw-alumni' ),
            'award_year' => esc_html__( 'Award Year', 'efw-alumni' ),
            'award_heb_year' => esc_html__( 'Award Hebrew Year', 'efw-alumni' ),
            'award_year_string' => esc_html__( 'Award Year (EN+HE)', 'efw-alumni' ),
            'award_link' => esc_html__( 'More Info Link', 'efw-alumni' ),
            'award_edit_link' => esc_html__( 'Edit Award Link', 'efw-alumni' ),
            'recipient_prefix' => esc_html__( 'Laureate Prefix', 'efw-alumni' ),
		);

		$this->add_control(
			'award_data',
			[
				'type' => \Elementor\Controls_Manager::SELECT,
				'label' => esc_html__( 'Award Data', 'efw-alumni' ),
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
		$award_data_selected = $this->get_settings( 'award_data' );

		if ( ! $award_data_selected ) {
			return;
		}

        $award = efw_get_award( get_the_ID() );

        switch ($award_data_selected) {
            case 'award_year_string':
                echo $award['award_year'] . ' - <span class="he-year">' . $award['award_heb_year'] . '</span>';
                break;
            
            case 'recipient_alum_url':
                echo get_permalink( $award['award_recipient'] );
                break;
            
			case 'award_edit_link':
                $post_id = get_the_ID();
				if ( $post_id ) {
					echo get_edit_post_link( $post_id );
				}
                break;
                
            default:
            echo $award[$award_data_selected];
                break;
        }
	}

}
