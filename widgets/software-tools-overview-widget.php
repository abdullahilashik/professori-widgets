<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Software_Tools_Overview_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'software_tools_overview';
    }

    public function get_title() {
        return esc_html__( 'Software Tools Overview', 'tpw' );
    }

    public function get_icon() {
        return 'eicon-wrench';
    }

    public function get_categories() {
        return [ 'basic' ];
    }

    protected function _register_controls() {
        // --- Main Content Section ---
        $this->start_controls_section( 'section_content', [ 'label' => esc_html__( 'Content', 'tpw' ) ] );
        $this->add_control( 'main_title', [
            'label' => esc_html__( 'Section Title', 'tpw' ),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => esc_html__( 'Software Tools', 'tpw' ),
        ]);
        $this->add_control( 'description_text', [
            'label' => esc_html__( 'Description Text', 'tpw' ),
            'type' => \Elementor\Controls_Manager::WYSIWYG,
            'default' => '<p class="mb-4 text-gray-700 leading-relaxed">Our research group develops specialized software tools...</p>',
        ]);
        $this->end_controls_section();

        // --- Statistics Repeater Section ---
        $this->start_controls_section( 'section_stats', [ 'label' => esc_html__( 'Statistics', 'tpw' ) ] );
        $this->add_control( 'stats_title', [
            'label' => esc_html__( 'Statistics Box Title', 'tpw' ),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => esc_html__( 'Software Statistics', 'tpw' ),
        ]);
        
        $repeater = new \Elementor\Repeater();
        $repeater->add_control( 'stat_label', [ 'label' => esc_html__( 'Label', 'tpw' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Total Tools:' ]);
        $repeater->add_control( 'stat_value', [ 'label' => esc_html__( 'Value', 'tpw' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => '12' ]);

        $this->add_control( 'stats_list', [
            'label' => esc_html__( 'Statistics List', 'tpw' ),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                [ 'stat_label' => 'Total Tools:', 'stat_value' => '12' ],
                [ 'stat_label' => 'Downloads:', 'stat_value' => '5,000+' ],
                [ 'stat_label' => 'Institutions Using:', 'stat_value' => '150+' ],
                [ 'stat_label' => 'Countries:', 'stat_value' => '35' ],
            ],
            'title_field' => '{{{ stat_label }}} {{{ stat_value }}}',
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
            <div class="grid md:grid-cols-3 gap-8">
                <div class="md:col-span-2">
                    <?php echo wp_kses_post( $settings['description_text'] ); ?>
                </div>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="font-semibold text-lg mb-4 text-primary">
                        <?php echo esc_html( $settings['stats_title'] ); ?>
                    </h3>
                    <?php if ( $settings['stats_list'] ) : ?>
                        <ul class="space-y-3">
                            <?php foreach ( $settings['stats_list'] as $item ) : ?>
                                <li class="flex justify-between">
                                    <span class="font-medium"><?php echo esc_html( $item['stat_label'] ); ?></span>
                                    <span class="text-accent font-bold"><?php echo esc_html( $item['stat_value'] ); ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }
}