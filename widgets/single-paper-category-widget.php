<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Single_Paper_Category_Widget extends \Elementor\Widget_Base {

    public function get_name() { return 'single_paper_category'; }
    public function get_title() { return esc_html__( 'CPT: Single Paper Category', 'tpw' ); }
    public function get_icon() { return 'eicon-bullet-list'; }
    public function get_categories() { return [ 'basic' ]; }

    protected function _register_controls() {
        // --- Content & Query ---
        $this->start_controls_section('section_content', ['label' => 'Content & Query']);
        
        $this->add_control('title', ['label' => 'Custom Title', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Journal Publications', 'description' => 'This title will appear above the list/grid.']);

        $options = [];
        $terms = get_terms(['taxonomy' => 'paper_category', 'hide_empty' => false]);
        if (!is_wp_error($terms) && !empty($terms)) {
            foreach ($terms as $term) { $options[$term->slug] = $term->name; }
        }
        $this->add_control('paper_category_to_display', ['label' => 'Publication Category to Display', 'type' => \Elementor\Controls_Manager::SELECT, 'options' => $options, 'description' => 'Select the single category to show.']);
        
        $this->add_control('posts_per_page', ['label' => 'Number of Papers to Show', 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 10, 'description' => '-1 shows all.']);
        
        $this->add_control('orderby', ['label' => 'Order By', 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'date', 'options' => ['date' => 'Date', 'title' => 'Title']]);
        $this->add_control('order', ['label' => 'Order', 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'DESC', 'options' => ['DESC' => 'Descending', 'ASC' => 'Ascending']]);
        
        $this->end_controls_section();

        // --- Layout ---
        $this->start_controls_section('section_layout', ['label' => 'Layout']);
        $this->add_control('layout', ['label' => 'Layout Style', 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'grid', 'options' => ['grid' => 'Grid (Full details)', 'list' => 'List (Titles only)']]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $category_slug = $settings['paper_category_to_display'];
        if (empty($category_slug)) {
            echo '<div class="elementor-alert elementor-alert-warning">Please select a Publication Category to display.</div>';
            return;
        }

        $query_args = [
            'post_type' => 'paper',
            'posts_per_page' => $settings['posts_per_page'],
            'orderby' => $settings['orderby'],
            'order' => $settings['order'],
            'tax_query' => [['taxonomy' => 'paper_category', 'field' => 'slug', 'terms' => $category_slug]],
        ];
        $papers_query = new \WP_Query($query_args);
        ?>
        <div class="papers-category-wrapper">
            <h2 class="text-2xl font-bold text-primary mb-6 pb-2 border-b border-gray-200">
                <?php echo esc_html($settings['title']); ?>
            </h2>
            
            <?php if ($papers_query->have_posts()): ?>
                <?php if ($settings['layout'] === 'grid'): ?>
                    <div class="space-y-6">
                        <?php while ($papers_query->have_posts()): $papers_query->the_post(); ?>
                            <div class="border-l-4 border-primary pl-4 py-2 hover:bg-gray-50 transition">
                                <h3 class="text-lg font-bold text-primary mb-1"><a href="<?php the_permalink(); ?>" class="hover:underline"><?php the_title(); ?></a></h3>
                                <p class="text-gray-700 mb-1"><span class="font-medium">Authors:</span> <?php echo esc_html(get_post_meta(get_the_ID(), '_authors', true)); ?></p>
                                <p class="text-gray-700 mb-2"><span class="font-medium">In:</span> <?php echo esc_html(get_post_meta(get_the_ID(), '_publication_info', true)); ?></p>
                                <div class="flex justify-between items-center">
                                    <?php professori_render_dynamic_meta(get_the_ID()); ?>
                                    <?php professori_render_file_links(get_the_ID()); ?>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: // List Layout ?>
                    <ul class="space-y-2 list-disc pl-5">
                        <?php while ($papers_query->have_posts()): $papers_query->the_post(); ?>
                            <li>
                                <a href="<?php the_permalink(); ?>" class="text-primary hover:underline"><?php the_title(); ?></a>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php endif; ?>
            <?php else: ?>
                <p>No publications found in this category.</p>
            <?php endif; wp_reset_postdata(); ?>
        </div>
        <?php
    }
}