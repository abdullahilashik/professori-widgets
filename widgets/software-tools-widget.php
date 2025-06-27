<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Software_Tools_Widget extends \Elementor\Widget_Base {

    public function get_name() { return 'cpt_software_tools'; }
    public function get_title() { return esc_html__( 'CPT: Software Tools List', 'tpw' ); }
    public function get_icon() { return 'eicon-code-bold'; }
    public function get_categories() { return [ 'basic' ]; }

    protected function _register_controls() {
        $this->start_controls_section('content_section', ['label' => 'Settings']);
        $this->add_control('title', ['label' => 'Main Title', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Featured Software']);
        $this->add_control('other_tools_title', ['label' => 'Other Tools Subheading', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Other Tools']);
        $this->add_control('load_more_text', ['label' => 'Load More Button Text', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Show More Software Tools']);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $featured_id = 0;
        $posts_per_page = 4; // Number of items to load per page/click

        // --- Context-Aware Filtering Logic ---
        $tax_query_args = [];
        $current_term_id = 0;
        if ( is_tax('software_category') ) {
            $current_term = get_queried_object();
            if ($current_term instanceof \WP_Term) {
                $current_term_id = $current_term->term_id;
                $tax_query_args = [['taxonomy' => 'software_category', 'field' => 'term_id', 'terms' => $current_term_id]];
            }
        }
        ?>
        <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
            <div class="flex flex-col md:flex-row justify-between md:items-center mb-6 gap-4">
                <h2 class="text-2xl font-bold text-primary"><?php echo esc_html($settings['title']); ?></h2>
                <?php
                $categories = get_terms(['taxonomy' => 'software_category', 'hide_empty' => true]);
                if (!empty($categories) && !is_wp_error($categories)) : ?>
                <div class="flex items-center">
                    <span class="text-sm text-gray-600 mr-2 flex-shrink-0">Filter by category:</span>
                    <select onchange="if (this.value) window.location.href=this.value;" class="border border-gray-300 rounded-lg px-3 py-1 focus:ring-2 focus:ring-accent focus:border-accent">
                        <option value="<?php echo get_post_type_archive_link('software_tool'); ?>">All Categories</option>
                        <?php foreach($categories as $category) : ?>
                            <option value="<?php echo esc_url(get_term_link($category)); ?>" <?php selected($current_term_id, $category->term_id); ?>>
                                <?php echo esc_html($category->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Featured Tool -->
            <?php
            $featured_query_args = ['post_type' => 'software_tool', 'posts_per_page' => 1, 'meta_key' => '_is_featured', 'meta_value' => 'yes', 'tax_query' => $tax_query_args];
            $featured_query = new \WP_Query($featured_query_args);
            if ($featured_query->have_posts()) : while ($featured_query->have_posts()) : $featured_query->the_post();
                $featured_id = get_the_ID();
                $version = get_post_meta($featured_id, '_version', true);
                $platform = get_post_meta($featured_id, '_platform', true);
                $key_features_raw = get_post_meta($featured_id, '_key_features', true);
                $key_features = !empty($key_features_raw) ? explode("\n", trim($key_features_raw)) : [];
                $download_link = get_post_meta($featured_id, '_download_link', true);
                $docs_link = get_post_meta($featured_id, '_docs_link', true);
                $tutorial_link = get_post_meta($featured_id, '_tutorial_link', true);
                $category = get_the_terms($featured_id, 'software_category');
            ?>
            <div class="mb-8 border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition duration-300">
                <div class="md:flex">
                    <?php if (has_post_thumbnail()): ?>
                    <div class="md:w-1/3 bg-gray-100 p-4 flex items-center justify-center"><img src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title_attribute(); ?>" class="rounded-lg h-full w-full object-contain"></div>
                    <?php endif; ?>
                    <div class="md:w-2/3 p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-xl font-bold text-primary mb-2"><a href="<?php the_permalink(); ?>" class="hover:underline"><?php the_title(); ?></a></h3>
                                <div class="flex flex-wrap gap-2 mb-3">
                                    <?php if (!empty($category)) : ?><span class="bg-accent text-white text-xs px-2 py-1 rounded"><?php echo esc_html($category[0]->name); ?></span><?php endif; ?>
                                    <?php if ($version): ?><span class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded">Version <?php echo esc_html($version); ?></span><?php endif; ?>
                                    <?php if ($platform): ?><span class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded"><?php echo esc_html($platform); ?></span><?php endif; ?>
                                </div>
                            </div>
                            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Featured</span>
                        </div>
                        <div class="text-gray-700 mb-4"><?php echo get_the_content(); ?></div>
                        <?php if (!empty($key_features)): ?>
                        <div class="mb-4">
                            <h4 class="font-medium text-gray-800 mb-2">Key Features:</h4>
                            <ul class="list-disc pl-5 space-y-1 text-gray-700 text-sm">
                                <?php foreach ($key_features as $feature): ?><li><?php echo esc_html(trim($feature)); ?></li><?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>
                        <div class="flex flex-wrap gap-4">
                            <?php if ($download_link): ?><a href="<?php echo esc_url($download_link); ?>" class="bg-primary hover:bg-secondary text-white font-medium py-2 px-4 rounded transition inline-flex items-center"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>Download</a><?php endif; ?>
                            <?php if ($docs_link): ?><a href="<?php echo esc_url($docs_link); ?>" class="border border-primary text-primary hover:bg-gray-50 font-medium py-2 px-4 rounded transition inline-flex items-center"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>Documentation</a><?php endif; ?>
                            <?php if ($tutorial_link): ?><a href="<?php echo esc_url($tutorial_link); ?>" class="text-gray-700 hover:text-accent font-medium py-2 px-4 rounded transition inline-flex items-center"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Tutorial</a><?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; wp_reset_postdata(); endif; ?>

            <!-- Other Tools -->
            <h3 class="text-xl font-bold text-primary mt-8 mb-4"><?php echo esc_html($settings['other_tools_title']); ?></h3>
            <div class="grid md:grid-cols-2 gap-6" id="other-tools-container">
                <?php
                $other_query_args = ['post_type' => 'software_tool', 'posts_per_page' => $posts_per_page, 'post__not_in' => [$featured_id], 'tax_query' => $tax_query_args];
                $other_query = new \WP_Query($other_query_args);
                if ($other_query->have_posts()): while($other_query->have_posts()): $other_query->the_post();
                    $version = get_post_meta(get_the_ID(), '_version', true);
                    $platform = get_post_meta(get_the_ID(), '_platform', true);
                    $category = get_the_terms(get_the_ID(), 'software_category');
                ?>
                <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition">
                    <?php if(has_post_thumbnail()): ?><div class="bg-gray-100 h-48 overflow-hidden"><a href="<?php the_permalink(); ?>"><img src="<?php the_post_thumbnail_url('medium_large'); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-cover"></a></div><?php endif; ?>
                    <div class="p-4">
                        <h3 class="text-lg font-bold text-primary mb-2"><a href="<?php the_permalink(); ?>" class="hover:underline"><?php the_title(); ?></a></h3>
                        <div class="flex flex-wrap gap-2 mb-3">
                           <?php if (!empty($category)) : ?><span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded"><?php echo esc_html($category[0]->name); ?></span><?php endif; ?>
                           <?php if ($platform): ?><span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded"><?php echo esc_html($platform); ?></span><?php endif; ?>
                        </div>
                        <p class="text-gray-600 text-sm mb-4"><?php the_excerpt(); ?></p>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Version <?php echo esc_html($version); ?></span>
                            <a href="<?php the_permalink(); ?>" class="text-accent text-sm font-medium hover:underline">View Details »</a>
                        </div>
                    </div>
                </div>
                <?php endwhile; endif; ?>
            </div>
            
            <!-- AJAX Load More Button -->
            <?php
            // Calculate total pages for the "other" tools
            $full_query_args = ['post_type' => 'software_tool', 'post__not_in' => [$featured_id], 'tax_query' => $tax_query_args, 'posts_per_page' => -1, 'fields' => 'ids'];
            $all_other_posts = new \WP_Query($full_query_args);
            $total_posts = $all_other_posts->found_posts;
            $total_pages = ceil($total_posts / $posts_per_page);
            
            if ($total_pages > 1):
            ?>
            <div class="text-center mt-8">
                <button id="load-more-btn" class="px-4 py-2 bg-gray-100 text-primary rounded-lg hover:bg-gray-200 transition"
                        data-page="2" 
                        data-total-pages="<?php echo esc_attr($total_pages); ?>"
                        data-exclude="[<?php echo esc_attr($featured_id); ?>]"
                        data-taxonomy="<?php echo esc_attr($current_term_id); ?>">
                    <?php echo esc_html($settings['load_more_text']); ?>
                </button>
            </div>
            <?php
            endif;
            wp_reset_postdata();
            ?>
        </div>
        <?php
    }
}