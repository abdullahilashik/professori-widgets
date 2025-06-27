<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Education_Timeline_Widget extends \Elementor\Widget_Base {

    public function get_name() { return 'education_timeline'; }
    public function get_title() { return esc_html__( 'CPT: Education Timeline', 'tpw' ); }
    public function get_icon() { return 'eicon-time-line'; }
    public function get_categories() { return [ 'basic' ]; }

    protected function _register_controls() {
        $this->start_controls_section('content_section', ['label' => 'Settings']);
        $this->add_control('title', ['label' => 'Title', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Education']);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $query_args = ['post_type' => 'education', 'posts_per_page' => -1, 'orderby' => 'menu_order', 'order' => 'ASC'];
        $edu_query = new \WP_Query($query_args);
        ?>
        <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
            <h2 class="text-2xl font-bold text-primary mb-6 pb-2 border-b border-gray-200"><?php echo esc_html($settings['title']); ?></h2>
            <div class="relative">
                <div class="hidden md:block absolute left-1/2 h-full w-0.5 bg-gray-200 transform -translate-x-1/2"></div>
                <?php if ($edu_query->have_posts()) : $item_index = 0; while ($edu_query->have_posts()) : $edu_query->the_post();
                    $is_even = ($item_index % 2 === 1); 
                    $post_id = get_the_ID();

                    $title       = get_the_title($post_id);
                    $permalink   = get_permalink($post_id);
                    $institution = get_post_meta($post_id, '_education_institution', true);
                    $dates       = get_post_meta($post_id, '_education_date', true);
                    $content     = apply_filters('the_content', get_the_content(null, false, $post_id));
                ?>
                <div class="flex flex-col md:flex-row mb-8">
                    <div class="w-full md:w-1/2 mb-4 md:mb-0 <?php echo $is_even ? 'md:order-3 md:pl-8' : 'md:pr-8 md:text-right'; ?>">
                        <h3 class="text-lg font-bold text-primary">
                            <a href="<?php echo esc_url($permalink); ?>" class="hover:underline"><?php echo esc_html($title); ?></a>
                        </h3>
                        <p class="text-gray-600"><?php echo esc_html($institution); ?></p>
                        <p class="text-sm text-gray-500"><?php echo esc_html($dates); ?></p>
                    </div>
                    <div class="hidden md:flex md:w-auto <?php echo $is_even ? 'md:order-2' : ''; ?> justify-center relative"><div class="w-6 h-6 rounded-full bg-accent border-4 border-white"></div></div>
                    <div class="w-full md:w-1/2 <?php echo $is_even ? 'md:order-1 md:pr-8' : 'md:pl-8'; ?>">
                        <!-- <div class="bg-gray-50 p-4 rounded-lg"><div class="text-gray-700 prose-sm"><?php echo $content; ?></div></div> -->
                    </div>
                </div>
                <?php $item_index++; endwhile; wp_reset_postdata(); else: ?>
                    <p>No education entries found.</p>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}