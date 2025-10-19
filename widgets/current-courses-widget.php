<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Current_Courses_Widget extends \Elementor\Widget_Base {

    public function get_name() { return 'current_courses'; }

    // ===============================================
    // THE SYNTAX ERROR FIX IS HERE
    // The invalid period '.' after 'return' is removed.
    // ===============================================
    public function get_title() { 
        return esc_html__( 'CPT: Courses List', 'tpw' ); 
    }

    public function get_icon() { return 'eicon-post-list'; }
    public function get_categories() { return [ 'basic' ]; }

    protected function _register_controls() {
        // --- Content & Query Section ---
        $this->start_controls_section('section_content', ['label' => 'Content & Query']);
        $this->add_control('main_title', ['label' => 'Title', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Current Courses']);
        
        $options = [];
        $terms = get_terms(['taxonomy' => 'course_level', 'hide_empty' => false]);
        if (!is_wp_error($terms) && !empty($terms)) {
            foreach ($terms as $term) { $options[$term->term_id] = $term->name; }
        }
        $this->add_control('course_levels_to_display', [
            'label' => 'Filter by Course Level', 'type' => \Elementor\Controls_Manager::SELECT2, 
            'multiple' => true, 'options' => $options, 
            'description' => 'Leave empty to show all levels.'
        ]);
        
        $this->add_control('posts_per_page', ['label' => 'Number of Courses to Show', 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => -1, 'description' => '-1 means show all.']);
        $this->add_control('orderby', ['label' => 'Order By', 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'date', 'options' => ['date' => 'Date', 'title' => 'Title']]);
        $this->add_control('order', ['label' => 'Order', 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'DESC', 'options' => ['DESC' => 'Descending', 'ASC' => 'Ascending']]);
        $this->add_control('not_found_message', ['label' => 'Not Found Message', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'No courses are listed at this time.']);
        $this->end_controls_section();

        // --- Layout Section ---
        $this->start_controls_section('section_layout', ['label' => 'Layout']);
        $this->add_control('layout', [
            'label' => 'Layout Style', 'type' => \Elementor\Controls_Manager::SELECT, 
            'default' => 'grid', 'options' => ['grid' => 'Grid', 'list' => 'List']
        ]);
        $this->add_control('show_image', [
            'label' => 'Show Featured Image', 'type' => \Elementor\Controls_Manager::SWITCHER, 
            'label_on' => 'Show', 'label_off' => 'Hide', 'return_value' => 'yes', 'default' => 'no'
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $query_args = [
            'post_type'      => 'course',
            'posts_per_page' => $settings['posts_per_page'],
            'orderby'        => $settings['orderby'],
            'order'          => $settings['order'],
            'meta_query'     => [['key' => '_is_current_course', 'value' => 'yes', 'compare' => '=']],
        ];
        if (!empty($settings['course_levels_to_display'])) {
            $query_args['tax_query'] = [['taxonomy' => 'course_level', 'field' => 'term_id', 'terms' => $settings['course_levels_to_display']]];
        }
        $courses_query = new \WP_Query($query_args);
        
        $layout_class = $settings['layout'] === 'grid' ? 'grid md:grid-cols-2 gap-6' : 'space-y-4';
        ?>
        <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
            <h2 class="text-2xl font-bold text-primary mb-6 pb-2 border-b border-gray-200">
                <?php echo esc_html($settings['main_title']); ?>
            </h2>
            <?php if ($courses_query->have_posts()) : ?>
                <div class="<?php echo esc_attr($layout_class); ?>">
                    <?php while ($courses_query->have_posts()) : $courses_query->the_post(); 
                        $course_code = get_post_meta(get_the_ID(), '_course_code', true);
                        $semester = get_post_meta(get_the_ID(), '_course_semester', true);
                        $levels = get_the_terms(get_the_ID(), 'course_level');
                        $level_name = (!empty($levels) && !is_wp_error($levels)) ? esc_html($levels[0]->name) : 'Course';

                        if ($settings['layout'] === 'grid') : ?>
                            <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition">
                                <?php if ('yes' === $settings['show_image'] && has_post_thumbnail()): ?>
                                    <a href="<?php the_permalink(); ?>" class="block h-48 bg-gray-200">
                                        <?php the_post_thumbnail('medium_large', ['class' => 'w-full h-full object-cover']); ?>
                                    </a>
                                <?php endif; ?>
                                <div class="bg-primary text-white px-4 py-3">
                                    <h3 class="font-bold text-lg"><a href="<?php the_permalink(); ?>" class="text-white hover:underline"><?php echo esc_html($course_code); ?>: <?php the_title(); ?></a></h3>
                                    <p class="text-blue-200 text-sm"><?php echo $level_name; ?></p>
                                </div>
                                <div class="p-4">
                                    <div class="text-gray-700 mb-3 h-16 overflow-hidden"><?php echo get_the_excerpt(); ?></div>
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="bg-gray-100 px-2 py-1 rounded text-gray-700"><?php echo esc_html($semester); ?></span>
                                        <a href="<?php the_permalink(); ?>" class="text-accent hover:underline">Materials →</a>
                                    </div>
                                </div>
                            </div>
                        <?php else: // List Layout ?>
                            <div class="border-l-4 border-primary pl-4 py-2 hover:bg-gray-50 transition">
                                <h3 class="text-lg font-bold text-primary mb-1"><a href="<?php the_permalink(); ?>" class="hover:underline"><?php echo esc_html($course_code); ?>: <?php the_title(); ?></a></h3>
                                <p class="text-sm text-gray-600 mb-2"><?php echo $level_name; ?> - <?php echo esc_html($semester); ?></p>
                                <div class="text-gray-700 text-sm"><?php echo get_the_excerpt(); ?></div>
                            </div>
                        <?php endif; ?>
                    <?php endwhile; ?>
                </div>
                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <p><?php echo esc_html($settings['not_found_message']); ?></p>
            <?php endif; ?>
        </div>
        <?php
    }
}