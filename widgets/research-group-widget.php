<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Research_Group_Widget extends \Elementor\Widget_Base {

    public function get_name() { return 'research_group_members'; }
    public function get_title() { return esc_html__( 'CPT: Research Group Members', 'tpw' ); }
    public function get_icon() { return 'eicon-users'; }
    public function get_categories() { return [ 'basic' ]; }

    protected function render() {
        $member_statuses = get_terms([
            'taxonomy' => 'member_status',
            'hide_empty' => true,
            'orderby' => 'name',
            'order' => 'ASC',
        ]);

        if ( empty($member_statuses) || is_wp_error($member_statuses) ) {
            return;
        }
        ?>
        <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
            <?php foreach ($member_statuses as $status): ?>
                <div class="mb-8 last:mb-0">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4"><?php echo esc_html($status->name); ?></h3>
                    <?php
                    $members_query = new \WP_Query([
                        'post_type' => 'research_member',
                        'posts_per_page' => -1,
                        'orderby' => 'title',
                        'order' => 'ASC',
                        'tax_query' => [
                            ['taxonomy' => 'member_status', 'field' => 'term_id', 'terms' => $status->term_id],
                        ],
                    ]);
                    if ($members_query->have_posts()): ?>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php while ($members_query->have_posts()): $members_query->the_post(); ?>
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-12 w-12 rounded-full bg-gray-200 overflow-hidden mr-4">
                                <?php if (has_post_thumbnail()): ?>
                                    <?php the_post_thumbnail('thumbnail', ['class' => 'h-full w-full object-cover']); ?>
                                <?php else: ?>
                                    <!-- Placeholder icon -->
                                    <svg class="h-full w-full text-gray-400 p-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">...</svg>
                                <?php endif; ?>
                            </div>
                            <div>
                                <h4 class="font-medium"><?php the_title(); ?></h4>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <?php endif; wp_reset_postdata(); ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}