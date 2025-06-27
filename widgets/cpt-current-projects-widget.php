<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class CPT_Current_Projects_Widget extends \Elementor\Widget_Base {

    public function get_name() { return 'cpt_current_projects'; }
    public function get_title() { return esc_html__( 'CPT: Current Projects', 'tpw' ); }
    public function get_icon() { return 'eicon-posts-ticker'; }
    public function get_categories() { return [ 'basic' ]; }

    protected function _register_controls() {
        $this->start_controls_section('section_content', ['label' => 'Settings']);
        $this->add_control('main_title', ['label' => 'Title', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Current Projects']);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $query_args = [
            'post_type' => 'project',
            'posts_per_page' => -1,
            'meta_query' => [['key' => '_project_status', 'value' => 'ongoing']],
            'orderby' => 'date',
            'order' => 'DESC'
        ];
        $projects_query = new \WP_Query($query_args);
        ?>
        <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
            <h2 class="text-2xl font-bold text-primary mb-6 pb-2 border-b border-gray-200"><?php echo esc_html($settings['main_title']); ?></h2>
            <div class="space-y-6">
                <?php if ($projects_query->have_posts()) : while ($projects_query->have_posts()) : $projects_query->the_post(); 
                    $role = get_post_meta(get_the_ID(), '_project_role', true);
                    $budget = get_post_meta(get_the_ID(), '_project_budget', true);
                    $duration = get_post_meta(get_the_ID(), '_project_duration', true);
                    $sponsor = get_post_meta(get_the_ID(), '_project_sponsor', true);
                    $collaborators = get_post_meta(get_the_ID(), '_project_collaborators', true);
                    $categories = get_the_terms(get_the_ID(), 'project_category');
                ?>
                    <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition duration-300">
                        <div class="md:flex">
                            <?php if (has_post_thumbnail()) : ?>
                            <div class="md:w-1/3 bg-gray-100 p-4 flex items-center justify-center">
                                <img src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title_attribute(); ?>" class="rounded-lg h-full w-full object-cover">
                            </div>
                            <?php endif; ?>
                            <div class="md:w-2/3 p-6">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="text-xl font-bold text-primary mb-2"><?php the_title(); ?></h3>
                                        <div class="flex flex-wrap gap-2 mb-3">
                                            <span class="bg-accent text-white text-xs px-2 py-1 rounded"><?php echo esc_html($duration); ?></span>
                                            <span class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded"><?php echo esc_html($role); ?></span>
                                            <span class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded">Budget: <?php echo esc_html($budget); ?></span>
                                        </div>
                                    </div>
                                    <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Ongoing</span>
                                </div>
                                <div class="text-gray-700 mb-4"><?php the_excerpt(); ?></div>
                                <?php if (!empty($categories) && !is_wp_error($categories)) : ?>
                                <div class="flex flex-wrap gap-2 mb-4">
                                    <?php foreach ($categories as $category) : ?>
                                        <span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded"><?php echo esc_html($category->name); ?></span>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-sm text-gray-600"><span class="font-medium">Sponsor:</span> <?php echo esc_html($sponsor); ?></p>
                                        <p class="text-sm text-gray-600"><span class="font-medium">Collaborators:</span> <?php echo esc_html($collaborators); ?></p>
                                    </div>
                                    <a href="<?php the_permalink(); ?>" class="text-accent font-medium hover:underline flex items-center">Details »</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; wp_reset_postdata(); else: ?>
                    <p>No current projects found.</p>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}