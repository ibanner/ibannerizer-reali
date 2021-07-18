<?php 

function ibn_login_style() { ?>
    <style type="text/css">
        body {
            background-color: #ffffff !important;
        }

        #login h1 a, .login h1 a {
            background-image: url(<?php echo plugin_dir_url( __FILE__ ).'img/ibanner_logo.png' ?>);
        height:100px;
        width:300px;
        background-size: 300px 100px;
        background-repeat: no-repeat;
        padding-bottom: 10px;
        }
    </style>
<?php }

add_action( 'login_enqueue_scripts', 'ibn_login_style' );

function ibn_login_logo_url() {
    $ibanner_url = "https://ibanner.co.il";
    return $ibanner_url;
}
add_filter( 'login_headerurl', 'ibn_login_logo_url' );
 
function ibn_login_logo_url_title() {
    return 'Itay Banner - The Contechnician';
}
add_filter( 'login_headertitle', 'ibn_login_logo_url_title' );