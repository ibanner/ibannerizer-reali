<?php

function ibn_settings_init() {
    // Register a new setting for "ibn" page.
    register_setting(
      'reading',
      'ga_tracking_code',
    );

    // Register a new section in the "ibn" page.
    add_settings_section(
        'ibn_ga_tracking_code_section',
        __( 'Google Analytics', 'ibannerizer' ),
        'ibn_ga_tracking_code_section_cb',
        'reading'
    );

    // Register a new field in the "ibn_section_developers" section, inside the "ibn" page.
    add_settings_field(
        'ibn_ga_tracking_code_field', // As of WP 4.6 this value is used only internally.
                                // Use $args' label_for to populate the id inside the callback.
        __( 'Tracking Code', 'ibannerizer' ),
        'ibn_ga_tracking_code_field_cb',
        'reading',
        'ibn_ga_tracking_code_section',
    );
}

function ibn_ga_tracking_code_section_cb( $args ) {
    ?>
    <p><?php echo __( 'Enter your Google Analytics tracking code here (e.g. UA-123456789-1).', 'ibannerizer' ) ?></p>
    <?php
}

function ibn_ga_tracking_code_field_cb( $args ) {
    // Get the value of the setting we've registered with register_setting()
    $ga_code = get_option( 'ga_tracking_code' );
    ?>
    <input type="text" name="ga_tracking_code" value="<?php echo isset( $ga_code ) ? esc_attr( $ga_code ) : ''; ?>">
    <?php
}

/**
 * Register our ibn_settings_init to the admin_init action hook.
 */
add_action( 'admin_init', 'ibn_settings_init' );

function ibn_add_googleanalytics() {

  $ga_code = get_option( 'ga_tracking_code' );
  ?>

  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $ga_code; ?>"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', <?php echo $ga_code; ?>);
  </script>

<?php }

add_action('wp_head', 'ibn_add_googleanalytics');
