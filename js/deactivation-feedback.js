/**
 * Created by User on 6/17/2017.
 */
"use strict";
jQuery(document).ready(function () {
    var confirmDeactivationLink = jQuery(".hugeit-deactivate-plugin-photo-gallery"),
        cancelDeactivationLink = jQuery(".hugeit-cancel-deactivation-photo-gallery"),
        deactivationURL;


    jQuery('body').on('click', '#the-list tr[data-slug=' + hugeitPhotogalleryL10n.slug + '] .deactivate a', function (e) {
        e.preventDefault();
        hugeitModalPhotoGallery.show(hugeitPhotogalleryL10n.slug + '-deactivation-feedback');
        deactivationURL = jQuery(this).attr('href');

        return false;
    });

    confirmDeactivationLink.on('click', function (e) {
        e.preventDefault();

        var checkedOption = jQuery('input[name=' + hugeitPhotogalleryL10n.slug + '-deactivation-reason]:checked'),
            comment = jQuery('textarea[name=' + hugeitPhotogalleryL10n.slug + '-deactivation-comment]').val(),
            nonce = jQuery('#hugeit-photo-gallery-deactivation-nonce').val();
        if (checkedOption.length || comment.length) {
            hugeitModalPhotoGallery.hide('forms-contact-deactivation-feedback');
            sendDeactivationFeedback(checkedOption.val(), comment, nonce);
            setTimeout(function () {
                window.location.replace(deactivationURL);
            }, 0);
        } else {
            hugeitModalPhotoGallery.hide('forms-contact-deactivation-feedback');
            window.location.replace(deactivationURL);
        }

        return false;
    });

    cancelDeactivationLink.on('click', function (e) {
        e.preventDefault();

        hugeitModalPhotoGallery.hide('-deactivation-feedback');

        return false;
    });

    function sendDeactivationFeedback(v, c, n) {
        jQuery.ajax({
            url: ajaxurl,
            method: 'post',
            data: {
                action: 'hugeit_photo_gallery_deactivation_feedback',
                value: v,
                comment: c,
                nonce: n
            }
        });
    }
});