<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Papers_Showcase_Widget extends \Elementor\Widget_Base {

    public function get_name() { return 'cpt_papers_showcase'; }
    public function get_title() { return esc_html__( 'CPT: Papers Showcase', 'tpw' ); }
    public function get_icon() { return 'eicon-archive-posts'; }
    public function get_categories() { return [ 'basic' ]; }

    protected function _register_controls() {
        // --- Content Settings ---
        $this->start_controls_section('content_section', ['label' => 'Content Settings']);
        $this->add_control('posts_per_page', ['label' => 'Initial Publications to Show (per category)','type' => \Elementor\Controls_Manager::NUMBER,'default' => 3]);
        $this->end_controls_section();

        // --- Layout & Display Options ---
        $this->start_controls_section('section_layout_options', ['label' => 'Layout & Display Options']);
        $this->add_control('show_category_nav', [ 'label' => 'Show Category Navigation', 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ]);
        $this->add_control('show_featured_post', [ 'label' => 'Show Featured Publication', 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ]);
        $this->add_control('show_category_titles', [ 'label' => 'Show Category Section Titles', 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ]);
        $this->add_control('show_view_all_button', [ 'label' => 'Show "View All" Buttons', 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ]);
        $this->add_control('hr1', ['type' => \Elementor\Controls_Manager::DIVIDER]);
        $this->add_control('orderby', [ 'label' => 'Order By', 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'date', 'options' => [ 'date' => 'Date', 'title' => 'Title' ] ]);
        $this->add_control('order', [ 'label' => 'Order', 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'DESC', 'options' => [ 'DESC' => 'Descending', 'ASC' => 'Ascending' ] ]);
        $this->end_controls_section();
    }
    
    // The private helper functions have been REMOVED from this file.
    // We will now call the global functions professori_render_dynamic_meta() and professori_render_file_links().

    protected function render() {
        $settings = $this->get_settings_for_display();
        $paper_categories = get_terms(['taxonomy' => 'paper_category', 'hide_empty' => true, 'meta_key' => 'category_order', 'orderby' => 'meta_value_num', 'order' => 'ASC']);
        
        if ('yes' === $settings['show_category_nav']): ?>
        <section class="mb-8">
            <div class="bg-white rounded-lg shadow-md p-4">
                <div class="flex flex-col sm:flex-row justify-center space-y-2 sm:space-y-0 sm:space-x-4">
                <?php if (!empty($paper_categories) && !is_wp_error($paper_categories)): foreach ($paper_categories as $category): ?>
                    <a href="#section-<?php echo esc_attr($category->slug); ?>" class="px-4 py-2 rounded-full border border-primary text-primary hover:bg-gray-50 font-medium"><?php echo esc_html($category->name); ?></a>
                <?php endforeach; endif; ?>
                </div>
            </div>
        </section>
        <?php endif;

        if (!empty($paper_categories) && !is_wp_error($paper_categories)) {
            foreach ($paper_categories as $category) {
                $featured_id = 0;
                ?>
                <section id="section-<?php echo esc_attr($category->slug); ?>" class="mb-16">
                    <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
                        <div class="flex flex-col md:flex-row justify-between md:items-center mb-6 gap-4">
                            <?php if ('yes' === $settings['show_category_titles']): ?><h2 class="text-2xl font-bold text-primary"><?php echo esc_html($category->name); ?></h2><?php endif; ?>
                            <?php if ($category->slug === 'journal-publications'): ?>
                            <div class="flex items-center">
                                <span class="text-sm text-gray-600 mr-2">Filter by year:</span>
                                <select data-category="<?php echo esc_attr($category->slug); ?>" class="papers-year-filter border border-gray-300 rounded-lg px-3 py-1">
                                    <option value="0">All Years</option>
                                    <?php 
                                    global $wpdb; 
                                    $years = $wpdb->get_col("SELECT DISTINCT YEAR(post_date) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'paper' ORDER BY post_date DESC"); 
                                    foreach($years as $year) { echo '<option value="'.esc_attr($year).'">'.esc_html($year).'</option>'; } 
                                    ?>
                                </select>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ('yes' === $settings['show_featured_post']): 
                            $featured_query = new \WP_Query(['post_type' => 'paper', 'posts_per_page' => 1, 'meta_query' => [['key' => '_is_featured', 'value' => 'yes']], 'tax_query' => [['taxonomy' => 'paper_category', 'field' => 'term_id', 'terms' => $category->term_id]]]);
                            if ($featured_query->have_posts()): while ($featured_query->have_posts()): $featured_query->the_post(); $featured_id = get_the_ID(); ?>
                            <div class="mb-8 border border-accent rounded-lg overflow-hidden">
                                <div class="bg-accent text-white px-4 py-2"><h3 class="font-bold">Featured Publication</h3></div>
                                <div class="p-4">
                                    <h3 class="text-xl font-bold text-primary mb-2"><a href="<?php the_permalink(); ?>" class="hover:underline"><?php the_title(); ?></a></h3>
                                    <p class="text-gray-700 mb-2"><span class="font-medium">Authors:</span> <?php echo esc_html(get_post_meta(get_the_ID(), '_authors', true)); ?></p>
                                    <p class="text-gray-700 mb-2"><span class="font-medium">In:</span> <?php echo esc_html(get_post_meta(get_the_ID(), '_publication_info', true)); ?></p>
                                    <?php professori_render_dynamic_meta(get_the_ID(), 'text-gray-700 mb-3'); ?>
                                    <div class="text-gray-700 mb-4"><?php the_excerpt(); ?></div>
                                    <div class="flex justify-between items-center"><?php professori_render_file_links(get_the_ID()); ?></div>
                                </div>
                            </div>
                            <?php endwhile; wp_reset_postdata(); endif;
                        endif; ?>

                        <div class="papers-list-container space-y-6" data-category-slug="<?php echo esc_attr($category->slug); ?>" data-featured-id="<?php echo esc_attr($featured_id); ?>">
                            <?php
                            $list_query_args = ['post_type' => 'paper', 'posts_per_page' => $settings['posts_per_page'], 'post__not_in' => [$featured_id], 'tax_query' => [['taxonomy' => 'paper_category', 'field' => 'term_id', 'terms' => $category->term_id]], 'orderby' => $settings['orderby'], 'order' => $settings['order']];
                            $list_query = new \WP_Query($list_query_args);
                            if($list_query->have_posts()): while($list_query->have_posts()): $list_query->the_post(); ?>
                            <div class="border-l-4 border-primary pl-4 py-2 hover:bg-gray-50 transition">
                                <h3 class="text-lg font-bold text-primary mb-1"><a href="<?php the_permalink(); ?>" class="hover:underline"><?php the_title(); ?></a></h3>
                                <p class="text-gray-700 mb-1"><span class="font-medium">Authors:</span> <?php echo esc_html(get_post_meta(get_the_ID(), '_authors', true)); ?></p>
                                <p class="text-gray-700 mb-2"><span class="font-medium">In:</span> <?php echo esc_html(get_post_meta(get_the_ID(), '_publication_info', true)); ?></p>
                                <div class="flex justify-between items-center">
                                    <?php professori_render_dynamic_meta(get_the_ID()); ?>
                                    <?php professori_render_file_links(get_the_ID()); ?>
                                </div>
                            </div>
                            <?php endwhile; endif; ?>
                        </div>
                        
                        <?php if ('yes' === $settings['show_view_all_button']):
                            $total_posts_query = new \WP_Query(['post_type' => 'paper', 'post__not_in' => [$featured_id], 'tax_query' => [['taxonomy' => 'paper_category', 'field' => 'term_id', 'terms' => $category->term_id]], 'fields' => 'ids']);
                            $total_posts = $total_posts_query->found_posts;
                            $posts_shown = $list_query->post_count;
                            if ($total_posts > $posts_shown): ?>
                            <div class="text-center mt-6">
                                <a href="<?php echo esc_url(get_term_link($category)); ?>" class="px-4 py-2 bg-gray-100 text-primary rounded-lg hover:bg-gray-200 transition">Show More <?php echo esc_html($category->name); ?> (<?php echo $total_posts - $posts_shown; ?> more)</a>
                            </div>
                            <?php endif; 
                        endif; wp_reset_postdata(); ?>
                    </div>
                </section>
                <?php
            }
        }
        ?>
        <script>
        jQuery(document).ready(function($) {
            $('.papers-year-filter').on('change', function() {
                var filter = $(this);
                var year = filter.val();
                var container = filter.closest('.p-6, .p-8').find('.papers-list-container');
                var categorySlug = container.data('category-slug');
                var featuredId = container.data('featured-id');
                
                container.html('<p class="text-center py-4">Loading...</p>');

                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'POST',
                    data: {
                        action: 'filter_papers',
                        nonce: '<?php echo wp_create_nonce("papers_nonce"); ?>',
                        year: year,
                        category: categorySlug,
                        featured_id: featuredId
                    },
                    success: function(response) { container.html(response); },
                    error: function() { container.html('<p class="text-center text-red-500 py-4">Error loading publications.</p>'); }
                });
            });
        });
        </script>
        <?php
    }
}