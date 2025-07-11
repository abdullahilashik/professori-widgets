<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Papers_Showcase_Widget extends \Elementor\Widget_Base {

    public function get_name() { return 'cpt_papers_showcase'; }
    public function get_title() { return esc_html__( 'CPT: Papers Showcase', 'tpw' ); }
    public function get_icon() { return 'eicon-archive-posts'; }
    public function get_categories() { return [ 'basic' ]; }

    protected function _register_controls() {
        // --- Settings for each section ---
        $this->start_controls_section('content_section', ['label' => 'Content Settings']);
        
        $this->add_control('posts_per_page', [
            'label' => esc_html__( 'Initial Publications to Show (per category)', 'tpw' ),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 3,
        ]);

        $this->end_controls_section();

        // --- Layout & Display Customization Options ---
        $this->start_controls_section(
            'section_layout_options',
            [
                'label' => esc_html__( 'Layout & Display Options', 'tpw' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control('show_category_nav', [ 'label' => esc_html__( 'Show Category Navigation', 'tpw' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'label_on' => 'Show', 'label_off' => 'Hide', 'return_value' => 'yes', 'default' => 'yes' ]);
        $this->add_control('show_featured_post', [ 'label' => esc_html__( 'Show Featured Publication', 'tpw' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'label_on' => 'Show', 'label_off' => 'Hide', 'return_value' => 'yes', 'default' => 'yes' ]);
        $this->add_control('show_category_titles', [ 'label' => esc_html__( 'Show Category Section Titles', 'tpw' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'label_on' => 'Show', 'label_off' => 'Hide', 'return_value' => 'yes', 'default' => 'yes' ]);
        $this->add_control('show_view_all_button', [ 'label' => esc_html__( 'Show "View All" Buttons', 'tpw' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'label_on' => 'Show', 'label_off' => 'Hide', 'return_value' => 'yes', 'default' => 'yes' ]);
        $this->add_control('hr1', ['type' => \Elementor\Controls_Manager::DIVIDER]);
        $this->add_control('orderby', [ 'label' => esc_html__( 'Order By', 'tpw' ), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'date', 'options' => [ 'date' => 'Date', 'title' => 'Title' ] ]);
        $this->add_control('order', [ 'label' => esc_html__( 'Order', 'tpw' ), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'DESC', 'options' => [ 'DESC' => 'Descending', 'ASC' => 'Ascending' ] ]);

        $this->end_controls_section();
    }
    
    // Helper function to render the dynamic meta info
    private function render_dynamic_meta($post_id, $classes = 'flex space-x-3 text-sm') {
        $dynamic_meta_raw = get_post_meta($post_id, '_dynamic_meta', true);
        if (empty($dynamic_meta_raw)) return;
        $metas = explode("\n", trim($dynamic_meta_raw));
        echo '<div class="'.esc_attr($classes).'">';
        foreach ($metas as $meta) {
            if (strpos($meta, '|') !== false) {
                list($label, $value) = explode('|', $meta, 2);
                echo '<span class="text-gray-600"><span class="font-medium">'.esc_html(trim($label)).':</span> '.esc_html(trim($value)).'</span>';
            }
        }
        echo '</div>';
    }
    
    // Helper function to render file links
    private function render_file_links($post_id, $classes = 'flex space-x-2') {
        $file_links_raw = get_post_meta($post_id, '_file_links', true);
        if (empty($file_links_raw)) return;
        $links = explode("\n", trim($file_links_raw));
        echo '<div class="'.esc_attr($classes).'">';
        foreach ($links as $link) {
            if (strpos($link, '|') !== false) {
                list($label, $url) = explode('|', $link, 2);
                echo '<a href="'.esc_url(trim($url)).'" class="text-accent hover:underline text-sm" target="_blank">'.esc_html(trim($label)).'</a>';
            }
        }
        echo '</div>';
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        // $paper_categories = get_terms(['taxonomy' => 'paper_category', 'hide_empty' => true, 'orderby' => 'name']);
        $paper_categories = get_terms([
        'taxonomy'   => 'paper_category',
        'hide_empty' => true,
        'meta_key'   => 'category_order', // Tell WordPress to look for our custom field
        'orderby'    => 'meta_value_num', // Tell WordPress to sort by that field's value, as a number
        'order'      => 'ASC',              // Sort in ascending order (0, 1, 2, 3...)
    ]);
        
        if ('yes' === $settings['show_category_nav']): ?>
        <!-- Publications Navigation -->
        <section class="mb-8"><div class="bg-white rounded-lg shadow-md p-4"><div class="flex flex-col sm:flex-row justify-center space-y-2 sm:space-y-0 sm:space-x-4">
        <?php if (!empty($paper_categories) && !is_wp_error($paper_categories)): foreach ($paper_categories as $category): ?>
            <a href="#section-<?php echo esc_attr($category->slug); ?>" class="px-4 py-2 rounded-full border border-primary text-primary hover:bg-gray-50 font-medium"><?php echo esc_html($category->name); ?></a>
        <?php endforeach; endif; ?>
        </div></div></section>
        <?php endif;

        // Loop through each category to render its section
        if (!empty($paper_categories) && !is_wp_error($paper_categories)) {
            foreach ($paper_categories as $category) {
                $featured_id = 0;
                ?>
                <section id="section-<?php echo esc_attr($category->slug); ?>" class="mb-16">
                    <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
                        <div class="flex flex-col md:flex-row justify-between md:items-center mb-6 gap-4">
                            <?php if ('yes' === $settings['show_category_titles']): ?>
                            <h2 class="text-2xl font-bold text-primary"><?php echo esc_html($category->name); ?></h2>
                            <?php endif; ?>
                            <?php if ($category->slug === 'journal-publications'): /* AJAX Filter only for Journals */ ?>
                            <div class="flex items-center">
                                <span class="text-sm text-gray-600 mr-2">Filter by year:</span>
                                <select data-category="<?php echo esc_attr($category->slug); ?>" class="papers-year-filter border border-gray-300 rounded-lg px-3 py-1 focus:ring-2 focus:ring-accent focus:border-accent">
                                    <option value="0">All Years</option>
                                    <?php /* Years populated dynamically via JS if needed, or PHP */ ?>
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
                                    <?php $this->render_dynamic_meta(get_the_ID(), 'text-gray-700 mb-3'); ?>
                                    <div class="text-gray-700 mb-4"><?php the_excerpt(); ?></div>
                                    <div class="flex justify-between items-center"><?php $this->render_file_links(get_the_ID()); ?></div>
                                </div>
                            </div>
                            <?php endwhile; wp_reset_postdata(); endif;
                        endif; ?>

                        <div class="papers-list-container space-y-6" data-category-slug="<?php echo esc_attr($category->slug); ?>">
                            <?php
                            $list_query_args = ['post_type' => 'paper', 'posts_per_page' => $settings['posts_per_page'], 'post__not_in' => [$featured_id], 'tax_query' => [['taxonomy' => 'paper_category', 'field' => 'term_id', 'terms' => $category->term_id]], 'orderby' => $settings['orderby'], 'order' => $settings['order']];
                            $list_query = new \WP_Query($list_query_args);
                            if($list_query->have_posts()): while($list_query->have_posts()): $list_query->the_post(); ?>
                            <div class="border-l-4 border-primary pl-4 py-2 hover:bg-gray-50 transition">
                                <h3 class="text-lg font-bold text-primary mb-1"><a href="<?php the_permalink(); ?>" class="hover:underline"><?php the_title(); ?></a></h3>
                                <p class="text-gray-700 mb-1"><span class="font-medium">Authors:</span> <?php echo esc_html(get_post_meta(get_the_ID(), '_authors', true)); ?></p>
                                <p class="text-gray-700 mb-2"><span class="font-medium">In:</span> <?php echo esc_html(get_post_meta(get_the_ID(), '_publication_info', true)); ?></p>
                                <div class="flex justify-between items-center">
                                    <?php $this->render_dynamic_meta(get_the_ID()); ?>
                                    <?php $this->render_file_links(get_the_ID()); ?>
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
        <!-- AJAX Script -->
        <script>
        jQuery(document).ready(function($) {
            $('.papers-year-filter').on('change', function() {
                var filter = $(this);
                var year = filter.val();
                var category = filter.closest('.papers-list-container').data('category-slug');
                var container = filter.closest('.p-6, .p-8').find('.papers-list-container');
                
                container.html('<p class="text-center">Loading...</p>');

                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'POST',
                    data: {
                        action: 'filter_papers',
                        nonce: '<?php echo wp_create_nonce("papers_nonce"); ?>',
                        year: year,
                        category: category
                    },
                    success: function(response) {
                        container.html(response);
                    },
                    error: function() {
                        container.html('<p class="text-center text-red-500">Error loading publications.</p>');
                    }
                });
            });
        });
        </script>
        <?php
    }
}