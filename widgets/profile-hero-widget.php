<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Profile_Hero_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'profile_hero_banner';
    }

    public function get_title() {
        return esc_html__( 'Profile Hero Banner', 'tpw' );
    }

    public function get_icon() {
        return 'eicon-person';
    }

    public function get_categories() {
        return [ 'basic' ];
    }

    protected function _register_controls() {
        // --- Content Section ---
        $this->start_controls_section( 'section_content', [ 'label' => esc_html__( 'Content', 'tpw' ) ] );
        $this->add_control( 'main_title', [
            'label' => esc_html__( 'Title', 'tpw' ),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => esc_html__( 'Academic Journey & Professional Career', 'tpw' ),
        ]);
        $this->add_control( 'subtitle', [
            'label' => esc_html__( 'Subtitle', 'tpw' ),
            'type' => \Elementor\Controls_Manager::TEXTAREA,
            'default' => esc_html__( 'From student to professor, my path in electrical engineering has been driven by a passion for power systems and renewable energy.', 'tpw' ),
        ]);
        $this->add_control( 'profile_image', [
            'label' => esc_html__( 'Profile Image', 'tpw' ),
            'type' => \Elementor\Controls_Manager::MEDIA,
            'default' => [ 'url' => 'https://hafizimtiaz.buet.ac.bd/static/images/photo.jpg' ],
        ]);
        $this->end_controls_section();

        // --- Statistics Repeater ---
        $this->start_controls_section( 'section_stats', [ 'label' => esc_html__( 'Statistics', 'tpw' ) ] );
        $repeater = new \Elementor\Repeater();
        $repeater->add_control( 'stat_value', [ 'label' => esc_html__( 'Value', 'tpw' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => '25+' ]);
        $repeater->add_control( 'stat_label', [ 'label' => esc_html__( 'Label', 'tpw' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Years of Experience' ]);
        
        $this->add_control( 'stats_list', [
            'label' => esc_html__( 'Statistics List', 'tpw' ),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                [ 'stat_value' => '25+', 'stat_label' => 'Years of Experience' ],
                [ 'stat_value' => '100+', 'stat_label' => 'Publications' ],
                [ 'stat_value' => '20+', 'stat_label' => 'Research Projects' ],
            ],
            'title_field' => '{{{ stat_value }}} {{{ stat_label }}}',
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="bg-gradient-to-r from-primary to-secondary text-white rounded-lg shadow-xl overflow-hidden">
            <div class="md:flex">
                <div class="md:w-2/3 p-8 md:p-12">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4">
                        <?php echo esc_html( $settings['main_title'] ); ?>
                    </h2>
                    <p class="text-xl mb-6">
                        <?php echo esc_html( $settings['subtitle'] ); ?>
                    </p>
                    
                    <?php if ( $settings['stats_list'] ) : ?>
                        <div class="flex flex-wrap gap-4">
                            <?php foreach ( $settings['stats_list'] as $item ) : ?>
                                <div class="bg-white bg-opacity-20 px-4 py-2 rounded-full">
                                    <span class="font-medium"><?php echo esc_html( $item['stat_value'] ); ?></span> <?php echo esc_html( $item['stat_label'] ); ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="md:w-1/3 flex items-center justify-center p-6">
                    <?php if ( ! empty( $settings['profile_image']['url'] ) ) : ?>
                        <img src="<?php echo esc_url( $settings['profile_image']['url'] ); ?>" 
                             alt="<?php echo esc_attr( $settings['main_title'] ); ?>" 
                             class="w-48 h-48 md:w-64 md:h-64 rounded-full border-4 border-white shadow-lg">
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }
}