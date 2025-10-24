<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Full_Contact_Page_Widget extends \Elementor\Widget_Base {

    public function get_name() { return 'full_contact_page'; }
    public function get_title() { return esc_html__( 'Full Contact Page', 'tpw' ); }
    public function get_icon() { return 'eicon-mail'; }
    public function get_categories() { return [ 'basic' ]; }

    protected function _register_controls() {
        // --- Page Header Section ---
        $this->start_controls_section('section_header', ['label' => 'Page Header']);
        $this->add_control('main_title', ['label' => 'Main Title', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Contact Dr. Hafiz Imtiaz']);
        $this->add_control('subtitle', ['label' => 'Subtitle / Description', 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'For research collaborations, student inquiries, or any other questions, please use the contact methods below.']);
        $this->end_controls_section();

        // --- Contact Info Section ---
        $this->start_controls_section('section_info', ['label' => 'Contact Information']);
        $this->add_control('info_title', ['label' => 'Box Title', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Contact Information']);
        $repeater_info = new \Elementor\Repeater();
        $repeater_info->add_control('icon', ['label' => 'Icon', 'type' => \Elementor\Controls_Manager::ICONS, 'default' => ['value' => 'fas fa-map-marker-alt', 'library' => 'fa-solid']]);
        $repeater_info->add_control('item_title', ['label' => 'Title', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Office Location']);
        $repeater_info->add_control('item_details', ['label' => 'Details', 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => "Room 305, EEE Building\nBangladesh University of Engineering and Technology\nDhaka 1000, Bangladesh"]);
        $this->add_control('contact_details_list', ['label' => 'Contact Items', 'type' => \Elementor\Controls_Manager::REPEATER, 'fields' => $repeater_info->get_controls(), 'title_field' => '{{{ item_title }}}', 'default' => [
            ['item_title' => 'Office Location', 'item_details' => "Room 305, EEE Building\nBangladesh University of Engineering and Technology\nDhaka 1000, Bangladesh"],
            ['item_title' => 'Email', 'item_details' => "hafiz@eee.buet.ac.bd\nhafiz.imtiaz@gmail.com"],
        ]]);
        $this->add_control('social_title', ['label' => 'Social Links Title', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Connect Socially']);
        $repeater_social = new \Elementor\Repeater();
        $repeater_social->add_control('social_icon', ['label' => 'Icon', 'type' => \Elementor\Controls_Manager::ICONS, 'default' => ['value' => 'fab fa-linkedin', 'library' => 'fa-brands']]);
        $repeater_social->add_control('social_link', ['label' => 'Link', 'type' => \Elementor\Controls_Manager::URL]);
        $this->add_control('social_links_list', ['label' => 'Social Links', 'type' => \Elementor\Controls_Manager::REPEATER, 'fields' => $repeater_social->get_controls(), 'title_field' => '{{{ social_link.url }}}']);
        $this->end_controls_section();

        // --- Form Section ---
        $this->start_controls_section('section_form', ['label' => 'Contact Form']);
        $this->add_control('form_title', ['label' => 'Form Title', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Send a Message']);
        $this->add_control('subject_options', ['label' => 'Subject Dropdown Options', 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => "Research Collaboration\nAcademic Inquiry\nPhD Supervision\nOther", 'description' => 'Enter one option per line.']);
        $this->end_controls_section();

        // --- Map Section ---
        $this->start_controls_section('section_map', ['label' => 'Map']);
        $this->add_control('map_title', ['label' => 'Map Box Title', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Office Location Map']);
        $this->add_control('map_embed_url', ['label' => 'Google Maps Embed URL', 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3652.215472477644!2d90.3922673154312!3d23.72663938459593!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755b8f7e8eb9d6f%3A0xec5b3a4b5e5d6b5d!2sBUET%2C%20Dhaka%201000!5e0!3m2!1sen!2sbd!4v1620000000000!5m2!1sen!2sbd']);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <main class="container mx-auto px-4 py-12">
            <div class="text-center mb-12">
                <h1 class="text-3xl font-bold text-primary mb-4"><?php echo esc_html($settings['main_title']); ?></h1>
                <p class="text-gray-600 max-w-2xl mx-auto"><?php echo nl2br(esc_html($settings['subtitle'])); ?></p>
            </div>
            <div class="flex flex-col lg:flex-row gap-8">
                <div class="lg:w-1/3">
                    <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
                        <h2 class="text-2xl font-bold text-primary mb-6 pb-2 border-b border-gray-200"><?php echo esc_html($settings['info_title']); ?></h2>
                        <div class="space-y-6">
                            <?php if ($settings['contact_details_list']): foreach ($settings['contact_details_list'] as $item): ?>
                            <div class="flex items-start">
                                <div class="text-accent mr-4 mt-1"><?php \Elementor\Icons_Manager::render_icon($item['icon'], ['class' => 'h-6 w-6']); ?></div>
                                <div>
                                    <h3 class="font-semibold text-gray-800 mb-1"><?php echo esc_html($item['item_title']); ?></h3>
                                    <div class="text-gray-600 prose-sm"><?php echo nl2br(esc_html($item['item_details'])); ?></div>
                                </div>
                            </div>
                            <?php endforeach; endif; ?>
                        </div>
                        <?php if (!empty($settings['social_links_list'])): ?>
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <h3 class="font-semibold text-gray-800 mb-4"><?php echo esc_html($settings['social_title']); ?></h3>
                            <div class="flex space-x-4">
                            <?php foreach ($settings['social_links_list'] as $item): ?>
                                <a href="<?php echo esc_url($item['social_link']['url']); ?>" target="<?php echo $item['social_link']['is_external'] ? '_blank' : '_self'; ?>" rel="<?php echo $item['social_link']['nofollow'] ? 'nofollow' : ''; ?>" class="text-gray-600 hover:text-accent transition"><?php \Elementor\Icons_Manager::render_icon($item['social_icon'], ['class' => 'h-6 w-6']); ?></a>
                            <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="lg:w-2/3">
                    <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
                        <h2 class="text-2xl font-bold text-primary mb-6 pb-2 border-b border-gray-200"><?php echo esc_html($settings['form_title']); ?></h2>
                        <?php
                        global $form_errors, $form_success, $form_data;
                        if (!empty($form_errors)) {
                            echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert"><strong class="font-bold">Please correct the errors below:</strong><ul class="list-disc list-inside mt-2">';
                            foreach ($form_errors as $error) { echo '<li>' . esc_html($error) . '</li>'; }
                            echo '</ul></div>';
                        }
                        if (isset($form_success) && $form_success === true) {
                            echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert"><p class="font-bold">Thank you for your message! It has been sent.</p></div>';
                        }
                        if (!isset($form_success) || $form_success !== true) :
                        ?>
                        <form method="post" action="" class="space-y-6">
                            <?php wp_nonce_field('mytheme_contact_form', 'contact_form_nonce'); ?>
                            <input type="hidden" name="contact_form_submitted" value="1">
                            <input type="text" name="honeypot" style="display:none !important;" tabindex="-1" autocomplete="off">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                    <input type="text" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-accent" value="<?php echo esc_attr($form_data['name'] ?? ''); ?>">
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                    <input type="email" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-accent" value="<?php echo esc_attr($form_data['email'] ?? ''); ?>">
                                </div>
                            </div>
                            <div>
                                <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                                <select name="subject" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-accent">
                                    <option value="">Select a subject</option>
                                    <?php
                                    $subjects = explode("\n", trim($settings['subject_options']));
                                    $selected_subject = $form_data['subject'] ?? '';
                                    foreach ($subjects as $s) {
                                        $s = trim($s); if(empty($s)) continue; $slug = strtolower(str_replace(' ', '-', $s));
                                        echo '<option value="' . esc_attr($slug) . '" ' . selected($selected_subject, $slug, false) . '>' . esc_html($s) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div>
                                <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                                <textarea name="message" rows="5" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-accent"><?php echo esc_textarea($form_data['message'] ?? ''); ?></textarea>
                            </div>
                            <div><button type="submit" class="w-full bg-accent text-white py-3 px-6 rounded-lg hover:bg-blue-600 transition flex items-center justify-center">Send Message</button></div>
                        </form>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($settings['map_embed_url'])): ?>
                    <div class="bg-white rounded-lg shadow-md p-4 mt-8">
                        <h3 class="font-semibold text-gray-800 mb-4"><?php echo esc_html($settings['map_title']); ?></h3>
                        <div class="aspect-w-16 aspect-h-9 bg-gray-200 rounded-lg overflow-hidden">
                            <iframe src="<?php echo esc_url($settings['map_embed_url']); ?>" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
        <?php
    }
}