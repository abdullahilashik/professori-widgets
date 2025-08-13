<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class About_Me_Summary_Widget extends \Elementor\Widget_Base {

    public function get_name() { return 'about_me_summary'; }
    public function get_title() { return esc_html__( 'Homepage: About Me Summary', 'tpw' ); }
    public function get_icon() { return 'eicon-user-circle-o'; }
    // public function get_categories() { return [ 'basic' ]; }
    public function get_categories() {
        return [ 'frontpage-widgets' ]; // Use the slug we created in Step 1
    }

    protected function _register_controls() {
        $this->start_controls_section('content_section', ['label' => 'Content']);
        $this->add_control('title', ['label' => 'Title', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'About Me']);
        $this->add_control('about_text', ['label' => 'About Text', 'type' => \Elementor\Controls_Manager::WYSIWYG, 'default' => '<p>I am a Professor...</p>']);
        $this->add_control('education_title', ['label' => 'Education Box Title', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Education']);
        $this->add_control('education_count', ['label' => 'Number of Education Items to Show', 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 3]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
            <h2 class="text-2xl font-bold text-primary mb-6 pb-2 border-b border-gray-200"><?php echo esc_html($settings['title']); ?></h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="md:col-span-2 prose max-w-none text-gray-700"><?php echo wp_kses_post($settings['about_text']); ?></div>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="font-semibold text-lg mb-4 text-primary"><?php echo esc_html($settings['education_title']); ?></h3>
                    <ul class="space-y-4">
                        <?php
                        $edu_query = new \WP_Query(['post_type' => 'education', 'posts_per_page' => $settings['education_count'], 'orderby' => 'menu_order', 'order' => 'ASC']);
                        if ($edu_query->have_posts()) : while ($edu_query->have_posts()) : $edu_query->the_post();
                            $institution = get_post_meta(get_the_ID(), '_education_institution', true);
                            $dates = get_post_meta(get_the_ID(), '_education_date', true);
                        ?>
                            <li>
                                <h4 class="font-medium"><?php the_title(); ?></h4>
                                <p class="text-sm text-gray-600"><?php echo esc_html($institution); ?> (<?php echo esc_html($dates); ?>)</p>
                            </li>
                        <?php endwhile; wp_reset_postdata(); endif; ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php
    }
}