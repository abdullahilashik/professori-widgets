<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Problem_Sets_Widget extends \Elementor\Widget_Base {

    public function get_name() { return 'cpt_problem_sets'; }
    public function get_title() { return esc_html__( 'CPT: Problem Sets', 'tpw' ); }
    public function get_icon() { return 'eicon-table'; }
    public function get_categories() { return [ 'basic' ]; }

    protected function _register_controls() {
        $this->start_controls_section('content_section', ['label' => 'Settings']);
        $this->add_control('title', ['label' => 'Title', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Problem Sets & Solutions']);
        $this->add_control('posts_per_page', ['label' => 'Number of Sets to Show', 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 3]);
        $this->add_control('view_all_text', ['label' => '"View All" Button Text', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'View All Problem Sets']);
        $this->end_controls_section();
    }

    private function render_file_links($post_id) {
        $file_links_raw = get_post_meta($post_id, '_file_links', true);
        if (empty($file_links_raw)) return;
        $file_links = explode("\n", trim($file_links_raw));
        
        $links_html = [];
        foreach ($file_links as $link) {
            if (strpos($link, '|') !== false) {
                list($label, $url) = explode('|', $link, 2);
                $links_html[] = '<a href="'.esc_url(trim($url)).'" class="hover:underline" target="_blank">'.esc_html(trim($label)).'</a>';
            }
        }
        echo implode(' | ', $links_html);
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $query_args = [
            'post_type' => 'problem_set',
            'posts_per_page' => $settings['posts_per_page'],
            'orderby' => 'date',
            'order' => 'DESC'
        ];
        $problems_query = new \WP_Query($query_args);
        $total_posts = $problems_query->found_posts;
        $posts_shown = $problems_query->post_count;
        ?>
        <section class="mb-16">
            <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
                <h2 class="text-2xl font-bold text-primary mb-6"><?php echo esc_html($settings['title']); ?></h2>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Problem Set</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Topic</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Level</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Files</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if ($problems_query->have_posts()) : while ($problems_query->have_posts()) : $problems_query->the_post();
                                $post_id = get_the_ID();
                                
                                // ====================================================================
                                //  THE DEFINITIVE FIX:
                                //  1. Use get_the_title() for the ID, as instructed.
                                //  2. Correctly retrieve the '_problem_level' and '_problem_topic' meta.
                                // ====================================================================
                                $problem_set_id = get_the_title($post_id);
                                $topic          = get_post_meta($post_id, '_problem_topic', true);
                                $level          = get_post_meta($post_id, '_problem_level', true);

                            ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo esc_html($problem_set_id); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo esc_html($topic); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo esc_html($level); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-accent space-x-2">
                                    <?php $this->render_file_links($post_id); ?>
                                </td>
                            </tr>
                            <?php endwhile; endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php wp_reset_postdata(); ?>
                
                <?php if ($total_posts > $posts_shown): ?>
                <div class="text-center mt-6">
                    <a href="<?php echo get_post_type_archive_link('problem_set'); ?>" class="px-4 py-2 bg-gray-100 text-primary rounded-lg hover:bg-gray-200 transition">
                         <?php echo esc_html($settings['view_all_text']); ?> (<?php echo esc_html($total_posts - $posts_shown); ?> more)
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </section>
        <?php
    }
}