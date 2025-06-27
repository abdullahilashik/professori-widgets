<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Awards_Honors_Widget extends \Elementor\Widget_Base {

    public function get_name() { return 'awards_honors'; }
    public function get_title() { return esc_html__( 'CPT: Awards & Honors', 'tpw' ); }
    public function get_icon() { return 'eicon-star'; }
    public function get_categories() { return [ 'basic' ]; }

    protected function _register_controls() {
        $this->start_controls_section('content_section', ['label' => 'Settings']);
        $this->add_control('title', ['label' => 'Title', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Awards & Honors']);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $query_args = ['post_type' => 'award', 'posts_per_page' => -1, 'orderby' => 'menu_order', 'order' => 'ASC'];
        $award_query = new \WP_Query($query_args);
        ?>
        <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
            <h2 class="text-2xl font-bold text-primary mb-6 pb-2 border-b border-gray-200"><?php echo esc_html($settings['title']); ?></h2>
            <div class="grid md:grid-cols-2 gap-6">
                <?php if ($award_query->have_posts()) : while ($award_query->have_posts()) : $award_query->the_post();
                    $post_id = get_the_ID();
                    $title   = get_the_title($post_id);
                    $body    = get_post_meta($post_id, '_award_body', true);
                    $year    = get_post_meta($post_id, '_award_year', true);
                    $content = apply_filters('the_content', get_the_content(null, false, $post_id));
                ?>
                    <div class="border-l-4 border-accent pl-4 py-2 hover:bg-gray-50 transition">
                        
                       <h3 class="text-lg font-bold text-primary">
        <a href="<?php echo esc_url(get_permalink($post_id)); ?>" class="hover:underline">
            <?php echo esc_html($title); ?>
        </a>
    </h3>
                        <p class="text-gray-600"><?php echo esc_html($body); ?></p>
                        <p class="text-sm text-gray-500 mb-2"><?php echo esc_html($year); ?></p>
                        <div class="text-gray-700"><?php echo $content; ?></div>
                    </div>
                <?php endwhile; wp_reset_postdata(); else: ?>
                    <p>No awards have been added yet.</p>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}