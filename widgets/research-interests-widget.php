<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Research_Interests_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'research_interests_grid';
    }

    public function get_title() {
        return esc_html__( 'Research Interests Grid', 'tpw' );
    }

    public function get_icon() {
        return 'eicon-info-circle-o';
    }

    public function get_categories() {
        return [ 'basic' ];
    }

    protected function _register_controls() {
        // --- Main Section Title ---
        $this->start_controls_section( 'section_title', [ 'label' => esc_html__( 'Title', 'tpw' ) ] );
        $this->add_control( 'main_title', [
            'label' => esc_html__( 'Section Title', 'tpw' ),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => esc_html__( 'Research Interests', 'tpw' ),
        ]);
        $this->end_controls_section();

        // --- Interests Repeater ---
        $this->start_controls_section( 'section_interests', [ 'label' => esc_html__( 'Interests', 'tpw' ) ] );
        
        $repeater = new \Elementor\Repeater();
        $repeater->add_control( 'interest_icon', [
            'label' => esc_html__( 'Icon', 'tpw' ),
            'type' => \Elementor\Controls_Manager::ICONS,
            'default' => [ 'value' => 'fas fa-bolt', 'library' => 'fa-solid' ],
        ]);
        $repeater->add_control( 'interest_title', [
            'label' => esc_html__( 'Interest Title', 'tpw' ),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Interest Title',
            'label_block' => true,
        ]);
        $repeater->add_control( 'interest_description', [
            'label' => esc_html__( 'Description', 'tpw' ),
            'type' => \Elementor\Controls_Manager::TEXTAREA,
            'default' => 'A brief description of the research interest.',
        ]);
        $repeater->add_control( 'interest_link', [
            'label' => esc_html__( 'Link (Optional)', 'tpw' ),
            'type' => \Elementor\Controls_Manager::URL,
            'placeholder' => esc_html__( 'https://your-link.com', 'tpw' ),
        ]);

        $this->add_control( 'interest_list', [
            'label' => esc_html__( 'Interest List', 'tpw' ),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                [ 'interest_title' => 'Power System Stability', 'interest_description' => 'Developing novel methods for analyzing and enhancing power system stability...' ],
                [ 'interest_title' => 'Renewable Energy Integration', 'interest_description' => 'Investigating strategies for large-scale integration of renewable energy sources...' ],
                [ 'interest_title' => 'Smart Grid Technologies', 'interest_description' => 'Exploring advanced monitoring, control, and optimization techniques...' ],
            ],
            'title_field' => '{{{ interest_title }}}',
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
            <h2 class="text-2xl font-bold text-primary mb-6 pb-2 border-b border-gray-200">
                <?php echo esc_html( $settings['main_title'] ); ?>
            </h2>
            
            <?php if ( $settings['interest_list'] ) : ?>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ( $settings['interest_list'] as $index => $item ) : 
                        $tag = 'div';
                        $link_key = 'link_' . $index;

                        if ( ! empty( $item['interest_link']['url'] ) ) {
                            $tag = 'a';
                            $this->add_link_attributes( $link_key, $item['interest_link'] );
                        }
                    ?>
                        <<?php echo $tag; ?> <?php echo $this->get_render_attribute_string( $link_key ); ?> class="block bg-gray-50 p-6 rounded-lg hover:shadow-md transition">
                            <div class="text-accent mb-3">
                                <?php \Elementor\Icons_Manager::render_icon( $item['interest_icon'], [ 'class' => 'h-8 w-8', 'aria-hidden' => 'true' ] ); ?>
                            </div>
                            <h3 class="font-semibold text-lg mb-2"><?php echo esc_html( $item['interest_title'] ); ?></h3>
                            <p class="text-gray-600 text-sm"><?php echo esc_html( $item['interest_description'] ); ?></p>
                        </<?php echo $tag; ?>>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}