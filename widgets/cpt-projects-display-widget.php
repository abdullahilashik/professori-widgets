<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class CPT_Projects_Display_Widget extends \Elementor\Widget_Base {

    public function get_name() { return 'cpt_projects_display'; }
    public function get_title() { return esc_html__( 'CPT: Projects Display', 'tpw' ); }
    public function get_icon() { return 'eicon-folder'; }
    public function get_categories() { return [ 'basic' ]; }

    protected function _register_controls() {
        // --- Content Tab ---
        $this->start_controls_section('section_content', ['label' => 'Content']);
        $this->add_control('main_title', ['label' => 'Main Title', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Previous Projects']);
        $this->add_control('intro_text', ['label' => 'Introduction Text', 'type' => \Elementor\Controls_Manager::WYSIWYG, 'default' => 'Below, you can find brief descriptions of some previous projects I completed...']);
        $this->end_controls_section();

        // --- Query Settings Tab ---
        $this->start_controls_section('section_query', ['label' => 'Query Settings']);
        $project_types_options = [];
        $project_types = get_terms(['taxonomy' => 'project_type', 'hide_empty' => false]);
        if (!is_wp_error($project_types) && !empty($project_types)) {
            foreach ($project_types as $type) { $project_types_options[$type->slug] = $type->name; }
        }
        $this->add_control('project_types_to_display', ['label' => 'Project Types to Display', 'type' => \Elementor\Controls_Manager::SELECT2, 'multiple' => true, 'options' => $project_types_options, 'default' => array_keys($project_types_options), 'description' => 'Leave empty to show all types.']);
        $this->add_control('orderby', ['label' => 'Order By', 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'title', 'options' => ['date' => 'Date', 'title' => 'Title']]);
        $this->add_control('order', ['label' => 'Order', 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'ASC', 'options' => ['DESC' => 'Descending', 'ASC' => 'Ascending']]);
        $this->end_controls_section();

        // --- Style Tab ---
        $this->start_controls_section('section_style_main', ['label' => 'Main Titles & Text', 'tab' => \Elementor\Controls_Manager::TAB_STYLE]);
        $this->add_control('main_title_heading', ['label' => 'Main Title', 'type' => \Elementor\Controls_Manager::HEADING]);
        $this->add_control('main_title_color', ['label' => 'Color', 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .projects-main-title' => 'color: {{VALUE}}']]);
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), ['name' => 'main_title_typography', 'selector' => '{{WRAPPER}} .projects-main-title']);
        $this->add_control('intro_text_heading', ['label' => 'Introduction Text', 'type' => \Elementor\Controls_Manager::HEADING, 'separator' => 'before']);
        $this->add_control('intro_text_color', ['label' => 'Color', 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .projects-intro-text' => 'color: {{VALUE}}']]);
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), ['name' => 'intro_text_typography', 'selector' => '{{WRAPPER}} .projects-intro-text p']);
        $this->end_controls_section();

        $this->start_controls_section('section_style_list', ['label' => 'Project List', 'tab' => \Elementor\Controls_Manager::TAB_STYLE]);
        $this->add_control('category_title_heading', ['label' => 'Category Titles', 'type' => \Elementor\Controls_Manager::HEADING]);
        $this->add_control('category_title_color', ['label' => 'Color', 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .projects-category-title' => 'color: {{VALUE}}']]);
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), ['name' => 'category_title_typography', 'selector' => '{{WRAPPER}} .projects-category-title']);
        $this->add_control('project_content_heading', ['label' => 'Project Content', 'type' => \Elementor\Controls_Manager::HEADING, 'separator' => 'before']);
        $this->add_control('project_content_color', ['label' => 'Color', 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .project-item' => 'color: {{VALUE}}']]);
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), ['name' => 'project_content_typography', 'selector' => '{{WRAPPER}} .project-item, {{WRAPPER}} .project-item p, {{WRAPPER}} .project-item strong']);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $types_to_get = $settings['project_types_to_display'];
        if (empty($types_to_get)) {
            $project_types = get_terms(['taxonomy' => 'project_type', 'hide_empty' => true]);
        } else {
            $project_types = get_terms(['taxonomy' => 'project_type', 'slug' => $types_to_get]);
        }
        
        if (empty($project_types) || is_wp_error($project_types)) {
            echo '<p>No project types found.</p>';
            return;
        }
        ?>
        <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
            <h1 class="text-3xl font-bold text-primary mb-6 pb-4 border-b border-gray-200 projects-main-title"><?php echo esc_html($settings['main_title']); ?></h1>
            <div class="prose max-w-none text-gray-700 mb-8 projects-intro-text">
                <?php echo wp_kses_post($settings['intro_text']); ?>
            </div>

            <?php foreach ($project_types as $type): ?>
                <div class="mb-12 last:mb-0">
                    <h2 class="text-2xl font-bold text-primary mb-6 projects-category-title"><?php echo esc_html($type->name); ?></h2>
                    <ul class="space-y-6 list-disc pl-5">
                    <?php
                    $projects_query = new \WP_Query([
                        'post_type' => 'project',
                        'posts_per_page' => -1,
                        'orderby' => $settings['orderby'],
                        'order' => $settings['order'],
                        'tax_query' => [['taxonomy' => 'project_type', 'field' => 'term_id', 'terms' => $type->term_id]],
                    ]);
                    if ($projects_query->have_posts()): while ($projects_query->have_posts()): $projects_query->the_post();
                        $course_context = get_post_meta(get_the_ID(), '_project_course_context', true);
                    ?>
                        <li class="project-item">
                            <?php if ($type->slug === 'research-projects'): ?>
                                <div class="text-gray-700 leading-relaxed prose prose-sm max-w-none"><?php the_content(); ?></div>
                            <?php else: // Course Projects ?>
                                <strong class="font-medium text-gray-800"><?php the_title(); ?></strong>
                                <?php if ($course_context): ?> - <span class="text-gray-600"><?php echo esc_html($course_context); ?></span><?php endif; ?>
                            <?php endif; ?>
                        </li>
                    <?php endwhile; endif; wp_reset_postdata(); ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}