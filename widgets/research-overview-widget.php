<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Research_Overview_Widget extends \Elementor\Widget_Base {

    public function get_name() { return 'research_overview'; }
    public function get_title() { return esc_html__( 'Research Overview', 'tpw' ); }
    public function get_icon() { return 'eicon-document-in-page'; }
    public function get_categories() { return [ 'basic' ]; }

    protected function _register_controls() {
        $this->start_controls_section('content_section', ['label' => 'Content']);
        $this->add_control('title', ['label' => 'Title', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Research Interest']);
        $this->add_control('content', ['label' => 'Content', 'type' => \Elementor\Controls_Manager::WYSIWYG, 'default' => '<p>My primary research interest is...</p>']);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
            <h2 class="text-2xl font-bold text-primary mb-6 pb-2 border-b border-gray-200"><?php echo esc_html($settings['title']); ?></h2>
            <div class="prose max-w-none text-gray-700">
                <?php echo wp_kses_post($settings['content']); ?>
            </div>
        </div>
        <?php
    }
}