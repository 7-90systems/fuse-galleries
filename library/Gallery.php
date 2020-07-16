<?php
    /**
     *  Set up our gallery functions.
     *
     *  @filter fuse_galleries_thumb_size
     *  @filter fuse_galleries_full_size
     */

    namespace Fuse\Plugin\Galleries;


    class Gallery {

        /**
         *  Object constructor.
         */
        public function __construct () {
            add_action ('add_meta_boxes', array ($this, 'addMetaBoxes'), 9);
            add_action ('save_post', array ($this, 'savePost'));

            // Add our gallery to the bottom of the post content.
            if (get_option ('fuse_gallery_show', 'content') != 'shortcode') {
                add_filter ('the_content', array ($this, 'showGallery'));
            } // if ()

            // Set up our gallery shortcode.
            add_shortcode ('fuse_gallery', array ($this, 'showgallery'));
        } // __construct ()




        /**
         *  Add our meta box.
         */
        public function addMetaBoxes () {
            $post_types = get_post_types ();

            foreach ($post_types as $type) {
                if (get_option ('fuse_gallery_type_'.$type, 'no') == 'yes') {
                    add_meta_box ('fuse_galleries_gallery_meta', __ ('Gallery', 'fuse'), array ($this, 'galleryMeta'), $type, 'normal', 'high');
                } // if ()
            } // foreach ()
        } // addMetaBoxes ()

        /**
         *  set up the gallery meta box.
         */
        public function galleryMeta ($post) {
            $gallery = get_post_meta ($post->ID, 'fuse_galleries_gallery_ids', true);

            if (strlen ($gallery) > 0) {
                $gallery = explode (',', $gallery);
            } // if ()
            else {
                $gallery = array ();
            } // else
?>
    <ul id="fuse_galleries_gallery_container">
        <?php foreach ($gallery as $img): ?>
            <?php
                $img = intval ($img);
            ?>
            <?php if ($img > 0): ?>
                <?php
                    $thumb = wp_get_attachment_image_src ($img, 'thumbnail');
                ?>
                <li class="gallery_item image ui-state-default" data-id="<?php echo $img; ?>">
                    <div class="delete">Delete</div>
                    <img src="<?php echo esc_url ($thumb [0]); ?>" alt="" />
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
        <li class="gallery_item add">
            <?php _e ('Add gallery image', 'fuse'); ?>
        </li>
    </ul>
    <div class="clear"></div>

    <input type="hidden" id="fuse_galleries_gallery_ids" name="fuse_galleries_gallery_ids" value="<?php echo implode (',', $gallery); ?>" />
<?php
        } // galleryMeta ()




        /**
         *  Save our posts values.
         */
        public function savePost ($post_id) {
            if (defined ('DOING_AUTOSAVe') && DOING_AUTOSAVE) {
                return;
            } // if ()
            else {
                // Gallery
                if (array_key_exists ('fuse_galleries_gallery_ids', $_POST)) {
                    update_post_meta ($post_id, 'fuse_galleries_gallery_ids', $_POST ['fuse_galleries_gallery_ids']);
                } // if ()
            } // else
        } // savePost ()




        /**
         *  Show the image gallery.
         */
        public function showGallery ($content) {
            global $post;

            // Are galleries allowed for this post type?
            if (get_option ('fuse_gallery_type_'.$post->post_type, 'no') == 'yes') {
                // Does this post have a gallery?
                $images = get_post_meta ($post->ID, 'fuse_galleries_gallery_ids', true);

                if (strlen ($images) > 0) {
                    $images = explode (',', $images);

                    $thumb_size = apply_filters ('fuse_galleries_thumbnail_size', apply_filters ('fuse_galleries_thumb_size', 'thumbnail'));
                    $large_size = apply_filters ('fuse_galleries_large_size', apply_filters ('fuse_galleries_full_size', 'full'));

                    ob_start ();
?>
    <div class="fuse-page-gallery gallery-type<?php echo $post->post_type; ?>">
        <?php foreach ($images as $image_id): ?>
            <?php
                $image = get_post ($image_id);
            ?>
            <?php if ($image->post_type = 'attachment'): ?>
                <?php
                    $thumb = wp_get_attachment_image_src ($image->ID, $thumb_size);
                    $large = wp_get_attachment_image_src ($image->ID, $large_size);

                    $alt = esc_attr ($image->post_title);
                ?>
                <div class="fuse-page-gallery-image">
                    <a href="<?php echo esc_url ($large [0]); ?>" title="<?php echo $alt; ?>">
                        <img src="<?php echo esc_url ($thumb [0]); ?>" alt="<?php echo $alt; ?>" class="fuse-gallery-image" />
                    </a>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
<?php
                    $html = ob_get_contents ();
                    ob_end_clean ();

                    $content.= $html;
                } // if ()
            } // if ()

            return $content;
        } // showGallery ()

    } // class Gallery