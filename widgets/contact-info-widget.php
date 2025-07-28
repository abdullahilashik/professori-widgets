<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Contact_Info_Widget extends \Elementor\Widget_Base {

    public function get_name() { return 'contact_info'; }
    public function get_title() { return esc_html__( 'Homepage: Contact Info', 'tpw' ); }
    public function get_icon() { return 'eicon-map-pin'; }
    public function get_categories() { return [ 'basic' ]; }

    protected function _register_controls() {
        $this->start_controls_section('content_section', ['label' => 'Content']);
        $this->add_control('title', ['label' => 'Main Title', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Contact Information']);
        $this->add_control('contact_title', ['label' => 'Contact List Title', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Get In Touch']);
        $this->add_control('phone', ['label' => 'Phone', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => '+880 XXXX XXXXXX']);
        $this->add_control('email', ['label' => 'Email', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'hafizimtiaz@eee.buet.ac.bd']);
        $this->add_control('address', ['label' => 'Address', 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => "Department of Electrical and Electronic Engineering\nBangladesh University of Engineering and Technology (BUET)\nDhaka-1000, Bangladesh"]);
        $this->add_control('hours_title', ['label' => 'Office Hours Title', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Office Hours']);
        $this->add_control('hours_details', ['label' => 'Office Hours Details', 'type' => \Elementor\Controls_Manager::WYSIWYG, 'default' => "<p><strong>Sunday-Thursday:</strong> 9:00 AM - 5:00 PM</p><p><strong>Friday-Saturday:</strong> Closed</p>"]);
        $this->add_control('connect_title', ['label' => 'Connect Title', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Connect With Me']);
        $repeater = new \Elementor\Repeater();
        $repeater->add_control('social_icon', ['label' => 'Icon', 'type' => \Elementor\Controls_Manager::ICONS, 'default' => ['value' => 'fab fa-linkedin', 'library' => 'fa-brands']]);
        $repeater->add_control('social_link', ['label' => 'Link', 'type' => \Elementor\Controls_Manager::URL, 'placeholder' => 'https://...']);
        $this->add_control('social_links_list', ['label' => 'Social Links', 'type' => \Elementor\Controls_Manager::REPEATER, 'fields' => $repeater->get_controls(), 'title_field' => '{{{ social_link.url }}}']);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
            <h2 class="text-2xl font-bold text-primary mb-6 pb-2 border-b border-gray-200"><?php echo esc_html($settings['title']); ?></h2>
            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h3 class="font-semibold text-lg mb-4"><?php echo esc_html($settings['contact_title']); ?></h3>
                    <ul class="space-y-3">
                        <li class="flex items-start"><svg class="h-5 w-5 text-accent mr-3 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">...</svg><span><?php echo esc_html($settings['phone']); ?></span></li>
                        <li class="flex items-start"><svg class="h-5 w-5 text-accent mr-3 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">...</svg><span><?php echo esc_html($settings['email']); ?></span></li>
                        <li class="flex items-start"><svg class="h-5 w-5 text-accent mr-3 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">...</svg><span><?php echo nl2br(esc_html($settings['address'])); ?></span></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold text-lg mb-4"><?php echo esc_html($settings['hours_title']); ?></h3>
                    <div class="bg-gray-50 p-4 rounded-lg prose prose-sm max-w-none"><?php echo wp_kses_post($settings['hours_details']); ?></div>
                    <h3 class="font-semibold text-lg mt-6 mb-4"><?php echo esc_html($settings['connect_title']); ?></h3>
                    <div class="flex space-x-4">
                    <?php if ($settings['social_links_list']): foreach ($settings['social_links_list'] as $item): ?>
                        <a href="<?php echo esc_url($item['social_link']['url']); ?>" class="text-gray-600 hover:text-accent transition"><?php \Elementor\Icons_Manager::render_icon($item['social_icon'], ['class' => 'h-6 w-6']); ?></a>
                    <?php endforeach; endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}