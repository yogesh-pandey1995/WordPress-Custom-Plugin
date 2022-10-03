<?php

/**
 * Plugin Name: YP Custom Plugin
 * Plugin URI: #
 * Description: this is our custom plugin.
 * Version: 1.0.0
 * Author: Yogesh Pandey
 * Author URI: #
 * Text Domain: yp-custom-plugin
 **/

if (!defined('ABSPATH')) {
    header('Location: /');
    die;
}

if (!defined('YP_PLUGIN_VERSION')) {
    define('YP_PLUGIN_VERSION', '1.0.0');
}

if (!defined('YP_PLUGIN_DIR_PATH')) {
    define('YP_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__));
}

if (!defined('YP_PLUGIN_DIR_URL')) {
    define('YP_PLUGIN_DIR_URL', plugin_dir_url(__FILE__));
}

if (!function_exists('yp_plugin_scripts')) {
    function yp_plugin_style_scripts()
    {
        wp_enqueue_style('p_style', YP_PLUGIN_DIR_URL . 'assets/css/plugin_style.css', array(), YP_PLUGIN_VERSION, false);

        wp_enqueue_script('p_script', YP_PLUGIN_DIR_URL . 'assets/js/plugin_function.js', array(), YP_PLUGIN_VERSION, true);
    }
    add_action('wp_enqueue_scripts', 'yp_plugin_style_scripts');
}

/**--- Register Activation Hook ---**/
function yp_plugin_activation()
{
    require_once YP_PLUGIN_DIR_PATH . 'inc/register_activation_hook.php';
}
register_activation_hook(__FILE__, 'yp_plugin_activation');

/**--- Register Deactivation Hook ---**/
function yp_plugin_deactivation()
{
    require_once YP_PLUGIN_DIR_PATH . 'inc/register_deactivation_hook.php';
}
register_deactivation_hook(__FILE__, 'yp_plugin_deactivation');



/** Shortcode **/
function yp_sc_function()
{
    require_once YP_PLUGIN_DIR_PATH . 'inc/shortcode.php';
}
add_shortcode('yp_shortcode', 'yp_sc_function');






function yp_menu_function()
{
    /*** Select Query ***/
    global $wpdb, $table_prefix;
    $table_name = $table_prefix . 'employee';

    if (isset($_GET['search_form'])) {
        $select_query = "SELECT * FROM `$table_name` WHERE `name` LIKE '%" . $_GET['search_form'] . "%';";
    } else {
        $select_query = "SELECT * FROM `$table_name`";
    }

    $results = $wpdb->get_results($select_query);
    ob_start();

    /*** Delete Row ***/
    if (isset($_REQUEST['delete_id'])) {
        $del_id = $_REQUEST['delete_id'];
        $select_query = "DELETE FROM $table_name WHERE id='$del_id'";
        $results = $wpdb->get_results($select_query);
        // $wpdb->query("DELETE FROM $table_name WHERE id='$del_id'");

        echo "<script>location.replace('admin.php?page=yp_plugin_slug');</script>";
    }

    /*** Edit Row ***/
    if (isset($_REQUEST['edit_id'])) :
        $upt_id = $_REQUEST['edit_id']; ?>
        <script>
            location.replace('admin.php?page=add_new_slug&edit_id=' + <?= $upt_id ?>);
        </script>
    <?php
    endif;
    ?>

    <div class="wrap">
        <h2>YP All Records</h2>
        <div class="ypform">
            <form action="<?php echo admin_url('admin.php'); ?>" id="search_form_id">
                <input type="hidden" name="page" value="yp_plugin_menu">
                <input type="text" name="search_form" id="search_form" placeholder="Search">
                <input type="submit" value="search" name="search">
            </form>
        </div>
        <table class="wp-list-table widefat fixed striped table-view-list posts">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>NAME</th>
                    <th>EMAIL</th>
                    <th>MOBILE NO</th>
                    <th>DELETE ACTION</th>
                    <th>EDIT ACTION</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $data) : ?>
                    <tr>
                        <td><?php echo $data->id; ?></td>
                        <td><?php echo $data->name; ?></td>
                        <td><?php echo $data->email; ?></td>
                        <td><?php echo $data->m_number; ?></td>
                        <td><a class="btn" href="admin.php?page=yp_plugin_slug&delete_id=<?php echo $data->id; ?>">Delete</a></td>
                        <td><a class="btn" href="admin.php?page=yp_plugin_slug&edit_id=<?php echo $data->id; ?>">Edit</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php
    echo ob_get_clean();
}
function yp_submenu_function()
{
?>
    <div class="wrap">
        <h2>YP Add New</h2>
        <div class="yp_add_new">
            <?php
            /*** Get Edit Data ***/
            if (isset($_REQUEST['edit_id'])) {

                global $wpdb, $table_prefix;;
                $table_name = $table_prefix . 'employee';
                $id = $_REQUEST['edit_id'];
                $select_query = "SELECT * FROM $table_name WHERE id=$id";
                $results = $wpdb->get_results($select_query);

                foreach ($results as $data) {
                    $id = $data->id;
                    $name = $data->name;
                    $email = $data->email;
                    $m_number = $data->m_number;
                }
            }
            /*** Update Data ***/
            if (isset($_REQUEST['add_update'])) {
                $id = $_REQUEST['id'];
                $name = $_REQUEST['name'];
                $email = $_REQUEST['email'];
                $phone = $_REQUEST['phone'];

                global $wpdb, $table_prefix;;
                $table_name = $table_prefix . 'employee';
                $select_query = "UPDATE $table_name SET name='$name', email='$email', m_number='$phone' WHERE id='$id'";
                $results = $wpdb->get_results($select_query);
                // $results = $wpdb->query($select_query);

                echo "<script>location.replace('admin.php?page=yp_plugin_slug');</script>";
            }
            /*** Insert Data ***/
            if (isset($_REQUEST['add_submit'])) {
                $name = $_REQUEST['name'];
                $email = $_REQUEST['email'];
                $phone = $_REQUEST['phone'];

                global $wpdb, $table_prefix;
                $table_name = $table_prefix . 'employee';

                $data = array(
                    'name' => $name,
                    'email' => $email,
                    'm_number' => $phone,
                );
                $insert_data = $wpdb->insert($table_name, $data);

                if ($insert_data) {
                    $txt_value = "DATA INSERTED";
                } else {
                    $txt_value = "DATA NOT INSERTED";
                }
            }

            if (isset($_REQUEST['add_submit'])) {
                echo $txt_value;
            }
            ?>
            <form action="<?php echo admin_url('admin.php'); ?>" method="GET">
                <input type="hidden" name="page" value="add_new_slug">
                <input type="hidden" name="id" value="<?php if (isset($_REQUEST['edit_id'])) echo $id; ?>">

                <input type="text" id="name" name="name" placeholder="Name" value="<?php if (isset($_REQUEST['edit_id'])) echo $name; ?>" required><br>
                <input type="email" id="email" name="email" placeholder="Email" value="<?php if (isset($_REQUEST['edit_id'])) echo $email; ?>" required><br>
                <input type="text" id="phone" name="phone" placeholder="Phone No" value="<?php if (isset($_REQUEST['edit_id'])) echo $m_number; ?>" required><br>

                <?php if (isset($_REQUEST['edit_id'])) :
                    echo "<input type='submit' name='add_update' value='Update'>";
                else :
                    echo "<input type='submit' name='add_submit' value='Submit'>";
                endif; ?>
            </form>
        </div>
    </div>
<?php
}

