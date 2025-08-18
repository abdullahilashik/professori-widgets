<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Research_Interests_Grid_Widget extends \Elementor\Widget_Base {

    public function get_name() { return 'research_interests_grid'; }
    public function get_title() { return esc_html__( 'Homepage: Research Interests', 'tpw' ); }
    public function get_icon() { return 'eicon-info-circle-o'; }
    // public function get_categories() { return [ 'basic' ]; }
    public function get_categories() {
        return [ 'basic' ]; // Use the slug we created in Step 1
    }

    protected function _register_controls() {
        $this->start_controls_section('content_section', ['label' => 'Content']);
        $this->add_control('title', ['label' => 'Title', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Research Interests']);
        $repeater = new \Elementor\Repeater();
        $repeater->add_control('icon', ['label' => 'Icon', 'type' => \Elementor\Controls_Manager::ICONS, 'default' => ['value' => 'fas fa-bolt', 'library' => 'fa-solid']]);
        $repeater->add_control('interest_title', ['label' => 'Title', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Interest Title']);
        $repeater->add_control('description', ['label' => 'Description', 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'A brief description...']);
        $this->add_control('interests_list', ['label' => 'Interests', 'type' => \Elementor\Controls_Manager::REPEATER, 'fields' => $repeater->get_controls(), 'title_field' => '{{{ interest_title }}}']);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
            <h2 class="text-2xl font-bold text-primary mb-6 pb-2 border-b border-gray-200"><?php echo esc_html($settings['title']); ?></h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php if ($settings['interests_list']): foreach ($settings['interests_list'] as $item): ?>
                <div class="bg-gray-50 p-6 rounded-lg hover:shadow-md transition">
                    <div class="text-accent mb-3"><?php \Elementor\Icons_Manager::render_icon($item['icon'], ['class' => 'h-8 w-8', 'aria-hidden' => 'true']); ?></div>
                    <h3 class="font-semibold text-lg mb-2"><?php echo esc_html($item['interest_title']); ?></h3>
                    <p class="text-gray-600 text-sm"><?php echo esc_html($item['description']); ?></p>
                </div>
                <?php endforeach; endif; ?>
            </div>
        </div>
        <?php
    }
}