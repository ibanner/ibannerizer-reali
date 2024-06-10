<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * efw_class_list_shortcode
 * 
 * Generate the required HTML for the class list in a single al-class page.
 *
 * @return mixed
 */
function efw_class_list_shortcode($atts) {

  $class_id = is_tax('al-class') ? get_queried_object_id() : 105 ; // default value for testing
  if (isset( $_GET['class'] )) {
    $class_id = $_GET['class'];
  }
  
  $classdata = efw_get_class_alumnidata( $class_id );
  $alumni = $classdata['alumni'] ? unserialize( $classdata['alumni'] ) : NULL ;
  
  if ( $alumni ) {
    ?>
    <style>
      .alumni-class-list-wrapper {
        display: grid;
	      grid-template-columns: repeat(6,1fr);
        grid-column-gap: 30px;
        grid-row-gap: 30px;
        align-items: stretch;
      }

      @media (max-width: 767px) {
        .alumni-class-list-wrapper {
          grid-template-columns: repeat(2,1fr);
          grid-column-gap: 20px;
          grid-row-gap: 20px;
        }
      }

      .alumni-single-item {
        display: flex;
	      flex-direction: column;
        position: relative;
      }

      .alumni-single-item a {
        display: flex;
        min-height: 120px;
        flex-direction: column;
        justify-content: center;
	      align-items: center;
        background-color: #fff;
        padding: 10px;
        --container-widget-width: calc( 1 - 100% );
        --container-widget-height: initial;
        --container-widget-flex-grow: 0;
        --container-widget-align-self: initial;
        justify-content: flex-start;
        align-items: center;
        gap: 7px 0px;
        border-radius: 15px 15px 15px 15px;
        transition: background 0.3s, border 0.3s, box-shadow 0.3s, transform 0.3s
      }
      .rip-icon {
        position: absolute;
        top: 3px;
        right: 5px;
      }
      .rip-icon img {
        max-width: 20px;
        height: 20px;
        object-fit: contain;
      }

      svg.alumni-icon {
        fill: #CFE1EF;
      }

      h2.alumnus-name {
        font-family: var( --e-global-typography-text-font-family ), Arial, Sans-serif;
        font-size: var( --e-global-typography-text-font-size );
        font-weight: var( --e-global-typography-text-font-weight );
      }
    </style>
    <?php 
    if (isset($atts['title']) && 1 == $atts['title']) {
    ?>  
    <center><h1>מחזור <?php echo $classdata['class_name'] . ' - ' . count($alumni['data']); ?> בוגרים</h1></center>
    <?php
    }
    ?>
    <div class="alumni-class-list-wrapper">
      <?php
      foreach ( $alumni['data'] as $name => $data ) {
        if ( ! empty($data['flags']) && str_contains( $data['flags'] , 'non-grad' ) ) {
          $icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="alumni-icon" style="height:35px;"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"/></svg>';
        } else {
          $icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" class="alumni-icon" style="height:28px;"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M320 32c-8.1 0-16.1 1.4-23.7 4.1L15.8 137.4C6.3 140.9 0 149.9 0 160s6.3 19.1 15.8 22.6l57.9 20.9C57.3 229.3 48 259.8 48 291.9v28.1c0 28.4-10.8 57.7-22.3 80.8c-6.5 13-13.9 25.8-22.5 37.6C0 442.7-.9 448.3 .9 453.4s6 8.9 11.2 10.2l64 16c4.2 1.1 8.7 .3 12.4-2s6.3-6.1 7.1-10.4c8.6-42.8 4.3-81.2-2.1-108.7C90.3 344.3 86 329.8 80 316.5V291.9c0-30.2 10.2-58.7 27.9-81.5c12.9-15.5 29.6-28 49.2-35.7l157-61.7c8.2-3.2 17.5 .8 20.7 9s-.8 17.5-9 20.7l-157 61.7c-12.4 4.9-23.3 12.4-32.2 21.6l159.6 57.6c7.6 2.7 15.6 4.1 23.7 4.1s16.1-1.4 23.7-4.1L624.2 182.6c9.5-3.4 15.8-12.5 15.8-22.6s-6.3-19.1-15.8-22.6L343.7 36.1C336.1 33.4 328.1 32 320 32zM128 408c0 35.3 86 72 192 72s192-36.7 192-72L496.7 262.6 354.5 314c-11.1 4-22.8 6-34.5 6s-23.5-2-34.5-6L143.3 262.6 128 408z"/></svg>';
        }

        $is_fallen = ( ! empty($data['flags']) && str_contains( $data['flags'] , 'fallen' ) ) ? 1 : 0 ;
        $rip = ( 1 == $is_fallen ) ? ' <span class="rip">' . esc_html__( 'RIP', 'efw-alumni' ) . '</span>' : '';
        $display_name = $name . $rip;
        $url_base = site_url( '/alumnus/' );

        ?>
        <div class="alumni-single-item">
          <a href="<?php echo $url_base . $data['alum_id']; ?>">
            <?php if ( $is_fallen ): ?>
              <div class="rip-icon">
                <img src="https://alumni.reali.org.il/wp-content/uploads/2021/07/candleIcon.png" class="rip-icon">
              </div>
            <?php endif; ?>
            <div class="alumnus-icon-wrapper">
              <?php echo $icon; ?>
            </div>
            <h2 class="alumnus-name <?php echo $data['flags']; ?>" aria-label="<?php echo $name; ?>"><?php echo $display_name; ?></h2>
          </a>
        </div>
        <?php
      }
      ?>
    </div> <!-- alumni-class-list-wrapper -->
    <?php
  }
  
  }

add_shortcode( 'efw_classlist', 'efw_class_list_shortcode' );