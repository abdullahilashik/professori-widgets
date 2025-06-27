<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Video_Tutorials_Widget extends \Elementor\Widget_Base {

    public function get_name() { return 'cpt_video_tutorials'; }
    public function get_title() { return esc_html__( 'CPT: Video Tutorials', 'tpw' ); }
    public function get_icon() { return 'eicon-video-camera'; }
    public function get_categories() { return [ 'basic' ]; }

    protected function _register_controls() {
        $this->start_controls_section('content_section', ['label' => 'Settings']);
        $this->add_control('title', ['label' => 'Title', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Video Tutorials']);
        $this->add_control('posts_per_page', ['label' => 'Number of Videos to Show', 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 3]);
        $this->add_control('view_all_text', ['label' => '"View All" Button Text', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'View All Video Tutorials']);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $query_args = [
            'post_type' => 'video_tutorial',
            'posts_per_page' => $settings['posts_per_page'],
            'orderby' => 'date',
            'order' => 'DESC'
        ];
        $videos_query = new \WP_Query($query_args);
        $total_posts = $videos_query->found_posts;
        $posts_shown = $videos_query->post_count;
        ?>
        <section class="mb-16">
            <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
                <h2 class="text-2xl font-bold text-primary mb-6"><?php echo esc_html($settings['title']); ?></h2>
                
                <?php if ($videos_query->have_posts()) : ?>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php while ($videos_query->have_posts()) : $videos_query->the_post();
                        $post_id = get_the_ID();
                        $video_url = get_post_meta($post_id, '_video_url', true);
                        $duration = get_post_meta($post_id, '_video_duration', true);
                        $topics = get_the_terms($post_id, 'tutorial_topic');
                        
                        // ===============================================
                        // THE FIX IS HERE: Check if $topics is a WP_Error
                        // ===============================================
                        if ( is_wp_error($topics) ) {
                            $topics = false; // Set it to false so later checks will fail gracefully
                        }

                    ?>
                        <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition">
                            <a href="<?php echo esc_url($video_url); ?>" target="_blank" class="block bg-gray-900 aspect-w-16 aspect-h-9 relative">
                                <?php if (has_post_thumbnail()): ?>
                                    <?php the_post_thumbnail('medium_large', ['class' => 'w-full h-full object-cover opacity-75']); ?>
                                <?php else: ?>
                                    <img src="https://via.placeholder.com/800x450?text=Video" alt="Video Placeholder" class="w-full h-full object-cover opacity-75">
                                <?php endif; ?>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <svg class="h-16 w-16 text-white opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </div>
                            </a>
                            <div class="p-4">
                                <h3 class="font-bold text-primary mb-2"><?php the_title(); ?></h3>
                                <div class="flex flex-wrap gap-2 mb-3">
                                    <?php if (!empty($topics)): ?><span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded"><?php echo esc_html($topics[0]->name); ?></span><?php endif; ?>
                                    <?php if ($duration): ?><span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded"><?php echo esc_html($duration); ?></span><?php endif; ?>
                                </div>
                                <div class="text-gray-700 text-sm mb-4"><?php the_excerpt(); ?></div>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-500">Posted: <?php echo get_the_date(); ?></span>
                                    <a href="<?php echo esc_url($video_url); ?>" target="_blank" class="text-accent text-sm font-medium hover:underline">Watch Now »</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                <?php endif; wp_reset_postdata(); ?>
                
                <?php if ($total_posts > $posts_shown): ?>
                <div class="text-center mt-8">
                    <a href="<?php echo get_post_type_archive_link('video_tutorial'); ?>" class="px-4 py-2 bg-gray-100 text-primary rounded-lg hover:bg-gray-200 transition">
                        <?php echo esc_html($settings['view_all_text']); ?> (<?php echo esc_html($total_posts - $posts_shown); ?> more)
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </section>
        <?php
    }
}