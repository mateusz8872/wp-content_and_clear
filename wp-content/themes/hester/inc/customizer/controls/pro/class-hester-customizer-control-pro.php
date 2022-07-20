<?php
if ( ! class_exists( 'Hester_Customizer_Control_Pro' ) ) :
class Hester_Customizer_Control_Pro extends Hester_Customizer_Control {

    /**
		 * The control type.
		 *
		 * @var string
		 */
		public $type = 'hester-pro';

    /**
    * Render the content on the theme customizer page
    */
    public function content_template() {
        ?>
            <div class="upsell-btn" style="text-align: center;">                 
                <a style="margin: 0 auto 5px;display: inline-block;" href="http://www.peregrine-themes.com/hester/?utm_medium=customizer&utm_source=button&utm_campaign=profeatures" target="blank" class="btn btn-success btn"><?php esc_html_e('Upgrade to Hester Pro','hester'); ?> </a>
            </div>
            <div class="">
                <img class="hester_img_responsive " src="<?php echo esc_url( get_template_directory_uri() ) .'/assets/images/hester_pro.png'?>">
            </div>         
            <div class="">
                <h3 style="margin-top:10px;margin-left: 20px;text-decoration:underline;color:#333;"><?php echo esc_html_e( 'Hester Pro - Features','hester'); ?></h3>
                <ul style="padding-top:10px">
                    <li class="upsell-hester"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('All starter sites included','hester'); ?> </li>
                    <li class="upsell-hester"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('Unlimited slider slides & settings','hester'); ?> </li>
                    <li class="upsell-hester"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('Unlimited info box & settings','hester'); ?> </li>
                    <li class="upsell-hester"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('Unlimited service box & settings','hester'); ?> </li>
                    <li class="upsell-hester"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('Unlimited features box & settings','hester'); ?> </li>
                    <li class="upsell-hester"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('Extra shop settings','hester'); ?> </li>
                    <li class="upsell-hester"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('Extra blog settings','hester'); ?> </li>
                    <li class="upsell-hester"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('Extra section with background settings','hester'); ?> </li>
                    <li class="upsell-hester"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('Pricing plan section','hester'); ?> </li>
                    <li class="upsell-hester"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('Team section','hester'); ?> </li>
                    <li class="upsell-hester"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('Testimonials section','hester'); ?> </li>
                    <li class="upsell-hester"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('Clients section','hester'); ?> </li>
                    <li class="upsell-hester"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('Call to action','hester'); ?> </li>
                    <li class="upsell-hester"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('About section','hester'); ?></li>
                    <li class="upsell-hester"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('Gallery section','hester'); ?> </li>
                    <li class="upsell-hester"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('Funfacts section','hester'); ?> </li>                        
                    <li class="upsell-hester"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('Steps to start section','hester'); ?> </li>                        
                    <li class="upsell-hester"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('Workflow section','hester'); ?> </li>
                    <li class="upsell-hester"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('Work history section','hester'); ?> </li>
                    <li class="upsell-hester"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('Blog Masonry Template','hester'); ?> </li>
                    <li class="upsell-hester"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('Careers Template','hester'); ?> </li>
                    <li class="upsell-hester"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('Contact Us Template','hester'); ?> </li>
                    <li class="upsell-hester"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('FAQ Template','hester'); ?> </li>
                    <li class="upsell-hester"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('Use all home sections on other custom pages','hester'); ?> </li>
                    <li class="upsell-hester"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('Coming Soon/Maintenance Mode Option','hester'); ?> </li>
                    <li class="upsell-hester"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('Design options in each home sections','hester'); ?> </li>
                    <li class="upsell-hester"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('Quick Support','hester'); ?> </li>
                    <li class="upsell-hester"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('And much more...','hester'); ?> </li>
                </ul>
            </div>
            <div class="upsell-btn upsell-btn-bottom" style="text-align: center;">                 
                <a style="margin: 0 auto 5px;display: inline-block;" href="http://www.peregrine-themes.com/hester/?utm_medium=customizer&utm_source=button&utm_campaign=profeatures" target="blank" class="btn btn-success btn"><?php esc_html_e('Upgrade to Hester Pro','hester'); ?> </a>
            </div>
           
            <p>
                <?php
                    printf( __( 'If you Like our Products , Please Rate us 5 star on %1$sWordPress.org%2$s.  We\'d really appreciate it! </br></br>  Thank You', 'hester' ), '<a target="" href="https://wordpress.org/support/view/theme-reviews/hester?filter=5">', '</a>' );
                ?>
            </p>
        <?php
    }
}
endif;
