<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
function hugeit_contact_drawFreeBanner( $freeText = 'no' ) {
	$path_site2 = plugins_url( "../images", __FILE__ );
	?>
	<style>
		.free_version_banner {
			background: url('<?php echo $path_site2; ?>/wp_banner_bg.png');
            background-size: cover;
		}
	</style>
	<div class="free_version_banner" >
		<img class="manual_icon" src="<?php echo $path_site2; ?>/forms_icon.png" alt="user manual" />
		<p class="usermanual_text"><?php _e('Huge IT Forms','hugeit_contact');?></p>
		<a class="get_full_version" href="http://huge-it.com/forms/" target="_blank">
            <?php _e('GO PRO','hugeit_contact');?>
        </a>
		<a href="http://huge-it.com" target="_blank"><img class="huge_it_logo" src="<?php echo $path_site2; ?>/huge-it_logo.png"/></a>
		<div style="clear: both;"></div>
		<div class="hg_social_link_buttons">
			<a target="_blank" class="fb" href="https://www.facebook.com/hugeit/"><i class="fa fa-facebook" aria-hidden="true"></i></a>
			<a target="_blank" class="twitter"  href="https://twitter.com/HugeITcom"><i class="fa fa-twitter" aria-hidden="true"></i></a>
			<a target="_blank" class="gplus" href="https://plus.google.com/111845940220835549549"><i class="fa fa-google-plus" aria-hidden="true"></i></a>
			<a target="_blank" class="yt"  href="https://www.youtube.com/channel/UCueCH_ulkgQZhSuc0L5rS5Q"><i class="fa fa-youtube-play" aria-hidden="true"></i></a>
		</div>
		<div class="hg_view_plugins_block">
            <ul>
                <li>
                    <a target="_blank"  href="https://wordpress.org/support/plugin/forms-contact/reviews">
                        <?php _e('Rate Us','hugeit_contact');?>
                    </a>
                </li>
                <li>
                    <a target="_blank"  href="http://huge-it.com/wordpress-forms-demo-1-delivery-form/">
                        <?php _e('Demo','hugeit_contact');?>
                    </a>
                </li>
                <li>
                    <a target="_blank"  href="http://huge-it.com/wordpress-forms-faq/">
                        <?php _e('Help','hugeit_contact');?>
                    </a>
                    <ul>
                        <li>
                            <a target="_blank"  href="http://huge-it.com/contact-us/">
                                <?php _e('Contact Us','hugeit_contact');?>
                            </a>
                        </li>
                        <li>
                            <a target="_blank"  href="https://huge-it.com/wordpress-forms-user-manual/">
                                <?php _e('User Manual','hugeit_contact');?>
                            </a>
                        </li>
                        <li>
                            <a target="_blank"  href="http://huge-it.com/wordpress-forms-faq/">
                                <?php _e('FAQ','hugeit_contact');?>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
            <div  class="description_text"><p><?php _e('Click Go Pro to activate all additional customization options.','hugeit_contact');?></p></div>
        </div>
		<div style="clear: both;"></div>
	</div>
<?php
}