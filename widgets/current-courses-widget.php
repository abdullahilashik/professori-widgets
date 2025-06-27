<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Current_Courses_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'current_courses';
    }

    public function get_title() {
        return esc_html__( 'Current Courses List', 'tpw' );
    }

    public function get_icon() {
        return 'eicon-post-list';
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
                'default' => esc_html__( 'Current Courses', 'tpw' ),
            ]
        );

        $this->add_control(
            'not_found_message',
            [
                'label' => esc_html__( 'Not Found Message', 'tpw' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'No current courses are listed at this time.', 'tpw' ),
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $args = array(
            'post_type'      => 'course',
            'posts_per_page' => -1,
            'meta_query'     => array(
                array(
                    'key'     => '_is_current_course',
                    'value'   => 'yes',
                    'compare' => '=',
                ),
            ),
        );
        $courses_query = new \WP_Query( $args );
        ?>

        <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
            <h2 class="text-2xl font-bold text-primary mb-6 pb-2 border-b border-gray-200">
                <?php echo esc_html( $settings['main_title'] ); ?>
            </h2>

            <?php if ( $courses_query->have_posts() ) : ?>
                <div class="grid md:grid-cols-2 gap-6">
                    <?php while ( $courses_query->have_posts() ) : $courses_query->the_post(); 
                        $course_code = get_post_meta( get_the_ID(), '_course_code', true );
                        $semester = get_post_meta( get_the_ID(), '_course_semester', true );
                        $levels = get_the_terms( get_the_ID(), 'course_level' );
                        $level_name = ! empty( $levels ) ? esc_html( $levels[0]->name ) : 'Course';
                    ?>
                        <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition">
                            <div class="bg-primary text-white px-4 py-3">
                                <h3 class="font-bold text-lg"><?php echo esc_html( $course_code ); ?>: <?php the_title(); ?></h3>
                                <p class="text-accent-200 text-sm"><?php echo $level_name; ?></p>
                            </div>
                            <div class="p-4">
                                <div class="text-gray-700 mb-3"><?php echo get_the_excerpt(); ?></div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="bg-gray-100 px-2 py-1 rounded text-gray-700"><?php echo esc_html( $semester ); ?></span>
                                    <a href="<?php the_permalink(); ?>" class="text-accent hover:underline">Course Materials →</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <p><?php echo esc_html( $settings['not_found_message'] ); ?></p>
            <?php endif; ?>
        </div>
        <?php
    }
}