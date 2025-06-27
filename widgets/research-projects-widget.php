<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Research_Projects_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'research_projects_table';
    }

    public function get_title() {
        return esc_html__( 'Completed Projects Table', 'tpw' );
    }

    public function get_icon() {
        return 'eicon-table';
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
            'default' => esc_html__( 'Completed Research Projects', 'tpw' ),
        ]);
        $this->end_controls_section();

        // --- Projects Repeater ---
        $this->start_controls_section( 'section_projects', [ 'label' => esc_html__( 'Projects', 'tpw' ) ] );
        
        $repeater = new \Elementor\Repeater();
        $repeater->add_control( 'project_title', [
            'label' => esc_html__( 'Project Title', 'tpw' ),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Project Title',
            'label_block' => true,
        ]);
        $repeater->add_control( 'project_duration', [ 'label' => esc_html__( 'Duration', 'tpw' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => '2018-2021' ]);
        $repeater->add_control( 'project_sponsor', [ 'label' => esc_html__( 'Sponsor', 'tpw' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Sponsor Name' ]);
        $repeater->add_control( 'project_budget', [ 'label' => esc_html__( 'Budget', 'tpw' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => '$100,000' ]);
        $repeater->add_control( 'project_link', [
            'label' => esc_html__( 'Details Link', 'tpw' ),
            'type' => \Elementor\Controls_Manager::URL,
            'placeholder' => esc_html__( 'https://your-link.com', 'tpw' ),
            'default' => [ 'url' => '#' ],
        ]);

        $this->add_control( 'project_list', [
            'label' => esc_html__( 'Project List', 'tpw' ),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                [ 'project_title' => 'Dynamic Stability Enhancement Techniques', 'project_duration' => '2018-2021', 'project_sponsor' => 'UGC Bangladesh', 'project_budget' => '$120,000' ],
                [ 'project_title' => 'Renewable Energy Integration Study', 'project_duration' => '2017-2020', 'project_sponsor' => 'World Bank', 'project_budget' => '$180,000' ],
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
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project Title</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sponsor</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Budget</th>
                            <th scope="col" class="relative px-6 py-3"><span class="sr-only">Details</span></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if ( $settings['project_list'] ) : ?>
                            <?php foreach ( $settings['project_list'] as $index => $item ) : 
                                $link_key = 'link_' . $index;
                                $this->add_link_attributes( $link_key, $item['project_link'] );
                            ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo esc_html( $item['project_title'] ); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo esc_html( $item['project_duration'] ); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo esc_html( $item['project_sponsor'] ); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo esc_html( $item['project_budget'] ); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a <?php echo $this->get_render_attribute_string( $link_key ); ?> class="text-accent hover:underline">Details</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }
}