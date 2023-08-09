<?php

if (!defined('ABSPATH')) {
    exit;
}

function cpm_formidable_form_addon_enqueue_script()
{
    global $ver_num;
    $ver_num = mt_rand();
    wp_enqueue_script('cpm_filestack_script', 'https://static.filestackapi.com/filestack-js/3.x.x/filestack.min.js');
    wp_enqueue_script('cpm_custom_for_filestack_script', plugin_dir_url(__FILE__) . '/cpm_custom.js',array(), $ver_num, 'all');
    wp_enqueue_style('cpm_custom_for_filestack_css', plugin_dir_url(__FILE__) . '/cpm_custom.css',array(), $ver_num, 'all');
}
add_action('wp_enqueue_scripts', 'cpm_formidable_form_addon_enqueue_script');


add_action("admin_menu", "cpm_imdb_options_submenu");
if (!function_exists('cpm_imdb_options_submenu')) {
    function cpm_imdb_options_submenu()
    {
        add_submenu_page(
            'formidable',
            'Filestack API key',
            'Filestack API',
            'administrator',
            'filestack-addon-formidable-forms',
            'cpm_api_settings_page'
        );
    }
}

function cpm_api_settings_page()
{
    if (isset($_POST['form_submitted'])) {
        if (!isset($_POST['filestack_api_key'])) {
            return;
        }
        // Sanitize user input.
        $my_data = sanitize_text_field($_POST['filestack_api_key']);

        $submit = update_option('_filestack_api_key', $my_data);
        if ($submit) {
            _e( 'API Key was successfully saved.','formidable-addon' );
        } else {
            _e( 'Not submitted try again.','formidable-addon' );
        }
    } ?>

    <h3><?php _e('Filestack API key', 'formidable-addon'); ?></h3>

    <div class="filestack-api-form">
        <form method="POST">
            <label for="fname">
                <p><?php _e('Filestack Api key', 'formidable-addon'); ?>
            </label>
            <input type="text" id="filestack_api" name="filestack_api_key" placeholder="file stack api here..." value="<?php echo !empty(get_option('_filestack_api_key')) ? get_option('_filestack_api_key') : ''; ?> ">

            <input type="submit" value="Save" name="form_submitted">
        </form>
    </div>

<?php }



add_filter('frm_available_fields', 'add_basic_field');
function add_basic_field($fields)
{
    $fields['button'] = array(
        'name' => 'Filestack Uploads',
        'icon' => 'frm_icon_font frm_upload_icon', // Set the class for a custom icon here.
    );
    return $fields;
}


add_filter('frm_before_field_created', 'set_my_field_defaults');
function set_my_field_defaults($field_data)
{
    if ($field_data['type'] == 'button') {
        $field_data['name'] = 'Upload Files';

        $defaults = array(
            'size' => 400, 'max' => 150,
            'label1' => 'Draw It',
        );
        foreach ($defaults as $k => $v) {
            $field_data['field_options'][$k] = $v;
        }
    }
    return $field_data;
}



add_action('frm_form_fields', 'show_my_front_field', 10, 3);
function show_my_front_field($field, $field_name, $atts)
{
    
    if ($field['type'] != 'button') {
        return;
    }

    $field['value'] = stripslashes_deep($field['value']);
    $api_key_ = get_option('_filestack_api_key');
    $api_key_ .= rand(10, 100);
?>
    <div>
        <div id="cpm_filestack_button">
            <input type="button" name="file" value="<?php _e('Selecione o arquivo', 'formidable-addon'); ?>" onclick="cpm_upload_image('<?php echo $api_key_; ?>','<?php echo $atts['html_id']; ?>','<?php echo $field_name; ?>')" class="cpm_filestack_image">
            <input type="hidden" name="<?php echo esc_attr($field_name); ?>" value="" class="name_class" id="<?php echo $field_name; ?>" readonly>
        </div>
        <div id="cpm_formidable_img_<?php echo $atts['html_id']; ?>" class="cpm_formidable_form_img"> </div>
    </div>

<?php
}
