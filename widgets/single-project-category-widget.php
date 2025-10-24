<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Single_Project_Category_Widget extends \Elementor\Widget_Base {

    public function get_name() { return 'single_project_category'; }
    public function get_title() { return esc_html__( 'CPT: Single Project Category', 'tpw' ); }
    public function get_icon() { return 'eicon-gallery-grid'; }
    public function get_categories() { return [ 'basic' ]; }

    protected function _register_controls() {
        // --- Content Tab: Section & Query ---
        $this->start_controls_section('section_content', ['label' => 'Content & Query']);
        
        $this->add_control('title', ['label' => 'Section Title', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Research Projects']);

        $options = [];
        $terms = get_terms(['taxonomy' => 'project_type', 'hide_empty' => false]);
        if (!is_wp_error($terms) && !empty($terms)) {
            foreach ($terms as $term) { $options[$term->slug] = $term->name; }
        }
        $this->add_control('project_category_to_display', ['label' => 'Project Category to Display', 'type' => \Elementor\Controls_Manager::SELECT, 'options' => $options, 'description' => 'Select the single category you want to show.']);

        // ====================================================================
        // THE FIX IS HERE: Added 'menu_order' and 'meta_value_num'
        // ====================================================================
        $this->add_control('orderby', [
            'label' => 'Order By',
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'date',
            'options' => [
                'date' => 'Date',
                'title' => 'Title',
                'menu_order' => 'Custom Order', // Sorts by the "Order" field
                'meta_value_num' => 'Project Year', // Sorts by the custom "Year" field
            ]
        ]);
        
        $this->add_control('order', [
            'label' => 'Order',
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'DESC',
            'options' => ['DESC' => 'Descending', 'ASC' => 'Ascending']
        ]);
        
        $this->end_controls_section();

        // --- Style Tab ---
        $this->start_controls_section('section_style_override', ['label' => 'Style Overrides', 'tab' => \Elementor\Controls_Manager::TAB_STYLE]);
        $this->add_control('title_color', ['label' => 'Section Title Color', 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .category-main-title' => 'color: {{VALUE}}']]);
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), ['name' => 'title_typography', 'selector' => '{{WRAPPER}} .category-main-title']);
        $this->add_control('hr1', ['type' => \Elementor\Controls_Manager::DIVIDER]);
        $this->add_control('project_title_color', ['label' => 'Project Title Color', 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .project-card-title a' => 'color: {{VALUE}}']]);
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), ['name' => 'project_title_typography', 'selector' => '{{WRAPPER}} .project-card-title']);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $category_slug = $settings['project_category_to_display'];

        if ( empty($category_slug) ) {
            echo '<div class="elementor-alert elementor-alert-warning">Please select a Project Category in the widget settings.</div>';
            return;
        }

        // ====================================================================
        // THE FIX IS HERE: Build a more intelligent query
        // ====================================================================
        $query_args = [
            'post_type' => 'project',
            'posts_per_page' => -1,
            'orderby' => $settings['orderby'],
            'order' => $settings['order'],
            'tax_query' => [['taxonomy' => 'project_type', 'field' => 'slug', 'terms' => $category_slug]],
        ];

        // If sorting by 'Project Year', we need to add the meta_key to the query
        if ( $settings['orderby'] === 'meta_value_num' ) {
            $query_args['meta_key'] = '_project_year';
        }
        
        $projects_query = new \WP_Query($query_args);
        ?>
        
        <div class="projects-showcase-wrapper">
            <h2 class="text-2xl font-bold text-primary mb-6 category-main-title">
                <?php echo esc_html($settings['title']); ?>
            </h2>
            
            <?php if ($projects_query->have_posts()): ?>
            <div class="grid md:grid-cols-2 gap-6">
                <?php while ($projects_query->have_posts()): $projects_query->the_post();
                    $course_context = get_post_meta(get_the_ID(), '_project_course_context', true);
                ?>
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 transition duration-300 hover:shadow-lg hover:border-accent">
                    <h3 class="text-lg font-bold text-primary mb-3 project-card-title">
                        <a href="<?php the_permalink(); ?>" class="hover:underline">
                            <?php the_title(); ?>
                        </a>
                    </h3>
                    <div class="text-gray-700 text-sm leading-relaxed project-card-content">
                        <?php if ($category_slug === 'research-projects'): ?>
                            <?php echo get_the_excerpt(); ?>
                        <?php else: ?>
                            <?php if ($course_context): ?>
                                <p><?php echo esc_html($course_context); ?></p>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            <?php else: ?>
                <p>No projects found in this category.</p>
            <?php endif; wp_reset_postdata(); ?>
        </div>
        <?php
    }
}