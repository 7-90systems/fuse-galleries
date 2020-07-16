<?php
    /**
     *  Set up our administration options.
     */

    namespace Fuse\Plugin\Galleries;


    class Admin {

        /**
         *  Object constructor.
         */
        public function __construct () {
            add_action ('admin_menu', array ($this, 'adminMenu'));
        } // __construct ()




        /**
         *  Set up our administration menu options.
         */
        public function adminMenu () {
            add_options_page (__ ('Galleries', 'fuse'), __ ('Galleries', 'fuse'), 'manage_options', 'fuse_galleries', array ($this, 'settingsPage'));
        } // adminMenu ()




        /**
         *  Set up the galleries settings page.
         */
        public function settingsPage () {
            $message = '';

            // Hide internal post types
            $hidden_types = array (
                'attachment',
                'revision',
                'nav_menu_item',
                'customize_changeset',
                'oembed_cache',
                'user_request',
                'wp_block',
                'fuse_layouts',
                'custom_css'
            );

            $post_types = get_post_types (array (), 'objects');

            if (array_key_exists ('fuse_gallery_posttype', $_POST) && is_array ($_POST ['fuse_gallery_posttype'])) {
                foreach ($post_types as $type) {
                    if (in_array ($type->name, $_POST ['fuse_gallery_posttype'])) {
                        update_option ('fuse_gallery_type_'.$type->name, 'yes');
                    } // if ()
                    else {
                        delete_option ('fuse_gallery_type_'.$type->name);
                    } // else
                } // foreach ()

                update_option ('fuse_gallery_show', $_POST ['fuse_gallery_show']);

                $message = __ ('Settings updated', 'fuse');
            } // if ()
?>
    <div class="wrap">
        <h1><?php _e ('Fuse Page Gallery Settings', 'fuse'); ?></h1>

        <?php if (strlen ($message) > 0): ?>
            <div id="message" class="updated notice notice-success is-dismissible">
                <p><?php echo $message; ?></p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
            </div>
        <?php endif; ?>

        <form id="fuse_galleries_settings_form" name="fuse_galleries_settings_form" action="<?php echo esc_url (admin_url ('options-general.php?page=fuse_galleries')); ?>" method="post">

            <h3><?php _e ('Allow galleries on these post types:', 'fuse'); ?></h3>

            <ul>
                <?php foreach ($post_types as $pt): ?>
                    <?php
                        $checked = '';

                        if (get_option ('fuse_gallery_type_'.$pt->name, 'no') == 'yes') {
                            $checked = ' checked="checked"';
                        } // if ()
                    ?>
                    <?php if (in_array ($pt->name, $hidden_types) === false): ?>
                        <li>
                            <label>
                                <input type="checkbox" name="fuse_gallery_posttype[]" value="<?php esc_attr_e ($pt->name); ?>"<?php echo $checked; ?> />
                                <?php echo $pt->label; ?>
                            </label>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>

            <?php
                $show = get_option ('fuse_gallery_show', 'content');
            ?>

            <h3><?php _e ('Settings', 'fuse'); ?></h3>

            <table class="form-table">
                <tr>
                    <th><?php _e ('Show gallery by', 'fuse'); ?></th>
                    <td>
                        <select name="fuse_gallery_show">
                            <option value="content"<?php selected ($show, 'content'); ?>><?php _e ('Display at the end of the post/page content', 'fuse'); ?></option>
                            <option value="shortcode"<?php selected ($show, 'shortcode'); ?>><?php _e ('Display using the [fuse_gallery] shortcode in your content or templates', 'fuse'); ?></option>
                        </select>
                    </td>
                </tr>
            </table>

            <p class="submit"><input type="submit" name="fuse_galleries_settings_submit" value="<?php esc_attr_e ('Save Settings', 'fuse'); ?>" class="button button-primary" /></p>

        </form>
    </div>
<?php
        } // settingsPage ()

    } // class Admin