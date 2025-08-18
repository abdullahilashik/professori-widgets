<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Research_Group_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'research_group';
    }

    public function get_title() {
        return esc_html__( 'Research Group', 'tpw' );
    }

    public function get_icon() {
        return 'eicon-users';
    }

    public function get_categories() {
        return [ 'basic' ];
    }

    protected function _register_controls() {

        // --- Main Section Title ---
        $this->start_controls_section( 'section_main_title', [ 'label' => esc_html__( 'Main Title', 'tpw' ) ] );
        $this->add_control( 'main_title', [ 'label' => esc_html__( 'Title', 'tpw' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => esc_html__( 'Research Group', 'tpw' ) ]);
        $this->end_controls_section();

        // --- PhD Students Repeater ---
        $this->start_controls_section( 'section_phd_students', [ 'label' => esc_html__( 'PhD Students', 'tpw' ) ] );
        $this->add_control( 'phd_title', [ 'label' => esc_html__( 'PhD Section Title', 'tpw' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => esc_html__( 'PhD Students', 'tpw' ) ]);

        $repeater_phd = new \Elementor\Repeater();
        $repeater_phd->add_control( 'student_photo', [ 'label' => esc_html__( 'Photo', 'tpw' ), 'type' => \Elementor\Controls_Manager::MEDIA, 'default' => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ] ]);
        $repeater_phd->add_control( 'student_name', [ 'label' => esc_html__( 'Name', 'tpw' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Student Name' ]);
        $repeater_phd->add_control( 'student_topic', [ 'label' => esc_html__( 'Research Topic', 'tpw' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Topic: ...' ]);
        $repeater_phd->add_control( 'student_since', [ 'label' => esc_html__( 'Start Year', 'tpw' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Since: 2021' ]);
        $repeater_phd->add_control( 'student_link', [ 'label' => esc_html__( 'Profile Link (Optional)', 'tpw' ), 'type' => \Elementor\Controls_Manager::URL, 'placeholder' => esc_html__( 'https://example.com', 'tpw' ), 'dynamic' => [ 'active' => true ] ]);
        
        $this->add_control( 'phd_students_list', [ 'label' => esc_html__( 'PhD Student List', 'tpw' ), 'type' => \Elementor\Controls_Manager::REPEATER, 'fields' => $repeater_phd->get_controls(), 'default' => [ [ 'student_name' => 'Md. Rahman Ali', 'student_topic' => 'Topic: Stability in Renewable-rich Grids', 'student_since' => 'Since: 2021' ], [ 'student_name' => 'Fatima Jahan', 'student_topic' => 'Topic: Smart Grid Optimization', 'student_since' => 'Since: 2022' ], ], 'title_field' => '{{{ student_name }}}' ]);
        $this->end_controls_section();
        
        // --- MSc Students Repeater ---
        $this->start_controls_section( 'section_msc_students', [ 'label' => esc_html__( 'MSc Students', 'tpw' ) ] );
        $this->add_control( 'msc_title', [ 'label' => esc_html__( 'MSc Section Title', 'tpw' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => esc_html__( 'MSc Students', 'tpw' ) ]);

        $repeater_msc = new \Elementor\Repeater();
        $repeater_msc->add_control( 'student_photo', [ 'label' => esc_html__( 'Photo', 'tpw' ), 'type' => \Elementor\Controls_Manager::MEDIA, 'default' => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ] ]);
        $repeater_msc->add_control( 'student_name', [ 'label' => esc_html__( 'Name', 'tpw' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Student Name' ]);
        $repeater_msc->add_control( 'student_topic', [ 'label' => esc_html__( 'Research Topic', 'tpw' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Topic: ...' ]);
        $repeater_msc->add_control( 'student_since', [ 'label' => esc_html__( 'Start Year', 'tpw' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Since: 2023' ]);
        $repeater_msc->add_control( 'student_link', [ 'label' => esc_html__( 'Profile Link (Optional)', 'tpw' ), 'type' => \Elementor\Controls_Manager::URL, 'placeholder' => esc_html__( 'https://example.com', 'tpw' ), 'dynamic' => [ 'active' => true ] ]);

        $this->add_control( 'msc_students_list', [ 'label' => esc_html__( 'MSc Student List', 'tpw' ), 'type' => \Elementor\Controls_Manager::REPEATER, 'fields' => $repeater_msc->get_controls(), 'default' => [ [ 'student_name' => 'Ahmed Hossain', 'student_topic' => 'Topic: Energy Storage Systems', 'student_since' => 'Since: 2023' ], ], 'title_field' => '{{{ student_name }}}' ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
            <h2 class="text-2xl font-bold text-primary mb-6 pb-2 border-b border-gray-200"><?php echo esc_html( $settings['main_title'] ); ?></h2>
            
            <?php if ( ! empty( $settings['phd_students_list'] ) ) : ?>
            <div class="mb-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-4"><?php echo esc_html( $settings['phd_title'] ); ?></h3>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ( $settings['phd_students_list'] as $item ) : ?>
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-16 w-16 rounded-full bg-gray-200 overflow-hidden mr-4">
                            <?php if ( ! empty( $item['student_photo']['url'] ) ) : ?><img src="<?php echo esc_url( $item['student_photo']['url'] ); ?>" alt="<?php echo esc_attr( $item['student_name'] ); ?>" class="h-full w-full object-cover"><?php endif; ?>
                        </div>
                        <div>
                            <h4 class="font-medium">
                                <?php
                                if ( ! empty( $item['student_link']['url'] ) ) {
                                    $this->add_link_attributes( 'link_phd_' . $item['_id'], $item['student_link'] );
                                    ?><a <?php echo $this->get_render_attribute_string( 'link_phd_' . $item['_id'] ); ?>><?php echo esc_html( $item['student_name'] ); ?></a><?php
                                } else {
                                    echo esc_html( $item['student_name'] );
                                }
                                ?>
                            </h4>
                            <p class="text-sm text-gray-600"><?php echo esc_html( $item['student_topic'] ); ?></p>
                            <p class="text-xs text-gray-500"><?php echo esc_html( $item['student_since'] ); ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if ( ! empty( $settings['msc_students_list'] ) ) : ?>
            <div>
                <h3 class="text-xl font-semibold text-gray-800 mb-4"><?php echo esc_html( $settings['msc_title'] ); ?></h3>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ( $settings['msc_students_list'] as $item ) : ?>
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-16 w-16 rounded-full bg-gray-200 overflow-hidden mr-4">
                            <?php if ( ! empty( $item['student_photo']['url'] ) ) : ?><img src="<?php echo esc_url( $item['student_photo']['url'] ); ?>" alt="<?php echo esc_attr( $item['student_name'] ); ?>" class="h-full w-full object-cover"><?php endif; ?>
                        </div>
                        <div>
                            <h4 class="font-medium">
                                <?php
                                if ( ! empty( $item['student_link']['url'] ) ) {
                                    $this->add_link_attributes( 'link_msc_' . $item['_id'], $item['student_link'] );
                                    ?><a <?php echo $this->get_render_attribute_string( 'link_msc_' . $item['_id'] ); ?>><?php echo esc_html( $item['student_name'] ); ?></a><?php
                                } else {
                                    echo esc_html( $item['student_name'] );
                                }
                                ?>
                            </h4>
                            <p class="text-sm text-gray-600"><?php echo esc_html( $item['student_topic'] ); ?></p>
                            <p class="text-xs text-gray-500"><?php echo esc_html( $item['student_since'] ); ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php
    }
}