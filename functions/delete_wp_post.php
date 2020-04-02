<?php
namespace Atlb\Hades;

function delete_wp_post($offer)
{
    echo $offer->ID . ': ';
    if (has_post_thumbnail($offer->ID)) {
        $attachment_id = get_post_thumbnail_id($offer->ID);
        wp_delete_attachment($attachment_id, true);
        echo 'featured image deleted ';
    }
    if (false != wp_delete_post($offer->ID, true)) {
        echo 'post deleted ';
    } else {
        echo 'post not deleted';
    }
    echo "\r\n";
    ob_flush();
    flush();
}
