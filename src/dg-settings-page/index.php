<?php 
/**
 * Plugin Name:       Custom Settings Page
 * Plugin URI:        https://dgergo.com/
 * Description:       Create a custom settings page for your WordPress site.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      8.0
 * Author:            Gergo Docsa
 * Author URI:        https://dgergo.com/
*/

function dg_create_admin_menu() {
    // Add the new admin menu to the navigation sidebar in the WordPress admin panel.
    // Documentation for the function: https://developer.wordpress.org/reference/functions/add_menu_page/
	add_menu_page(
        $page_title = 'My settings', 
        $menu_title = 'My settings', 
        $capability = 'manage_options',
        $menu_slug = 'dg_my_settings', 
        $function = 'dg_my_settings_function', 
        $icon_url = 'dashicons-admin-settings',
        $position = 2,
    );
}
add_action( 'admin_menu', 'dg_create_admin_menu' );

function dg_profile_setting_inputs() {
    // Declare input fields used for the settings page. With this main function it becomes easy to maintain all your custom settings in one place without code duplication.
    $setting_inputs = array(
        'dg_my_name' => array(
            'label' => 'My name',
            'help_text' => 'This name can be displayed on the website. Option key is "dg_my_name".',
            'type' => 'text', 
        ),
        'dg_my_linkedin_url' => array(
            'label' => 'URL of my LinkedIn profile',
            'help_text' => 'This link can be shown on the website. Option key is "dg_my_linkedin_url".',
            'type' => 'text', 
        ),
    );
    return $setting_inputs;
}

function register_dg_settings() {
	// Register our settings to the core.
    // Documentation for the function: https://developer.wordpress.org/reference/functions/register_setting/
    $setting_inputs = dg_profile_setting_inputs();
    foreach($setting_inputs as $setting_key => $setting_data) {
        register_setting( 'dg-profile-settings', $setting_key );
    }
}
add_action( 'admin_init', 'register_dg_settings' );


function dg_my_settings_function() {
	?>
	<div class="wrap">
		<h1>My settings</h1>
        <p>Use this template to output the settings you declared above.</p>
		<p>Each setting can be retrieved by the <code>get_option()</code> function. Sample code:</p>
		<pre style="background: #fff;">$linkedin_url = get_option('dg_my_linkedin_url');</pre>
        <form method="post" action="options.php">
            <?php 
            settings_fields( 'dg-profile-settings' );  
            do_settings_sections( 'dg-profile-settings' );
            $setting_inputs = dg_profile_setting_inputs();
            ?>
            <table class="form-table">
                <?php 
                foreach($setting_inputs as $setting_key => $setting_data) { ?>
                    <tr valign="top">
                        <th scope="row"><?= $setting_data['label'] ;?></th>
                        <td><?php 
                        if($setting_data['type'] === 'text') { ?>
                            <input type="text" name="<?= $setting_key; ?>" value="<?php echo esc_attr( get_option($setting_key) ); ?>" />
                        <?php 
                        }
                        if($setting_data['type'] === 'checkbox') { ?>
                            <input type="checkbox" name="<?= $setting_key; ?>" value="1" <?php if(intval(get_option($setting_key)) === 1) { ?>checked="checked"<?php } ?>/>
                        <?php 
                        }
                        if($setting_data['type'] === 'textarea') { ?>
                            <textarea name="<?= $setting_key; ?>" rows="10" cols="50"><?php echo esc_attr( get_option($setting_key) ); ?></textarea>
                        <?php 
                        }
                        if(!empty($setting_data['help_text'])) { ?><p style="display:block"><small><?= $setting_data['help_text']; ?></small></p><?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                </table>
            <?php 
            submit_button('Save changes'); 
            ?>
        </form>   
	</div>
	<?php
	return;
}