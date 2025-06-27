<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class CPT_Completed_Projects_Widget extends \Elementor\Widget_Base {

    public function get_name() { return 'cpt_completed_projects'; }
    public function get_title() { return esc_html__( 'CPT: Completed Projects', 'tpw' ); }
    public function get_icon() { return 'eicon-archive'; }
    public function get_categories() { return [ 'basic' ]; }

    protected function _register_controls() {
        $this->start_controls_section('section_content', ['label' => 'Settings']);
        $this->add_control('main_title', ['label' => 'Title', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Completed Projects']);
        $this->add_control('posts_per_page', ['label' => 'Projects Per Page', 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 4]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $query_args = [
            'post_type' => 'project',
            'posts_per_page' => $settings['posts_per_page'],
            'paged' => $paged,
            'meta_query' => [['key' => '_project_status', 'value' => 'completed']],
            'orderby' => 'date',
            'order' => 'DESC'
        ];
        $projects_query = new \WP_Query($query_args);
        ?>
        <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
            <h2 class="text-2xl font-bold text-primary mb-6 pb-2 border-b border-gray-200"><?php echo esc_html($settings['main_title']); ?></h2>
            <div class="grid md:grid-cols-2 gap-6">
                <?php if ($projects_query->have_posts()) : while ($projects_query->have_posts()) : $projects_query->the_post();
                    $duration = get_post_meta(get_the_ID(), '_project_duration', true);
                    $budget = get_post_meta(get_the_ID(), '_project_budget', true);
                    $categories = get_the_terms(get_the_ID(), 'project_category');
                ?>
                    <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition">
                        <?php if (has_post_thumbnail()): ?>
                        <div class="bg-gray-100 h-48 overflow-hidden">
                            <a href="<?php the_permalink(); ?>"><img src="<?php the_post_thumbnail_url('medium_large'); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-cover"></a>
                        </div>
                        <?php endif; ?>
                        <div class="p-4">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-lg font-bold text-primary"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded flex-shrink-0"><?php echo esc_html($duration); ?></span>
                            </div>
                            <div class="text-gray-600 text-sm mb-3"><?php the_excerpt(); ?></div>
                            <?php if (!empty($categories) && !is_wp_error($categories)) : ?>
                            <div class="flex flex-wrap gap-2 mb-3">
                                <?php foreach ($categories as $category) : ?>
                                    <span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded"><?php echo esc_html($category->name); ?></span>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                            <div class="flex justify-between items-center">
                                <p class="text-sm text-gray-600"><span class="font-medium">Budget:</span> <?php echo esc_html($budget); ?></p>
                                <a href="<?php the_permalink(); ?>" class="text-accent text-sm font-medium hover:underline">View Project »</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; else: ?>
                    <p>No completed projects found.</p>
                <?php endif; ?>
            </div>
            <div class="mt-8 flex justify-center">
                <?php
                echo paginate_links([
                    'total' => $projects_query->max_num_pages,
                    'current' => $paged,
                    'prev_text' => '«',
                    'next_text' => '»',
                    'type' => 'plain',
                ]);
                wp_reset_postdata();
                ?>
            </div>
        </div>
        <?php
    }
}