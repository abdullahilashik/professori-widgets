<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Current_Research_Projects_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'current_research_projects';
    }

    public function get_title() {
        return esc_html__( 'Current Projects List', 'tpw' );
    }

    public function get_icon() {
        return 'eicon-posts-group';
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
            'default' => esc_html__( 'Current Research Projects', 'tpw' ),
        ]);
        $this->end_controls_section();

        // --- Projects Repeater ---
        $this->start_controls_section( 'section_projects', [ 'label' => esc_html__( 'Projects', 'tpw' ) ] );
        
        $repeater = new \Elementor\Repeater();
        $repeater->add_control( 'project_title', [ 'label' => esc_html__( 'Project Title', 'tpw' ), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Project Title', 'label_block' => true ]);
        $repeater->add_control( 'project_duration', [ 'label' => esc_html__( 'Duration', 'tpw' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => '2021-2024' ]);
        $repeater->add_control( 'project_sponsor', [ 'label' => esc_html__( 'Sponsor / Collaboration', 'tpw' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Sponsored by: ...' ]);
        $repeater->add_control( 'project_description', [ 'label' => esc_html__( 'Description', 'tpw' ), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Project description...' ]);
        $repeater->add_control( 'project_budget', [ 'label' => esc_html__( 'Budget', 'tpw' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Budget: $150,000' ]);
        $repeater->add_control( 'project_link_text', [ 'label' => esc_html__( 'Link Text', 'tpw' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Project Details →' ]);
        $repeater->add_control( 'project_link', [ 'label' => esc_html__( 'Link URL', 'tpw' ), 'type' => \Elementor\Controls_Manager::URL, 'default' => [ 'url' => '#' ] ]);

        $this->add_control( 'project_list', [
            'label' => esc_html__( 'Project List', 'tpw' ),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                [ 'project_title' => 'Stability Analysis of Power Systems with High Renewable Penetration', 'project_duration' => '2021-2024', 'project_sponsor' => 'Sponsored by: Bangladesh Research Council', 'project_description' => 'This project investigates the dynamic stability challenges introduced by large-scale renewable energy integration...', 'project_budget' => 'Budget: $150,000', 'project_link_text' => 'Project Details →' ],
                [ 'project_title' => 'Smart Grid Technologies for Bangladesh Power System', 'project_duration' => '2022-2025', 'project_sponsor' => 'Collaboration: BUET & International Energy Agency', 'project_description' => 'Developing and testing smart grid solutions tailored for the Bangladesh power system...', 'project_budget' => 'Budget: $250,000', 'project_link_text' => 'Project Details →' ],
            ],
            'title_field' => '{{{ project_title }}}',
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
            
            <?php if ( $settings['project_list'] ) : ?>
                <div class="space-y-6">
                    <?php foreach ( $settings['project_list'] as $index => $item ) : ?>
                        <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition">
                            <div class="bg-primary text-white px-4 py-3">
                                <h3 class="font-bold text-lg"><?php echo esc_html( $item['project_title'] ); ?></h3>
                                <div class="flex flex-wrap items-center text-sm mt-1">
                                    <span class="bg-accent px-2 py-1 rounded mr-2"><?php echo esc_html( $item['project_duration'] ); ?></span>
                                    <span class="text-accent-200"><?php echo esc_html( $item['project_sponsor'] ); ?></span>
                                </div>
                            </div>
                            <div class="p-4">
                                <p class="text-gray-700 mb-3"><?php echo wp_kses_post( $item['project_description'] ); ?></p>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="bg-gray-100 px-2 py-1 rounded text-gray-700"><?php echo esc_html( $item['project_budget'] ); ?></span>
                                    <?php
                                    if ( ! empty( $item['project_link']['url'] ) ) {
                                        $link_key = 'link_' . $index;
                                        $this->add_link_attributes( $link_key, $item['project_link'] );
                                        ?>
                                        <a <?php echo $this->get_render_attribute_string( $link_key ); ?> class="text-accent hover:underline">
                                            <?php echo esc_html( $item['project_link_text'] ); ?>
                                        </a>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}