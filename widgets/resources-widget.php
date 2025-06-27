<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Teaching_Resources_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'teaching_resources';
    }

    public function get_title() {
        return esc_html__( 'Teaching Resources', 'tpw' );
    }

    public function get_icon() {
        return 'eicon-folder-open';
    }

    public function get_categories() {
        return [ 'basic' ];
    }

    protected function _register_controls() {

        // --- Main Title Control ---
        $this->start_controls_section(
            'section_title',
            [
                'label' => esc_html__( 'Title', 'tpw' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'main_title',
            [
                'label' => esc_html__( 'Section Title', 'tpw' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Teaching Resources', 'tpw' ),
            ]
        );

        $this->end_controls_section();


        // --- Repeater for Resource Cards ---
        $this->start_controls_section(
            'section_resources_list',
            [
                'label' => esc_html__( 'Resource Cards', 'tpw' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'resource_icon',
            [
                'label' => esc_html__( 'Icon', 'tpw' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-book',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $repeater->add_control(
            'resource_title', [
                'label' => esc_html__( 'Title', 'tpw' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Resource Title' , 'tpw' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'resource_description', [
                'label' => esc_html__( 'Description', 'tpw' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => esc_html__( 'Resource description goes here.' , 'tpw' ),
            ]
        );

        $repeater->add_control(
            'resource_link', [
                'label' => esc_html__( 'Link', 'tpw' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-link.com', 'tpw' ),
                'show_external' => true,
                'default' => [
                    'url' => '#',
                    'is_external' => true,
                    'nofollow' => true,
                ],
            ]
        );

        $repeater->add_control(
            'resource_link_text', [
                'label' => esc_html__( 'Link Text', 'tpw' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Learn More →' , 'tpw' ),
            ]
        );


        $this->add_control(
            'resource_cards',
            [
                'label' => esc_html__( 'Resource Cards', 'tpw' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'resource_title' => 'Lecture Notes',
                        'resource_description' => 'Comprehensive notes for all courses I teach, regularly updated with the latest developments.',
                        'resource_link_text' => 'Browse Notes →',
                    ],
                    [
                        'resource_title' => 'Assignments',
                        'resource_description' => 'Problem sets and projects designed to reinforce theoretical concepts with practical applications.',
                         'resource_link_text' => 'View Assignments →',
                    ],
                    [
                        'resource_title' => 'Lab Materials',
                        'resource_description' => 'Guides and resources for laboratory sessions accompanying theoretical courses.',
                         'resource_link_text' => 'Access Labs →',
                    ],
                ],
                'title_field' => '{{{ resource_title }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <!-- Following best practice, we render only the inner content, not the outer <section> tag -->
        <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
            <h2 class="text-2xl font-bold text-primary mb-6 pb-2 border-b border-gray-200">
                <?php echo esc_html( $settings['main_title'] ); ?>
            </h2>
            
            <?php if ( $settings['resource_cards'] ) : ?>
            <div class="grid md:grid-cols-3 gap-6">
                <?php foreach ( $settings['resource_cards'] as $item ) : ?>
                    <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition">
                        <div class="text-accent mb-4">
                            <?php \Elementor\Icons_Manager::render_icon( $item['resource_icon'], [ 'class' => 'h-10 w-10', 'aria-hidden' => 'true' ] ); ?>
                        </div>
                        <h3 class="font-semibold text-lg mb-2">
                            <?php echo esc_html( $item['resource_title'] ); ?>
                        </h3>
                        <p class="text-gray-600 text-sm mb-4">
                            <?php echo esc_html( $item['resource_description'] ); ?>
                        </p>
                        <?php
                        if ( ! empty( $item['resource_link']['url'] ) ) {
                            $this->add_link_attributes( 'link-' . $item['_id'], $item['resource_link'] );
                        }
                        ?>
                        <a <?php echo $this->get_render_attribute_string( 'link-' . $item['_id'] ); ?> class="text-accent text-sm font-medium hover:underline">
                            <?php echo esc_html( $item['resource_link_text'] ); ?>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php
    }
}