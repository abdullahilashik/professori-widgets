<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Teaching_Philosophy_Widget extends \Elementor\Widget_Base {

    // Unique name for the widget
    public function get_name() {
        return 'teaching_philosophy';
    }


    // =================================================================
    //  ADD THIS NEW FUNCTION TO REMOVE THE DEFAULT WRAPPER SPACING
    // =================================================================
    public function get_html_wrapper_class() {
        return parent::get_html_wrapper_class() . ' elementor-widget-empty';
    }

    // The widget title that will be displayed in the Elementor panel
    public function get_title() {
        return esc_html__( 'Teaching Philosophy', 'tpw' );
    }

    // The widget icon
    public function get_icon() {
        return 'eicon-graduation-cap';
    }

    // The category the widget will appear in
    public function get_categories() {
        return [ 'basic' ];
    }

    // This is where you define the editable controls
    protected function _register_controls() {

        // --- Content Section ---
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Content', 'tpw' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'philosophy_title',
            [
                'label' => esc_html__( 'Section Title', 'tpw' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Teaching Philosophy', 'tpw' ),
            ]
        );

        $this->add_control(
            'philosophy_text',
            [
                'label' => esc_html__( 'Philosophy Text', 'tpw' ),
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'default' => '<p class="mb-4 text-gray-700 leading-relaxed">My teaching approach focuses on bridging theoretical concepts with practical applications...</p>'
            ]
        );

        $this->end_controls_section();


        // --- Awards Section ---
        $this->start_controls_section(
            'awards_section',
            [
                'label' => esc_html__( 'Awards', 'tpw' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'awards_title',
            [
                'label' => esc_html__( 'Awards Box Title', 'tpw' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Teaching Awards', 'tpw' ),
            ]
        );
        
        // This is the REPEATER control for the awards list
        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'award_text',
            [
                'label' => esc_html__( 'Award Description', 'tpw' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Best Teacher Award, BUET (Year)', 'tpw' ),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'awards_list',
            [
                'label' => esc_html__( 'Awards List', 'tpw' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [ 'award_text' => 'Best Teacher Award, BUET (Year)' ],
                    [ 'award_text' => 'Excellence in Teaching Award (Year)' ],
                ],
                'title_field' => '{{{ award_text }}}',
            ]
        );

        $this->end_controls_section();
    }


    // This is where you render the final HTML on the page
    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <section class="mb-16">
            <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
                <h2 class="text-2xl font-bold text-primary mb-6 pb-2 border-b border-gray-200">
                    <?php echo esc_html( $settings['philosophy_title'] ); ?>
                </h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="md:col-span-2">
                        <?php echo wp_kses_post( $settings['philosophy_text'] ); // Use wp_kses_post for WYSIWYG content ?>
                    </div>
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="font-semibold text-lg mb-4 text-primary">
                            <?php echo esc_html( $settings['awards_title'] ); ?>
                        </h3>
                        <?php if ( $settings['awards_list'] ) : ?>
                            <ul class="space-y-3">
                                <?php foreach ( $settings['awards_list'] as $item ) : ?>
                                    <li class="flex items-start">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-accent mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                        </svg>
                                        <span><?php echo esc_html( $item['award_text'] ); ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
        <?php
    }
}