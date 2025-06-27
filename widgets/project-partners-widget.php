<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Project_Partners_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'project_partners_grid';
    }

    public function get_title() {
        return esc_html__( 'Project Partners Grid', 'tpw' );
    }

    public function get_icon() {
        return 'eicon-logo';
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
            'default' => esc_html__( 'Project Partners', 'tpw' ),
        ]);
        $this->end_controls_section();

        // --- Partners Repeater ---
        $this->start_controls_section( 'section_partners', [ 'label' => esc_html__( 'Partners', 'tpw' ) ] );
        
        $repeater = new \Elementor\Repeater();
        $repeater->add_control( 'partner_name', [
            'label' => esc_html__( 'Partner Name', 'tpw' ),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Partner Name',
            'label_block' => true,
        ]);
        $repeater->add_control( 'partner_logo', [
            'label' => esc_html__( 'Logo', 'tpw' ),
            'type' => \Elementor\Controls_Manager::MEDIA,
            'default' => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ],
        ]);
        $repeater->add_control( 'partner_link', [
            'label' => esc_html__( 'Link (Optional)', 'tpw' ),
            'type' => \Elementor\Controls_Manager::URL,
            'placeholder' => esc_html__( 'https://partner-website.com', 'tpw' ),
        ]);

        $this->add_control( 'partner_list', [
            'label' => esc_html__( 'Partner List', 'tpw' ),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                [ 'partner_name' => 'BUET' ],
                [ 'partner_name' => 'UGC' ],
                [ 'partner_name' => 'BRC' ],
                [ 'partner_name' => 'BPDB' ],
                [ 'partner_name' => 'DPDC' ],
                [ 'partner_name' => 'IEA' ],
            ],
            'title_field' => '{{{ partner_name }}}',
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
            
            <?php if ( $settings['partner_list'] ) : ?>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    <?php foreach ( $settings['partner_list'] as $index => $item ) : 
                        $tag = 'div';
                        $link_key = 'link_' . $index;

                        if ( ! empty( $item['partner_link']['url'] ) ) {
                            $tag = 'a';
                            $this->add_link_attributes( $link_key, $item['partner_link'] );
                        }
                    ?>
                        <<?php echo $tag; ?> <?php echo $this->get_render_attribute_string( $link_key ); ?> class="flex items-center justify-center p-4 border border-gray-200 rounded-lg hover:shadow-md transition">
                            <?php if ( ! empty( $item['partner_logo']['url'] ) ) : ?>
                                <img src="<?php echo esc_url( $item['partner_logo']['url'] ); ?>" alt="<?php echo esc_attr( $item['partner_name'] ); ?>" class="h-12 object-contain">
                            <?php endif; ?>
                        </<?php echo $tag; ?>>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}