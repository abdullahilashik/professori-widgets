<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Learning_Resources_Overview_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'learning_resources_overview';
    }

    public function get_title() {
        return esc_html__( 'Learning Resources Overview', 'tpw' );
    }

    public function get_icon() {
        return 'eicon-notebook';
    }

    public function get_categories() {
        return [ 'basic' ];
    }

    protected function _register_controls() {
        // ===================================
        // == Content Tab
        // ===================================

        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__( 'Content', 'tpw' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'main_title',
            [
                'label' => esc_html__( 'Section Title', 'tpw' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Learning Resources', 'tpw' ),
            ]
        );

        $this->add_control(
            'description_text',
            [
                'label' => esc_html__( 'Description Text', 'tpw' ),
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'default' => '<p class="mb-4 text-gray-700 leading-relaxed">This collection of tutorials and learning materials covers fundamental and advanced topics in power systems, renewable energy integration, and smart grid technologies.</p><p class="mb-4 text-gray-700 leading-relaxed">Materials include lecture slides, problem sets, solution manuals, and video tutorials that complement my courses and research areas.</p>',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_stats',
            [
                'label' => esc_html__( 'Statistics', 'tpw' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'stats_title',
            [
                'label' => esc_html__( 'Statistics Box Title', 'tpw' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Resources Statistics', 'tpw' ),
            ]
        );
        
        $repeater = new \Elementor\Repeater();
        $repeater->add_control( 'stat_label', [ 'label' => esc_html__( 'Label', 'tpw' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Total Tutorials:' ]);
        $repeater->add_control( 'stat_value', [ 'label' => esc_html__( 'Value', 'tpw' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => '45+' ]);

        $this->add_control(
            'stats_list',
            [
                'label' => esc_html__( 'Statistics List', 'tpw' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [ 'stat_label' => 'Total Tutorials:', 'stat_value' => '45+' ],
                    [ 'stat_label' => 'Video Hours:', 'stat_value' => '30+' ],
                    [ 'stat_label' => 'Problem Sets:', 'stat_value' => '25' ],
                    [ 'stat_label' => 'Downloaded:', 'stat_value' => '10,000+' ],
                ],
                'title_field' => '{{{ stat_label }}} {{{ stat_value }}}',
            ]
        );

        $this->end_controls_section();

        // ===================================
        // == Style Tab
        // ===================================

        // --- Main Title Style Section ---
        $this->start_controls_section(
            'section_style_main_title',
            [
                'label' => esc_html__( 'Main Title', 'tpw' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control( 'main_title_color', [ 'label' => esc_html__( 'Color', 'tpw' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .lr-main-title' => 'color: {{VALUE}};' ] ]);
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'main_title_typography', 'selector' => '{{WRAPPER}} .lr-main-title' ]);

        $this->end_controls_section();

        // --- Description Style Section ---
        $this->start_controls_section(
            'section_style_description',
            [
                'label' => esc_html__( 'Description', 'tpw' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control( 'description_color', [ 'label' => esc_html__( 'Color', 'tpw' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .lr-description, {{WRAPPER}} .lr-description p' => 'color: {{VALUE}};' ] ]);
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'description_typography', 'selector' => '{{WRAPPER}} .lr-description p' ]);
        
        $this->end_controls_section();

        // --- Statistics Box Style Section ---
        $this->start_controls_section(
            'section_style_stats',
            [
                'label' => esc_html__( 'Statistics Box', 'tpw' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control( 'stats_title_color', [ 'label' => esc_html__( 'Title Color', 'tpw' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .lr-stats-title' => 'color: {{VALUE}};' ] ]);
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'stats_title_typography', 'selector' => '{{WRAPPER}} .lr-stats-title' ]);

        $this->add_control('hr1', ['type' => \Elementor\Controls_Manager::DIVIDER]);

        $this->add_control( 'stats_label_color', [ 'label' => esc_html__( 'Label Color', 'tpw' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .lr-stats-label' => 'color: {{VALUE}};' ] ]);
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'stats_label_typography', 'selector' => '{{WRAPPER}} .lr-stats-label' ]);
        
        $this->add_control('hr2', ['type' => \Elementor\Controls_Manager::DIVIDER]);

        $this->add_control( 'stats_value_color', [ 'label' => esc_html__( 'Value Color', 'tpw' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .lr-stats-value' => 'color: {{VALUE}};' ] ]);
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'stats_value_typography', 'selector' => '{{WRAPPER}} .lr-stats-value' ]);
        
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
            <h2 class="text-2xl font-bold text-primary mb-6 pb-2 border-b border-gray-200 lr-main-title">
                <?php echo esc_html( $settings['main_title'] ); ?>
            </h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="md:col-span-2 lr-description">
                    <?php echo wp_kses_post( $settings['description_text'] ); ?>
                </div>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="font-semibold text-lg mb-4 text-primary lr-stats-title">
                        <?php echo esc_html( $settings['stats_title'] ); ?>
                    </h3>
                    <?php if ( $settings['stats_list'] ) : ?>
                        <ul class="space-y-3">
                            <?php foreach ( $settings['stats_list'] as $item ) : ?>
                                <li class="flex justify-between">
                                    <span class="font-medium lr-stats-label"><?php echo esc_html( $item['stat_label'] ); ?></span>
                                    <span class="text-accent font-bold lr-stats-value"><?php echo esc_html( $item['stat_value'] ); ?></span>
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