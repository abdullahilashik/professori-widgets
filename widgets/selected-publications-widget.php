<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Selected_Publications_Widget extends \Elementor\Widget_Base {
    public function get_name() { return 'cpt_selected_publications'; }
    public function get_title() { return esc_html__( 'Homepage: Selected Publications', 'tpw' ); }
    public function get_icon() { return 'eicon-post-list'; }
    public function get_categories() { return [ 'basic' ]; }

    protected function _register_controls() {
        $this->start_controls_section('content_section', ['label' => 'Content']);
        $this->add_control('title', ['label' => 'Title', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Selected Publications']);
        $this->add_control('posts_count', ['label' => 'Number of Publications to Show', 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 3]);
        $this->add_control('view_all_text', ['label' => '"View All" Button Text', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'View All Publications →']);
        $this->add_control('view_all_link', ['label' => 'Link for "View All" Button', 'type' => \Elementor\Controls_Manager::URL, 'placeholder' => '/papers/']);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
            <h2 class="text-2xl font-bold text-primary mb-6 pb-2 border-b border-gray-200"><?php echo esc_html($settings['title']); ?></h2>
            <div class="space-y-4">
                <?php
                $papers_query = new \WP_Query(['post_type' => 'paper', 'posts_per_page' => $settings['posts_count'], 'orderby' => 'date', 'order' => 'DESC']);
                if ($papers_query->have_posts()) : while ($papers_query->have_posts()) : $papers_query->the_post();
                    $authors = get_post_meta(get_the_ID(), '_authors', true);
                    $pub_info = get_post_meta(get_the_ID(), '_publication_info', true);
                ?>
                <div class="border-l-4 border-accent pl-4 py-2 hover:bg-gray-50 transition">
                    <h3 class="font-semibold text-lg"><a href="<?php the_permalink(); ?>" class="hover:underline"><?php the_title(); ?></a></h3>
                    <p class="text-sm text-gray-600 mb-1"><?php echo esc_html($authors); ?>, <?php echo esc_html($pub_info); ?></p>
                    <div class="text-gray-700 text-sm"><?php the_excerpt(); ?></div>
                    <a href="<?php the_permalink(); ?>" class="text-accent text-sm inline-block mt-2 hover:underline">Read More →</a>
                </div>
                <?php endwhile; wp_reset_postdata(); endif; ?>
            </div>
            <?php if (!empty($settings['view_all_link']['url'])): ?>
            <div class="mt-6 text-center">
                <a href="<?php echo esc_url($settings['view_all_link']['url']); ?>" class="text-accent font-medium hover:underline"><?php echo esc_html($settings['view_all_text']); ?></a>
            </div>
            <?php endif; ?>
        </div>
        <?php
    }
}