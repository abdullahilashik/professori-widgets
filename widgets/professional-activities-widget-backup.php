<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Professional_Activities_Widget extends \Elementor\Widget_Base {

    public function get_name() { return 'professional_activities'; }
    public function get_title() { return esc_html__( 'CPT: Professional Activities', 'tpw' ); }
    public function get_icon() { return 'eicon-sitemap'; }
    public function get_categories() { return [ 'basic' ]; }

    protected function _register_controls() {
        $this->start_controls_section('content_section', ['label' => 'Settings']);
        $this->add_control('title', ['label' => 'Main Title', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Professional Activities']);
        $this->add_control('columns', [
            'label' => esc_html__( 'Number of Columns', 'tpw' ),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => '2',
            'options' => [
                '1' => esc_html__( '1', 'tpw' ),
                '2' => esc_html__( '2', 'tpw' ),
                '3' => esc_html__( '3', 'tpw' ),
            ],
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Get all the "Activity Group" terms that have posts associated with them.
        $activity_groups = get_terms([
            'taxonomy' => 'activity_group',
            'hide_empty' => true,
            'orderby' => 'name',
            'order' => 'ASC',
        ]);

        $columns_class = 'grid md:grid-cols-' . esc_attr($settings['columns']) . ' gap-8';
        ?>
        <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
            <h2 class="text-2xl font-bold text-primary mb-6 pb-2 border-b border-gray-200"><?php echo esc_html($settings['title']); ?></h2>
            
            <?php if ( ! empty($activity_groups) && ! is_wp_error($activity_groups) ) : ?>
                <div class="<?php echo $columns_class; ?>">
                    <?php foreach ($activity_groups as $group) : ?>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800 mb-4"><?php echo esc_html($group->name); ?></h3>
                            <ul class="space-y-4">
                                <?php
                                $activity_query = new \WP_Query([
                                    'post_type' => 'activity',
                                    'posts_per_page' => -1,
                                    'orderby' => 'menu_order',
                                    'order' => 'ASC',
                                    'tax_query' => [
                                        [
                                            'taxonomy' => 'activity_group',
                                            'field'    => 'term_id',
                                            'terms'    => $group->term_id,
                                        ],
                                    ],
                                ]);
                                if ($activity_query->have_posts()) : while ($activity_query->have_posts()) : $activity_query->the_post();
                                ?>
                                    <li class="flex items-start">
                                        <svg class="h-5 w-5 text-accent mr-2 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                        <div>
                                            <a href="<?php the_permalink(); ?>" class="font-medium text-gray-800 hover:text-primary hover:underline"><?php the_title(); ?></a>
                                            <?php if ( has_excerpt() ) : ?>
                                                <div class="text-sm text-gray-600 mt-1"><?php the_excerpt(); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </li>
                                <?php endwhile; wp_reset_postdata(); endif; ?>
                            </ul>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>No professional activities have been added yet.</p>
            <?php endif; ?>
        </div>
        <?php
    }
}