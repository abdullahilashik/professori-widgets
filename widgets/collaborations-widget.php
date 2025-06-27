<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Collaborations_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'collaborations_grid';
    }

    public function get_title() {
        return esc_html__( 'Collaborations Grid', 'tpw' );
    }

    public function get_icon() {
        return 'eicon-gallery-grid';
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
            'default' => esc_html__( 'Collaborations', 'tpw' ),
        ]);
        $this->end_controls_section();

        // --- Collaborators Repeater ---
        $this->start_controls_section( 'section_collaborators', [ 'label' => esc_html__( 'Collaborators', 'tpw' ) ] );
        
        $repeater = new \Elementor\Repeater();
        $repeater->add_control( 'logo_image', [
            'label' => esc_html__( 'Logo', 'tpw' ),
            'type' => \Elementor\Controls_Manager::MEDIA,
            'default' => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ],
        ]);
        $repeater->add_control( 'logo_name', [
            'label' => esc_html__( 'Name', 'tpw' ),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => esc_html__( 'Collaborator Name' , 'tpw' ),
            'label_block' => true,
        ]);
        $repeater->add_control( 'logo_link', [
            'label' => esc_html__( 'Link (Optional)', 'tpw' ),
            'type' => \Elementor\Controls_Manager::URL,
            'placeholder' => esc_html__( 'https://your-link.com', 'tpw' ),
        ]);

        $this->add_control( 'collaborator_list', [
            'label' => esc_html__( 'Collaborator List', 'tpw' ),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                [ 'logo_name' => 'Bangladesh University of Engineering and Technology' ],
                [ 'logo_name' => 'International Energy Agency' ],
            ],
            'title_field' => '{{{ logo_name }}}',
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
            
            <?php if ( $settings['collaborator_list'] ) : ?>
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <?php foreach ( $settings['collaborator_list'] as $index => $item ) : 
                        $tag = 'div';
                        $link_key = 'link_' . $index;

                        if ( ! empty( $item['logo_link']['url'] ) ) {
                            $tag = 'a';
                            $this->add_link_attributes( $link_key, $item['logo_link'] );
                        }
                    ?>
                        <<?php echo $tag; ?> <?php echo $this->get_render_attribute_string( $link_key ); ?> class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:shadow-md transition">
                            <?php if ( ! empty( $item['logo_image']['url'] ) ) : ?>
                                <img src="<?php echo esc_url( $item['logo_image']['url'] ); ?>" alt="<?php echo esc_attr( $item['logo_name'] ); ?>" class="h-16 mb-3 object-contain">
                            <?php endif; ?>
                            <h3 class="font-medium text-center"><?php echo esc_html( $item['logo_name'] ); ?></h3>
                        </<?php echo $tag; ?>>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}