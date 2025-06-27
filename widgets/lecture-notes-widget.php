<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Lecture_Notes_Widget extends \Elementor\Widget_Base {

    public function get_name() { return 'cpt_lecture_notes'; }
    public function get_title() { return esc_html__( 'CPT: Lecture Notes Section', 'tpw' ); }
    public function get_icon() { return 'eicon-document-in-page'; }
    public function get_categories() { return [ 'basic' ]; }

    protected function _register_controls() {
        $this->start_controls_section('content_section', ['label' => 'Settings']);
        $this->add_control('title', ['label' => 'Title', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Lecture Notes']);
        $this->add_control('posts_per_page', ['label' => 'Initial Notes to Show', 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 2]);
        $this->end_controls_section();
    }
    
    // Helper function to render file links
    public function render_file_links($post_id, $classes = 'text-accent hover:underline flex items-center text-sm') {
        $file_links_raw = get_post_meta($post_id, '_file_links', true);
        if (empty($file_links_raw)) return;
        $file_links = explode("\n", trim($file_links_raw));
        
        echo '<div class="flex flex-wrap gap-3">';
        foreach ($file_links as $link) {
            if (strpos($link, '|') !== false) {
                list($label, $url) = explode('|', $link, 2);
                 echo '<a href="'.esc_url(trim($url)).'" class="'.esc_attr($classes).'" target="_blank">'.esc_html(trim($label)).'</a>';
            }
        }
        echo '</div>';
    }
    
    // Helper function to render a single lecture note card
    private function render_lecture_note_card($post_id) {
        $is_new = get_post_meta($post_id, '_is_new', true);
        $updated_year = get_post_meta($post_id, '_updated_year', true);
        $topics = get_the_terms($post_id, 'lecture_topic');
        ?>
        <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition">
            <div class="md:flex">
                <div class="md:w-1/4 bg-gray-100 p-4 flex items-center justify-center">
                    <?php if (has_post_thumbnail($post_id)): ?>
                        <?php echo get_the_post_thumbnail($post_id, 'medium', ['class' => 'h-full w-full object-contain']); ?>
                    <?php else: ?>
                        <svg class="h-20 w-20 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">...</svg>
                    <?php endif; ?>
                </div>
                <div class="md:w-3/4 p-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-bold text-primary mb-1"><a href="<?php echo get_permalink($post_id); ?>" class="hover:underline"><?php echo get_the_title($post_id); ?></a></h3>
                            <div class="flex flex-wrap gap-2 mb-2">
                                <?php if (!empty($topics)): ?><span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded"><?php echo esc_html($topics[0]->name); ?></span><?php endif; ?>
                                <?php if ($updated_year): ?><span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded">Updated: <?php echo esc_html($updated_year); ?></span><?php endif; ?>
                            </div>
                        </div>
                        <?php if ($is_new === 'yes'): ?><span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">New</span><?php endif; ?>
                    </div>
                    <div class="text-gray-700 text-sm mb-4"><?php echo get_the_excerpt($post_id); ?></div>
                    <?php $this->render_file_links($post_id); ?>
                </div>
            </div>
        </div>
        <?php
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // --- Featured Note Section ---
        $featured_query = new \WP_Query(['post_type' => 'lecture_note', 'posts_per_page' => 1, 'meta_key' => '_is_featured', 'meta_value' => 'yes']);
        if ($featured_query->have_posts()) : $featured_query->the_post();
            $video_url = get_post_meta(get_the_ID(), '_video_url', true); // Assumes featured note might have a video link
        ?>
        <section class="mb-16">
            <div class="bg-gradient-to-r from-primary to-secondary text-white rounded-lg shadow-xl overflow-hidden">
                <div class="md:flex">
                    <div class="md:w-2/3 p-8 md:p-12">
                        <h2 class="text-2xl md:text-3xl font-bold mb-4"><a href="<?php the_permalink(); ?>" class="text-white hover:underline"><?php the_title(); ?></a></h2>
                        <p class="text-xl mb-6"><?php the_excerpt(); ?></p>
                        <div class="flex flex-wrap gap-4">
                            <?php $this->render_file_links(get_the_ID(), 'bg-white text-primary font-medium py-2 px-6 rounded-full hover:bg-gray-100 transition inline-flex items-center'); ?>
                            <?php if ($video_url): ?><a href="<?php echo esc_url($video_url); ?>" target="_blank" class="border-2 border-white text-white font-medium py-2 px-6 rounded-full hover:bg-white hover:text-primary transition inline-flex items-center">Watch Videos</a><?php endif; ?>
                        </div>
                    </div>
                    <div class="md:w-1/3 flex items-center justify-center p-6">
                        <?php if(has_post_thumbnail()) { the_post_thumbnail('large', ['class' => 'rounded-lg shadow-lg']); } else { echo '<img src="https://via.placeholder.com/400x300?text=Tutorial+Preview" class="rounded-lg shadow-lg">'; } ?>
                    </div>
                </div>
            </div>
        </section>
        <?php wp_reset_postdata(); endif; ?>

        <!-- Lecture Notes List Section -->
        <section id="lecture-notes" class="mb-16">
            <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
                <div class="flex flex-col md:flex-row justify-between md:items-center mb-6 gap-4">
                    <h2 class="text-2xl font-bold text-primary"><?php echo esc_html($settings['title']); ?></h2>
                    <div class="flex items-center">
                        <span class="text-sm text-gray-600 mr-2">Filter by topic:</span>
                        <select id="lecture-notes-filter" class="border border-gray-300 rounded-lg px-3 py-1 focus:ring-2 focus:ring-accent focus:border-accent">
                            <option value="0">All Topics</option>
                            <?php 
                            $topics = get_terms(['taxonomy' => 'lecture_topic', 'hide_empty' => true]);
                            foreach ($topics as $topic) { echo '<option value="'.esc_attr($topic->term_id).'">'.esc_html($topic->name).'</option>'; }
                            ?>
                        </select>
                    </div>
                </div>
                
                <div id="lecture-notes-container" class="space-y-6">
                    <?php
                    $initial_query_args = ['post_type' => 'lecture_note', 'posts_per_page' => $settings['posts_per_page'], 'meta_query' => [['key' => '_is_featured', 'value' => 'yes', 'compare' => '!=']]];
                    $initial_query = new \WP_Query($initial_query_args);
                    $total_posts = $initial_query->found_posts;
                    $posts_shown = $initial_query->post_count;
                    if ($initial_query->have_posts()) : while ($initial_query->have_posts()) : $initial_query->the_post();
                        $this->render_lecture_note_card(get_the_ID());
                    endwhile; wp_reset_postdata(); endif;
                    ?>
                </div>
                
                <?php if ($total_posts > $posts_shown): ?>
                <div class="text-center mt-8">
                    <a href="<?php echo get_post_type_archive_link('lecture_note'); ?>" class="px-4 py-2 bg-gray-100 text-primary rounded-lg hover:bg-gray-200 transition">
                        Show More Lecture Notes (<?php echo esc_html($total_posts - $posts_shown); ?> more)
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </section>
        
        <script>
        jQuery(document).ready(function($) {
            $('#lecture-notes-filter').on('change', function() {
                var categoryId = $(this).val();
                var container = $('#lecture-notes-container');
                container.html('<p class="text-center">Loading...</p>'); // Show a loading message

                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'POST',
                    data: {
                        action: 'filter_lecture_notes',
                        category: categoryId,
                        nonce: '<?php echo wp_create_nonce("lecture_notes_nonce"); ?>'
                    },
                    success: function(response) {
                        container.html(response);
                    },
                    error: function() {
                        container.html('<p class="text-center text-red-500">Error loading notes.</p>');
                    }
                });
            });
        });
        </script>
        <?php
    }
}