<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Dynamic Tag - Claiming User Current Photo
 *
 * Elementor dynamic tag that returns the claiming user's current photo to the Alumnus page.
 *
 * @since 1.0.0
 */

 class Elementor_Dynamic_Tag_Claiming_User_Current_Photo extends \Elementor\Core\DynamicTags\Data_Tag
{
    public function get_categories()
    {
        return [
			\Elementor\Modules\DynamicTags\Module::URL_CATEGORY,
			\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
			\Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY,
			\Elementor\Modules\DynamicTags\Module::POST_META_CATEGORY,
			 ];
    }

    public function get_group()
    {
        return [ 'efw-utility' ];
    }

    public function get_title()
    {
        return esc_html__( 'Claiming User Photo', 'efw-alumni' );
    }

    public function get_name()
    {
        return "claiming-user-photo";
    }

    public function get_value( array $options = array() )
    {
        
        $claiming_user = ( is_page('update') ? wp_get_current_user() : get_field( 'claiming_user' ) );

		if ( ! $claiming_user || ! isset( $claiming_user ) ) {
			return;
		}

        if( $claiming_user->has_prop( 'current_photo' ) ){

            $pid = $claiming_user->get( 'current_photo' );
            if ($pid) {
                return [
                    'id' => $pid,
                    'url' => wp_get_attachment_image_src($pid, 'full')[0],
                ]; 
            } else {
                return FALSE;
            }
		}
    }
}