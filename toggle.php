<?php
/**
 * Plugin Name: Top Bar Toggle
 * Plugin URI:  http://www.logicrays.com/
 * Description: Create Simple Toggle for Information like Mail-id, Contact, Time For Mobile, Use shortcode [toogle] in header.php
 * Version:     1.0
 * Author:      Logicrays Inc.
 */
 
define( 'INFO_TOGGLE_PLUGIN_PATH', plugins_url( '', __FILE__ ) );
 
function info_toogle_scripts() {
	if (!is_admin()) {
		wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' );
	    wp_enqueue_style( 'style-css', INFO_TOGGLE_PLUGIN_PATH.'/assets/css/style.css' );
	    wp_enqueue_style( 'merger-css', INFO_TOGGLE_PLUGIN_PATH.'/assets/css/merger.css' );
		wp_enqueue_script( 'jquery-merger', INFO_TOGGLE_PLUGIN_PATH.'/assets/js/jquery.merger.js' );
	}
}
add_action( 'wp_enqueue_scripts','info_toogle_scripts');
 
add_action('admin_menu', 'info_toggle');
function info_toggle() {
    add_menu_page('Top Bar Toggle Settings',
            'Top Bar Toggle',
            'manage_options',
            'toggle',
            info_toggle_settings
    );
}
function info_toggle_settings(){
?>
<div class="wrap">
<div class="icon32" id="icon-options-general"><br>
</div>
<h2>Top Bar Toggle Options</h2>
<form action="options.php" method="post">
<?php
settings_fields("section");
?>
<style>
.shortcode {font-size: 16px;}</style>
<p class="shortcode"><strong>Use Shortcode: [toogle] in header.php</strong></p>
<?php
do_settings_sections("theme-options");
submit_button();
?>
</form>
</div>
<?php
}
function info_toggle_fields()
{
	add_settings_section("section", "All Settings", null, "theme-options");	
	add_settings_field("info_email", "Email", "info_toggle_email_element", "theme-options", "section");
	add_settings_field("info_phone", "Phone", "info_toggle_phone_element", "theme-options", "section");
	add_settings_field("info_clock", "Clock", "info_toggle_clock_element", "theme-options", "section");
	
	add_settings_field("add_menu_to_replace", "Add Info to Replace", "info_toggle_menu_to_replace_element", "theme-options", "section");
	add_settings_field("pixel_width", "Pixel Width to Switch to MergerNav", "info_toggle_pixel_width_element", "theme-options", "section");
	add_settings_field("menu_position", "Info Position", "info_toggle_menu_position_element", "theme-options", "section");
	add_settings_field("menu_label", "Info Label Name", "info_toggle_menu_label_element", "theme-options", "section");

    register_setting("section", "info_email");
	register_setting("section", "info_phone");
	register_setting("section", "info_clock");
	
	register_setting("section", "add_menu_to_replace");
	register_setting("section", "pixel_width");
	register_setting("section", "menu_position");
	register_setting("section", "menu_label");
}

add_action("admin_init", "info_toggle_fields");

function info_toggle_email_element()
{
?>
<input type="text" name="info_email" id="info_email" value="<?php echo get_option('info_email'); ?>" />
<?php
}
function info_toggle_phone_element()
{
?>
<input type="text" name="info_phone" id="info_phone" value="<?php echo get_option('info_phone'); ?>" />
<?php
}
function info_toggle_clock_element()
{
?>
<input type="text" name="info_clock" id="info_clock" value="<?php echo get_option('info_clock'); ?>" />
<?php
}
function info_toggle_menu_to_replace_element()
{
?>
<input type="text" name="add_menu_to_replace" id="add_menu_to_replace" value="<?php echo get_option('add_menu_to_replace'); ?>" />
<?php
}
function info_toggle_pixel_width_element()
{
?>
<input type="number" name="pixel_width" id="pixel_width" value="<?php echo get_option('pixel_width'); ?>" />
<?php
}
function info_toggle_menu_position_element()
{
?>
<input type="text" name="menu_position" id="menu_position" value="<?php echo get_option('menu_position'); ?>" />
<?php
}
function info_toggle_menu_label_element()
{
?>
<input type="text" name="menu_label" id="menu_label" value="<?php echo get_option('menu_label'); ?>" />
<?php
}
function info_toogle_function($atts, $content = null){
ob_start();
?>	
<ul id="merger">
  <?php if(get_option('info_email')){ ?>
  <li><a href="mailto:<?php echo get_option('info_email'); ?>"><i class="fa fa-envelope"></i><?php echo get_option('info_email'); ?></a></li>
  <?php } if(get_option('info_phone')){ ?>
  <li><a href="tel:<?php echo get_option('info_phone'); ?>"><i class="fa fa-phone"></i><?php echo get_option('info_phone'); ?></a></li>
  <?php } if(get_option('info_clock')){ ?>
  <li><a href="#"><i class="fa fa-clock-o"></i><?php echo get_option('info_clock'); ?></a></li>
  <?php } ?>
</ul>
<script>
jQuery(document).ready(function(){
	jQuery('#merger').merge(
	{
		label: '<?php if(get_option('menu_label')){echo get_option('menu_label');}else{echo 'Info'; } ?>',
		prependTo:'<?php if(get_option('add_menu_to_replace')){echo get_option('add_menu_to_replace');}else{echo '#merger'; }?>',
		appendTo: '<?php if(get_option('menu_position')){echo get_option('menu_position');}else{echo 'body'; }; ?>',
	}
	);
	jQuery( ".merger_menu" ).addClass( "no-position" );
	<?php if(get_option('menu_position')){?>
	jQuery( ".merger_menu" ).removeClass( "no-position" );
	<?php }?>
});
</script>
<?php
$pixel_width = get_option('pixel_width');
$merger_custom_css = "
@media screen and (max-width: {$pixel_width}px) {
#merger {
 display:none;
}
.merger_menu {
 display:block;
}	
}";
?>
<style>
<?php echo $merger_custom_css; ?>
</style>
<?php
return ob_get_clean();
}
add_shortcode( 'toogle', 'info_toogle_function' );