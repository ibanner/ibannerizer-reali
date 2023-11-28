<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class EFW_Alumni_Search_Results extends \Elementor\Widget_Base {

public function get_name() {
    return 'alumni-search-results';
}

public function get_title() {
    return esc_html__( 'Alumni Search Results', 'efw-alumni' );
}

public function get_icon() {
    return 'eicon-search-results';
}

public function get_custom_help_url() {
    return 'https://effective-web.co.il/contact-us/';
}

public function get_categories() {
    return 'efw-widgets';
}

public function get_keywords() {
    return [ 'alumni', 'בוגרים', 'classes', 'מחזורים' ];
}

protected function register_controls() {
    $this->start_controls_section(
        'content_section',
        [
            'label' => esc_html__( 'Content', 'efw-alumni' ),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]
    );

    $this->add_control(
        'show_rip',
        [
            'label' => esc_html__( 'Show Rip', 'efw-alumni' ),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__( 'Show', 'efw-alumni' ),
            'label_off' => esc_html__( 'Hide', 'efw-alumni' ),
            'return_value' => 'yes',
            'default' => 'yes',
        ]
    );

    $this->add_control(
        'school-community',
        [
            'type' => \Elementor\Controls_Manager::TEXT,
            'label' => esc_html__( 'Non students title', 'efw-alumni' ),
            'placeholder' => esc_html__( 'School Community', 'efw-alumni' ),
            'default' => esc_html__( 'School Community', 'efw-alumni' ),
        ]
    );

    $this->add_control(
        'no-results-message',
        [
            'type' => \Elementor\Controls_Manager::TEXT,
            'label' => esc_html__( '"No Results" text', 'efw-alumni' ),
            'placeholder' => esc_html__( 'No people found with the name:', 'efw-alumni' ),
        ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
        'section_class_titles',
        [
            'label' => esc_html__( 'Class Titles', 'efw-alumni' ),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]
    );

    $this->add_group_control(
        \Elementor\Group_Control_Typography::get_type(),
        [
            'name' => 'class_titles_typography',
            'label' => esc_html__( 'Typography', 'efw-alumni' ),
            'selector' => '{{WRAPPER}} h2',
        ]
    );

    $this->add_control(
        'class_titles_color',
        [
            'label' => esc_html__( 'Color', 'efw-alumni' ),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => 'var( --e-global-color-primary )',
            'selectors' => [
                '{{WRAPPER}} h2' => 'color: {{VALUE}}',
            ],
        ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
        'section_alumni_names',
        [
            'label' => esc_html__( 'Alumni Names', 'efw-alumni' ),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]
    );

    $this->add_group_control(
        \Elementor\Group_Control_Typography::get_type(),
        [
            'name' => 'alumni_names_typography',
            'label' => esc_html__( 'Typography', 'efw-alumni' ),
            'selector' => '{{WRAPPER}} li',
        ]
    );

    $this->add_control(
        'alumni_names_color',
        [
            'label' => esc_html__( 'Color', 'efw-alumni' ),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => 'var( --e-global-color-primary )',
            'selectors' => [
                '{{WRAPPER}} li' => 'color: {{VALUE}}',
            ],
        ]
    );

    $this->end_controls_section();
}

protected function render() {

    global $post;
    
    $results = $_SESSION['_nested_list'];

    $settings = $this->get_settings_for_display();

    $this->add_render_attribute(
        'wrapper',
        [
            'id' => 'alumni-results',
            'aria-label' => $this->get_title(),
        ]
    );

    

    function efw_is_fallen( $classes ) {
        return ( in_array( 'group-fallen' , $classes) ? TRUE : FALSE );
    }

    if ( ! $results ) {
        ?>
        <div class="no-results">
            <h2><?php echo $settings['no-results-message']; ?></h2>
            <div>
                <?php echo esc_html__( 'Perhaps try other names?', 'efw-alumni' ); ?>
            </div>
        </div>
        <?php
    } else {
        
        foreach ($results as $class => $alumni) {
            $class_title = $settings['school-community'];

            if ($class) {
                $class_title = esc_html__( 'Class of', 'efw-alumni' ) . ' ' . $class;
            }

            ?>
            <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
                <h2><?php echo $class_title; ?></h2>
                <ul>
                
                <?php 

                $args = array( 
                    'posts_per_page' => 99, 
                    'post_type' => 'alumnus', 
                    'post_status' => 'publish',
                    'post__in' => $alumni,
                );

                $alumni_query = new WP_Query( $args );

                if( $alumni_query->have_posts() ){
                    while( $alumni_query->have_posts() ){
                    
                        $alumni_query->the_post();

                        $classes = get_post_class( '', $post->ID );

                        $this->add_render_attribute(
                            'alumnus',
                            [
                                'class' => [ 'alumni-single-result', esc_attr( implode( ' ', $classes ) ) ],
                                'aria-label' => $this->get_title(),
                            ]
                        );

                        ?>
                            <li <?php echo $this->get_render_attribute_string( 'alumnus' ); ?>><a href="<?php echo get_permalink(); ?>"><?php echo efw_get_alumnus_name( get_the_ID() ); ?></a></li>
                        <?php
                    }
                }

                ?>
            </div> <!-- End .class-wrapper -->
            <?php
        wp_reset_postdata();
        }
    }
}

protected function content_template() {
    ?>
	<# if ( 'yes' === settings.show_title ) { #>
		<h3>{{{ settings.title }}}</h3>
        <div class="">
            <h3>
	<# } #>

	<?php
}

}