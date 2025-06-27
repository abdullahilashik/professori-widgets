<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Past_Courses_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'past_courses';
    }

    public function get_title() {
        return esc_html__( 'Past Courses List', 'tpw' );
    }

    public function get_icon() {
        return 'eicon-archive-posts';
    }

    public function get_categories() {
        return [ 'basic' ];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__( 'Settings', 'tpw' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'main_title',
            [
                'label' => esc_html__( 'Title', 'tpw' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Past Courses', 'tpw' ),
            ]
        );

        $this->add_control(
            'not_found_message',
            [
                'label' => esc_html__( 'Not Found Message', 'tpw' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'No past courses found in this category.', 'tpw' ),
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        // Step 1: Get the IDs of all "current" courses to exclude them.
        $current_courses_query = new \WP_Query([
            'post_type' => 'course',
            'posts_per_page' => -1,
            'meta_key' => '_is_current_course',
            'meta_value' => 'yes',
            'fields' => 'ids' // Very efficient, just gets post IDs.
        ]);
        $current_course_ids = $current_courses_query->posts;

        // Step 2: Get all the 'Course Level' terms (e.g., Undergraduate, Graduate)
        $course_levels = get_terms(['taxonomy' => 'course_level', 'hide_empty' => true]);
        ?>

        <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
            <h2 class="text-2xl font-bold text-primary mb-6 pb-2 border-b border-gray-200">
                <?php echo esc_html( $settings['main_title'] ); ?>
            </h2>

            <?php if ( ! empty($course_levels) ) : ?>
                <?php foreach ( $course_levels as $level ) : ?>
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4"><?php echo esc_html( $level->name ); ?></h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course Code</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course Title</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Semester</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Materials</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php
                                    // Step 3: For each level, query for past courses.
                                    $past_courses_query = new \WP_Query([
                                        'post_type' => 'course',
                                        'posts_per_page' => -1,
                                        // 'post__not_in' => $current_course_ids, // The crucial exclusion logic
                                        'tax_query' => [
                                            [
                                                'taxonomy' => 'course_level',
                                                'field' => 'term_id',
                                                'terms' => $level->term_id,
                                            ]
                                        ]
                                    ]);

                                    if ( $past_courses_query->have_posts() ) :
                                        while ( $past_courses_query->have_posts() ) : $past_courses_query->the_post();
                                            $course_code = get_post_meta( get_the_ID(), '_course_code', true );
                                            $semester = get_post_meta( get_the_ID(), '_course_semester', true );
                                    ?>
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo esc_html( $course_code ); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php the_title(); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo esc_html( $semester ); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-accent"><a href="<?php the_permalink(); ?>" class="hover:underline">View</a></td>
                                    </tr>
                                    <?php
                                        endwhile;
                                        wp_reset_postdata();
                                    else :
                                    ?>
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500"><?php echo esc_html($settings['not_found_message']); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                 <p>No course categories found.</p>
            <?php endif; ?>
        </div>
        <?php
    }
}