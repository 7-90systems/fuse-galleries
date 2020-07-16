/**
 *  Admin JavaScript.
 */

jQuery (document).ready (function () {

    var frame,
        metaBox = jQuery ('#fuse_galleries_gallery_meta.postbox'),
        addImgLink = metaBox.find('.gallery_item.add');

    // ADD IMAGE LINK
    addImgLink.on ('click', function (event) {
        event.preventDefault ();

        if (frame) {
            frame.open ();
            return;
        } // if ()

        frame = wp.media({
            title: 'Add gallery images',
            button: {
                text: 'Add images'
            },
            multiple: true
        });

        frame.on ('select', function () {
            frame.state().get('selection').each (function (img) {
                img = img.toJSON ();

                var current_img = jQuery ('.gallery_item.image[data-id=' + img.id + ']');

                if (current_img.length == 0) {
                    jQuery ('<div class="gallery_item image" data-id="' + img.id + '"><div class="delete">Delete</div><img src="' + img.sizes.thumbnail.url + '" alt="" /></div>').insertBefore (addImgLink);
                } // if ()
            });

            fuseGalleriesSetIds ();
        });

        frame.open ();
    });


    // DELETE IMAGE LINK
    jQuery ('#fuse_galleries_gallery_container').on ('click', '.gallery_item .delete', function () {
        event.preventDefault ();

        jQuery (this).closest ('.gallery_item').remove ();
        fuseGalleriesSetIds ();
    });

    jQuery ('#fuse_galleries_gallery_container').sortable ({
         opacity: 0.6,
         items: '.gallery_item.image',
         update: function () {
            fuseGalleriesSetIds ();
         }
     });
});




function fuseGalleriesSetIds () {
    var ids = [];

    jQuery ('#fuse_galleries_gallery_container .gallery_item.image').each (function () {
        ids.push (jQuery (this).data ('id'));
    });

    jQuery ('#fuse_galleries_gallery_ids').val (ids);
} // fuseGalleriesSetIds ()