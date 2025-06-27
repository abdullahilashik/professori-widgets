<?php
/**
 * Plugin Name:       Professori Widgets
 * Description:       Adds a custom Professori Widgets to Elementor.
 * Version:           1.0.0
 * Author:            Abdullahil Ashik Md Arefin
 * Author URI:        https://yourwebsite.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       tpw
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Register the custom widget
 */
function register_teaching_philosophy_widget( $widgets_manager ) {
    require_once( __DIR__ . '/widgets/philosophy-widget.php' );
    $widgets_manager->register( new \Teaching_Philosophy_Widget() );

    // Load and register the NEW Resources widget
    require_once( __DIR__ . '/widgets/resources-widget.php' );
    $widgets_manager->register( new \Teaching_Resources_Widget() );

    // Load and register the NEW Current Courses widget
    require_once( __DIR__ . '/widgets/current-courses-widget.php' );
    $widgets_manager->register( new \Current_Courses_Widget() );

     // Load and register the NEW Past Courses widget
    require_once( __DIR__ . '/widgets/past-courses-widget.php' );
    $widgets_manager->register( new \Past_Courses_Widget() );

    // Load and register the NEW Research Group widget
    require_once( __DIR__ . '/widgets/research-group-widget.php' );
    $widgets_manager->register( new \Research_Group_Widget() );

    // Load and register the NEW Collaborations widget
    require_once( __DIR__ . '/widgets/collaborations-widget.php' );
    $widgets_manager->register( new \Collaborations_Widget() );

     // Load and register the NEW Research Projects widget
    require_once( __DIR__ . '/widgets/research-projects-widget.php' );
    $widgets_manager->register( new \Research_Projects_Widget() );

    // Load and register the NEW Current Research Projects widget
    require_once( __DIR__ . '/widgets/current-research-projects-widget.php' );
    $widgets_manager->register( new \Current_Research_Projects_Widget() );

    // Load and register the NEW Research Interests widget
    require_once( __DIR__ . '/widgets/research-interests-widget.php' );
    $widgets_manager->register( new \Research_Interests_Widget() );

    // Load and register the NEW Research Overview widget
    require_once( __DIR__ . '/widgets/research-overview-widget.php' );
    $widgets_manager->register( new \Research_Overview_Widget() );

    require_once( __DIR__ . '/widgets/cpt-current-projects-widget.php' );
    $widgets_manager->register( new \CPT_Current_Projects_Widget() );

    require_once( __DIR__ . '/widgets/cpt-completed-projects-widget.php' );
    $widgets_manager->register( new \CPT_Completed_Projects_Widget() );

    // Load and register the NEW Projects Overview widget
    require_once( __DIR__ . '/widgets/projects-overview-widget.php' );
    $widgets_manager->register( new \Projects_Overview_Widget() );

    // Load and register the NEW Project Partners widget
    require_once( __DIR__ . '/widgets/project-partners-widget.php' );
    $widgets_manager->register( new \Project_Partners_Widget() );

    // --- NEW BIOGRAPHY WIDGETS ---
    require_once( __DIR__ . '/widgets/personal-statement-widget.php' );
    $widgets_manager->register( new \Personal_Statement_Widget() );

    require_once( __DIR__ . '/widgets/education-timeline-widget.php' );
    $widgets_manager->register( new \Education_Timeline_Widget() );

    require_once( __DIR__ . '/widgets/professional-experience-widget.php' );
    $widgets_manager->register( new \Professional_Experience_Widget() );
    
    require_once( __DIR__ . '/widgets/awards-honors-widget.php' );
    $widgets_manager->register( new \Awards_Honors_Widget() );

    require_once( __DIR__ . '/widgets/professional-activities-widget.php' );
    $widgets_manager->register( new \Professional_Activities_Widget() );

    // Load and register the NEW Profile Hero widget
    require_once( __DIR__ . '/widgets/profile-hero-widget.php' );
    $widgets_manager->register( new \Profile_Hero_Widget() );

    // Load and register the NEW Software Tools Overview widget
    require_once( __DIR__ . '/widgets/software-tools-overview-widget.php' );
    $widgets_manager->register( new \Software_Tools_Overview_Widget() );

    require_once( __DIR__ . '/widgets/software-tools-widget.php' );
    $widgets_manager->register( new \Software_Tools_Widget() );

    // new
    // Load and register the NEW Learning Resources Overview widget
    require_once( __DIR__ . '/widgets/learning-resources-overview-widget.php' );
    $widgets_manager->register( new \Learning_Resources_Overview_Widget() );
    
    require_once( __DIR__ . '/widgets/lecture-notes-widget.php' );
    $widgets_manager->register( new \Lecture_Notes_Widget() );
    
    require_once( __DIR__ . '/widgets/video-tutorials-widget.php' );
    $widgets_manager->register( new \Video_Tutorials_Widget() );
    
    require_once( __DIR__ . '/widgets/problem-sets-widget.php' );
    $widgets_manager->register( new \Problem_Sets_Widget() );
    
    require_once( __DIR__ . '/widgets/workshops-widget.php' );
    $widgets_manager->register( new \Workshops_Widget() );
    
  
}
add_action( 'elementor/widgets/register', 'register_teaching_philosophy_widget' );