function yp_plugin_menu_function()
{
    add_menu_page('YP Custom Plugin', 'YP Custom Plugin', 'manage_options', 'yp_plugin_slug', 'yp_menu_function', 'dashicons-admin-home', '66');

    add_submenu_page('yp_plugin_slug', 'All Records', 'All Records', 'manage_options', 'yp_plugin_slug', 'yp_menu_function');
    add_submenu_page('yp_plugin_slug', 'Add New Records', 'Add New Records', 'manage_options', 'add_new_slug', 'yp_submenu_function');


    //     add_submenu_page('tools.php', 'Yogesh Custom Submenu', 'Yogesh Custom Submenu', 'manage_options', 'yp_slug_sub', 'function_submenu');
    //     add_dashboard_page() – index.php
    //     add_posts_page() – edit.php
    //     add_media_page() – upload.php
    //     add_pages_page() – edit.php?post_type=page
    //     add_comments_page() – edit-comments.php
    //     add_theme_page() – themes.php
    //     add_plugins_page() – plugins.php
    //     add_users_page() – users.php
    //     add_management_page() – tools.php
    //     add_options_page() – options-general.php
    //     add_options_page() – settings.php
    //     add_links_page() – link-manager.php – requires a plugin since WP 3.5+
    //     Custom Post Type – edit.php?post_type=wporg_post_type
    //     Network Admin – settings.php

    //     https://www.davidangulo.xyz/how-to-create-crud-operations-plugin-in-wordpress/

}
add_action('admin_menu', 'yp_plugin_menu_function');
