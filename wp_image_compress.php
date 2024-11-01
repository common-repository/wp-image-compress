<?php
/*
Plugin Name:Wp Image Compress
Description:This Plugin automatically compress all your images. Make your website faster by optimizing JPEG images.   
Version:1.0
Author:Kumbh Design Inc.
Author URI:https://www.kumbhdesign.com/
License:GPL2
License URI:https://www.gnu.org/licenses/gpl-2.0.html
*/

add_action('added_post_meta', 'ad_update_jpeg_quality', 10, 4);

register_activation_hook(__FILE__, 'ad_update_jpeg_quality');

function ad_update_jpeg_quality() {

    $attachments = get_posts(array(
        'numberposts' => -1,
        'post_type' => 'attachment',
        'post_mime_type' => 'image/jpeg','image/png'
    ));

  //  if (empty($attachments)) return;

    $uploads = wp_upload_dir();

    foreach ($attachments as $attachment) {

        $attach_meta = wp_get_attachment_metadata($attachment->ID);
  //      if (!is_array($attach_meta['sizes'])) break;

        $pathinfo = pathinfo($attach_meta['file']);
        $dir = $uploads['basedir'] . '/' . $pathinfo['dirname'];

        foreach ($attach_meta['sizes'] as $size => $value) {

            $image = $dir . '/' . $value['file'];
            
            $resource = imagecreatefromjpeg($image);

            if ($size == 'spalsh') {
                // set the jpeg quality for 'spalsh' size
                imagejpeg($resource, $image, 100);
            } elseif ($size == 'spalsh1') {
                // set the jpeg quality for the 'splash1' size
                imagejpeg($resource, $image, 60);
            } else {
                // set the jpeg quality for the rest of sizes
                imagejpeg($resource, $image, 60);
            }

            imagedestroy($resource);
        }
    }
}
?>