<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function efw_login_style() { ?>
    <style type="text/css">
        body {
            background-color: #ffffff !important;
            font-family: 'Alef', Arial, Sans-serif;
        }

        #login {
            width: 100% !important;
        }

        #login form {
            width: 320px;
            margin: 20px auto;
        }

        #login p {
            text-align: center;
        }

        #login h1 a, .login h1 a {
            background-image: url(<?php echo esc_url( wp_get_attachment_url( get_theme_mod( 'custom_logo' ) ) ) ?>);
            height:100px;
            width:auto;
            background-size: auto 100px;
            background-repeat: no-repeat;
            padding-bottom: 10px;
        }

        #efw-login-footer {
            text-align: center;
            position: absolute;
            bottom: 0;
            padding-bottom: 20px;
            width: 100%;
        }

        #efw-login-title {
            width: 100%;
            background-color: #0B4596;
            color: #fff;
            padding: 20px;
        }

        #efw-login-title h2 {
            text-align: center;
        }


    </style>
<?php }

add_action( 'login_enqueue_scripts', 'efw_login_style' );

function efw_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'efw_login_logo_url' );

function efw_login_message() {
    echo '<div id="efw-login-title">';
    echo '<h2>' . get_bloginfo('name') . '</h2>';
    echo '</div>';
}
add_filter( 'login_message', 'efw_login_message' );

function efw_login_footer() {
    $alum_url = site_url() . '/alumnus/14152/';
    $class_url = site_url() . '/al-class/%d7%a2%d7%93/';
    echo '<div id="efw-login-footer">';
    echo '<h3><strong>איפיון ופיתוח:</strong> <a href="' . $alum_url . '" rel="noopener">איתי בנר</a> (מחזור <a href="' . $class_url . '"><strong>ע"ד</strong></a>) - <a href="https://effective-web.co.il" target="_blank" rel="noopener">Effective Web</a></h3>';
    echo '</div>';
}
add_filter( 'login_footer', 'efw_login_footer' );

add_filter( 'login_display_language_dropdown', '__return_false' );

/**
 * efw_page_template_redirect
 * 
 * Setting redirect destinations for special cases
 *
 * @return void
 */
function efw_page_template_redirect() {
    if ( ( is_page( 'me' ) || is_page( 'update') ) && ! is_user_logged_in() ) {
        wp_redirect( site_url() );
        exit();
    } elseif ( is_page( 'me' ) && is_user_logged_in() ) {
        wp_redirect( efw_get_current_user_claimed_alum_permalink() );
        exit();
    }
}
add_action( 'template_redirect', 'efw_page_template_redirect' );