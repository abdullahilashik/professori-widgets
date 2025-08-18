<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Research_Overview_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'research_overview';
    }

    public function get_title() {
        return esc_html__( 'Research Overview', 'tpw' );
    }

    public function get_icon() {
        return 'eicon-document-in-page';
    }

    public function get_categories() {
        return [ 'basic' ];
    }

    protected function _register_controls() {
        // --- Main Content ---
        $this->start_controls_section( 'section_content', [ 'label' => esc_html__( 'Content', 'tpw' ) ] );
        $this->add_control( 'main_title', [
            'label' => esc_html__( 'Section Title', 'tpw' ),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => esc_html__( 'Research Overview', 'tpw' ),
        ]);
        $this->add_control( 'description_text', [
            'label' => esc_html__( 'Description Text', 'tpw' ),
            'type' => \Elementor\Controls_Manager::WYSIWYG,
            'default' => '<p class="mb-4 text-gray-700 leading-relaxed">My research focuses on...</p>',
        ]);
        $this->end_controls_section();

        // --- Metrics Repeater ---
        $this->start_controls_section( 'section_metrics', [ 'label' => esc_html__( 'Metrics', 'tpw' ) ] );
        $this->add_control( 'metrics_title', [
            'label' => esc_html__( 'Metrics Box Title', 'tpw' ),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => esc_html__( 'Research Metrics', 'tpw' ),
        ]);
        
        $repeater = new \Elementor\Repeater();
        $repeater->add_control( 'metric_label', [ 'label' => esc_html__( 'Label', 'tpw' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Publications:' ]);
        $repeater->add_control( 'metric_value', [ 'label' => esc_html__( 'Value', 'tpw' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => '100+' ]);

        $this->add_control( 'metrics_list', [
            'label' => esc_html__( 'Metrics List', 'tpw' ),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                [ 'metric_label' => 'Publications:', 'metric_value' => '100+' ],
                [ 'metric_label' => 'Citations:', 'metric_value' => '1500+' ],
                [ 'metric_label' => 'Projects:', 'metric_value' => '20+' ],
                [ 'metric_label' => 'PhD Supervised:', 'metric_value' => '10+' ],
            ],
            'title_field' => '{{{ metric_label }}} {{{ metric_value }}}',
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
                        <?php echo esc_html( $settings['metrics_title'] ); ?>
                    </h3>
                    <?php if ( $settings['metrics_list'] ) : ?>
                        <ul class="space-y-3">
                            <?php foreach ( $settings['metrics_list'] as $item ) : ?>
                                <li class="flex justify-between">
                                    <span class="font-medium"><?php echo esc_html( $item['metric_label'] ); ?></span>
                                    <span class="text-accent font-bold"><?php echo esc_html( $item['metric_value'] ); ?></span>
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