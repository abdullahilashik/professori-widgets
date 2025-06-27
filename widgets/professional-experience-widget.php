<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Professional_Experience_Widget extends \Elementor\Widget_Base {

    public function get_name() { return 'professional_experience'; }
    public function get_title() { return esc_html__( 'CPT: Professional Experience', 'tpw' ); }
    public function get_icon() { return 'eicon-briefcase'; }
    public function get_categories() { return [ 'basic' ]; }

    protected function _register_controls() {
        $this->start_controls_section('content_section', ['label' => 'Settings']);
        $this->add_control('title', ['label' => 'Title', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Professional Experience']);
        $this->end_controls_section();
    }

    protected function render_old() {
        $settings = $this->get_settings_for_display();
        $query_args = ['post_type' => 'experience', 'posts_per_page' => -1, 'orderby' => 'menu_order', 'order' => 'ASC'];
        $exp_query = new \WP_Query($query_args);
        ?>
        <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
            <h2 class="text-2xl font-bold text-primary mb-6 pb-2 border-b border-gray-200"><?php echo esc_html($settings['title']); ?></h2>
            <div class="space-y-8">
                <?php if ($exp_query->have_posts()) : while ($exp_query->have_posts()) : $exp_query->the_post();
                    $post_id = get_the_ID();
                    $title   = get_the_title($post_id);
                    $company = get_post_meta($post_id, '_experience_company', true);
                    $dates   = get_post_meta($post_id, '_experience_date', true);
                    $content = apply_filters('the_content', get_the_content(null, false, $post_id));
                ?>
                    <div class="flex flex-col md:flex-row">
                        <div class="md:w-1/4 mb-4 md:mb-0">
                            <h3 class="text-lg font-bold text-primary"><?php echo esc_html($title); ?></h3>
                            <p class="text-gray-600"><?php echo esc_html($company); ?></p>
                            <p class="text-sm text-gray-500"><?php echo esc_html($dates); ?></p>
                        </div>
                        <div class="md:w-3/4"><div class="bg-gray-50 p-4 rounded-lg"><div class="prose max-w-none text-gray-700"><?php echo $content; ?></div></div></div>
                    </div>
                <?php endwhile; wp_reset_postdata(); else: ?>
                    <p>No professional experience has been added yet.</p>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    protected function render() {
    $settings = $this->get_settings_for_display();
    $query_args = ['post_type' => 'experience', 'posts_per_page' => -1, 'orderby' => 'menu_order', 'order' => 'ASC'];
    $exp_query = new \WP_Query($query_args);
    ?>
    <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
        <h2 class="text-2xl font-bold text-primary mb-6 pb-2 border-b border-gray-200"><?php echo esc_html($settings['title']); ?></h2>
        <div class="space-y-8">
            <?php if ($exp_query->have_posts()) : while ($exp_query->have_posts()) : $exp_query->the_post();
                $post_id   = get_the_ID();
                $title     = get_the_title($post_id);
                $permalink = get_permalink($post_id); // Get the link for this post
                $company   = get_post_meta($post_id, '_experience_company', true);
                $dates     = get_post_meta($post_id, '_experience_date', true);
                $content   = apply_filters('the_content', get_the_content(null, false, $post_id));
            ?>
                <div class="flex flex-col md:flex-row">
                    <div class="md:w-1/4 mb-4 md:mb-0">
                        <h3 class="text-lg font-bold text-primary">
                            <a href="<?php echo esc_url($permalink); ?>" class="hover:underline">
                                <?php echo esc_html($title); ?>
                            </a>
                        </h3>
                        <p class="text-gray-600"><?php echo esc_html($company); ?></p>
                        <p class="text-sm text-gray-500"><?php echo esc_html($dates); ?></p>
                    </div>
                    <div class="md:w-3/4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="prose max-w-none text-gray-700">
                                <?php echo $content; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; wp_reset_postdata(); else: ?>
                <p>No professional experience has been added yet.</p>
            <?php endif; ?>
        </div>
    </div>
    <?php
}
}