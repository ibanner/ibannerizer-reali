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
        'show-rip',
        [
            'label' => esc_html__( 'Show Rip', 'efw-alumni' ),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__( 'Show', 'efw-alumni' ),
            'label_off' => esc_html__( 'Hide', 'efw-alumni' ),
            'return_value' => '1',
            'default' => '1',
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
            'placeholder' => esc_html__( 'No people found with that name.', 'efw-alumni' ),
            'default' => esc_html__( 'No people found with that name.', 'efw-alumni' ),
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

    $this->start_controls_section(
        'section_rip',
        [
            'label' => esc_html__( 'RIP Suffix', 'efw-alumni' ),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]
    );

    $this->add_group_control(
        \Elementor\Group_Control_Typography::get_type(),
        [
            'name' => 'rip_typography',
            'label' => esc_html__( 'Typography', 'efw-alumni' ),
            'selector' => '{{WRAPPER}} li .rip',
        ]
    );

    $this->add_control(
        'rip_color',
        [
            'label' => esc_html__( 'Color', 'efw-alumni' ),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => 'var( --e-global-color-primary )',
            'selectors' => [
                '{{WRAPPER}} li .rip' => 'color: {{VALUE}}',
            ],
        ]
    );

    $this->end_controls_section();
}

protected function render() {

    global $post;
    
    $sorted_list = $_SESSION['_sorted_list'];
    $settings = $this->get_settings_for_display();
    $user_override = ( 'yes' == $this->get_settings( 'user_override' ) ? 1 : 0 );

    $this->add_render_attribute(
        'wrapper',
        [
            'id' => 'alumni-results',
            'aria-label' => $this->get_title(),
        ]
    );

    if ( ! $sorted_list ) {
        ?>
        <div class="no-results">
            <h2><?php echo $settings['no-results-message']; ?></h2>
            <?php // if (current_user_can('manage_options')) {echo do_shortcode('[rlv_didyoumean]');} ?>
            <?php echo do_shortcode('[rlv_didyoumean]'); ?>
        </div>
        <?php
    } else {
        $count = count($sorted_list);
        $i = 1;

        foreach ( $sorted_list as $alum ) {
            $grad_class = wp_get_post_terms($alum->ID,'al-class');
            $class_attr = implode(' ' , get_post_class( '', $alum->ID ));
            $name = efw_get_alumnus_name( $alum->ID , 'full_current' , 0 , $settings['show-rip'] , $user_override );
            $this->add_render_attribute(
                'alumnus',
                [
                    'class' => 'alumni-single-result' . ' ' .  $class_attr,
                    'aria-label' => $name,
                ]
            );

            ?>
            <?php if ( (isset($prev) && $grad_class != $prev ) || !isset($prev) ) { 
                $class_title = ( $grad_class ? esc_html__( 'Class of', 'efw-alumni' ) . ' ' . $grad_class[0]->name : $class_title = $settings['school-community'] );
                echo ( 1 != $count? '</ul>' : '' );
                ?>
                <h2><?php echo $class_title; ?></h2>
                <ul>
            <?php } ?>

            <li <?php echo $this->get_render_attribute_string( 'alumnus' ); ?>><a href="<?php echo get_permalink($alum->ID); ?>"><?php echo $name; ?></a></li>

            <?php
            if ( $i == $count ) { 
                echo '</ul>'; 
            } else {
                $prev = $grad_class;
                $i++;
            }
        }
    }
}

protected function content_template() {
    ?>
	<# 
    view.addRenderAttribute( 'wrapper', 'id', 'alumni-results' );
    #>
    <h2>מחזור ע"א</h2>
    <ul>
        <li class="alumni-single-result"><a href="https://alumni.reali.org.il/alumnus/12929/">צור בנר</a></li>
    </ul>
    <h2>מחזור ע"ד</h2>
    <ul>
        <li class="alumni-single-result"><a href="https://alumni.reali.org.il/alumnus/14152/">איתי בנר</a></li>
    </ul>
	<?php
}

}