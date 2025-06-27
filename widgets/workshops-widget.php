<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Workshops_Widget extends \Elementor\Widget_Base {

    public function get_name() { return 'cpt_workshops'; }
    public function get_title() { return esc_html__( 'CPT: Workshops', 'tpw' ); }
    public function get_icon() { return 'eicon-calendar'; }
    public function get_categories() { return [ 'basic' ]; }

    protected function _register_controls() {
        $this->start_controls_section('content_section', ['label' => 'Settings']);
        $this->add_control('title', ['label' => 'Title', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Workshop Materials']);
        $this->add_control('posts_per_page', ['label' => 'Number of Workshops to Show', 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 2]);
        $this->end_controls_section();
    }
    
    // =================================================================
    // THE FIX FOR FILE LINKS IS HERE
    // The SVG code is now correctly placed inside the helper function.
    // =================================================================
    private function render_file_links($post_id, $classes = 'text-accent hover:underline flex items-center text-sm') {
        $file_links_raw = get_post_meta($post_id, '_file_links', true);
        if (empty($file_links_raw)) return;
        $file_links = explode("\n", trim($file_links_raw));
        
        echo '<div class="flex flex-wrap gap-3">';
        foreach ($file_links as $link) {
            if (strpos($link, '|') !== false) {
                list($label, $url) = explode('|', $link, 2);
                $label_html = esc_html(trim($label));
                $url_html = esc_url(trim($url));

                // Determine icon based on label
                $icon_svg = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>';
                if (stripos($label_html, 'recording') !== false || stripos($label_html, 'video') !== false) {
                    $icon_svg = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>';
                }

                echo '<a href="'.$url_html.'" class="'.esc_attr($classes).'" target="_blank">'.$icon_svg.' '.$label_html.'</a>';
            }
        }
        echo '</div>';
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $query_args = [
            'post_type' => 'workshop',
            'posts_per_page' => $settings['posts_per_page'],
            'orderby' => 'date',
            'order' => 'DESC'
        ];
        $workshops_query = new \WP_Query($query_args);
        ?>
        <section class="mb-16">
            <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
                <h2 class="text-2xl font-bold text-primary mb-6"><?php echo esc_html($settings['title']); ?></h2>
                
                <?php if ($workshops_query->have_posts()) : ?>
                <div class="grid md:grid-cols-2 gap-6">
                    <?php while ($workshops_query->have_posts()) : $workshops_query->the_post();
                        $post_id = get_the_ID();
                        $date = get_post_meta($post_id, '_workshop_date', true);
                        $location = get_post_meta($post_id, '_workshop_location', true);
                        
                        // ===============================================
                        // THE FIX FOR TAGS IS HERE
                        // We query the 'tutorial_tag' taxonomy.
                        // ===============================================
                        $tags = get_the_terms($post_id, 'tutorial_tag');
                        if ( is_wp_error($tags) ) {
                            $tags = false;
                        }
                    ?>
                    <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition">
                        <div class="bg-gray-100 p-4">
                            <h3 class="text-lg font-bold text-primary mb-2">
                                <a href="<?php the_permalink(); ?>" class="hover:underline"><?php the_title(); ?></a>
                            </h3>
                            <div class="text-gray-700 text-sm mb-3"><?php the_excerpt(); ?></div>
                            <div class="flex flex-wrap gap-2 mb-3">
                                <?php if ($date): ?><span class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded"><?php echo esc_html($date); ?></span><?php endif; ?>
                                <?php if ($location): ?><span class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded"><?php echo esc_html($location); ?></span><?php endif; ?>
                                <?php // Loop through and display tags if they exist
                                if (!empty($tags)) {
                                    foreach($tags as $tag) {
                                        echo '<span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">' . esc_html($tag->name) . '</span>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        <div class="p-4 bg-gray-50 border-t border-gray-200">
                             <?php $this->render_file_links($post_id); ?>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
                <?php endif; wp_reset_postdata(); ?>
                
                <!-- The "View All" button can be added here if needed -->                 
            </div>
        </section>
        <?php
    }
}