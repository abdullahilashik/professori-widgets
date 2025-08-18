<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Homepage_Hero_Widget extends \Elementor\Widget_Base {

    public function get_name() { return 'homepage_hero'; }
    public function get_title() { return esc_html__( 'Homepage: Hero Section', 'tpw' ); }
    public function get_icon() { return 'eicon-image-box'; }
    
    // Assign this widget to our new custom category
    public function get_categories() { return [ 'basic' ]; }

    protected function _register_controls() {
        // --- Content Tab ---
        $this->start_controls_section('section_content', ['label' => 'Content']);
        $this->add_control('main_title', ['label' => 'Title', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Advancing Power Systems Research']);
        $this->add_control('subtitle', ['label' => 'Subtitle', 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Specializing in renewable energy integration, smart grids, and power system stability.']);
        $this->add_control('profile_image', ['label' => 'Profile Image', 'type' => \Elementor\Controls_Manager::MEDIA, 'default' => ['url' => 'https://hafizimtiaz.buet.ac.bd/static/images/photo.jpg']]);
        $this->add_control('button_text', ['label' => 'Button Text', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Get In Touch']);
        $this->add_control('button_link', ['label' => 'Button Link', 'type' => \Elementor\Controls_Manager::URL, 'default' => ['url' => '#contact']]);
        $this->end_controls_section();

        // --- Style Tab ---
        $this->start_controls_section('section_style', ['label' => 'Style', 'tab' => \Elementor\Controls_Manager::TAB_STYLE]);
        $this->add_control('gradient_color_1', ['label' => 'Background Gradient Color 1', 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#1a365d']);
        $this->add_control('gradient_color_2', ['label' => 'Background Gradient Color 2', 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#2c5282']);
        $this->add_control('hr1', ['type' => \Elementor\Controls_Manager::DIVIDER]);
        $this->add_control('title_color', ['label' => 'Title Color', 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#FFFFFF', 'selectors' => ['{{WRAPPER}} .hero-title' => 'color: {{VALUE}}']]);
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), ['name' => 'title_typography', 'selector' => '{{WRAPPER}} .hero-title']);
        $this->add_control('hr2', ['type' => \Elementor\Controls_Manager::DIVIDER]);
        $this->add_control('subtitle_color', ['label' => 'Subtitle Color', 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#FFFFFF', 'selectors' => ['{{WRAPPER}} .hero-subtitle' => 'color: {{VALUE}}']]);
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), ['name' => 'subtitle_typography', 'selector' => '{{WRAPPER}} .hero-subtitle']);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $this->add_render_attribute('wrapper', 'style', 'background-image: linear-gradient(to right, ' . esc_attr($settings['gradient_color_1']) . ', ' . esc_attr($settings['gradient_color_2']) . ');');
        ?>
        <section <?php echo $this->get_render_attribute_string('wrapper'); ?> class="text-white py-16">
            <div class="container mx-auto px-4">
                <div class="flex flex-col md:flex-row items-center">
                    <div class="md:w-2/3 mb-8 md:mb-0">
                        <h2 class="text-3xl md:text-4xl font-bold mb-4 hero-title"><?php echo esc_html($settings['main_title']); ?></h2>
                        <p class="text-xl mb-6 hero-subtitle"><?php echo esc_html($settings['subtitle']); ?></p>
                        <?php
                        if (!empty($settings['button_link']['url'])) {
                            $this->add_link_attributes('button_link', $settings['button_link']);
                        }
                        ?>
                        <a <?php echo $this->get_render_attribute_string('button_link'); ?> class="bg-accent hover:bg-blue-600 text-white font-medium py-2 px-6 rounded-full transition inline-block">
                            <?php echo esc_html($settings['button_text']); ?>
                        </a>
                    </div>
                    <div class="md:w-1/3 flex justify-center">
                        <?php if (!empty($settings['profile_image']['url'])): ?>
                        <img src="<?php echo esc_url($settings['profile_image']['url']); ?>" 
                             alt="<?php echo esc_attr($settings['main_title']); ?>" 
                             class="w-48 h-48 md:w-64 md:h-64 rounded-full border-4 border-white shadow-xl">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
        <?php
    }
}