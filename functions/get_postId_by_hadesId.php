<?php
function get_postId_by_hadesId($hades_id, $url = false)
{
    $hades_id = intval($hades_id);
    if ($hades_id > 0) {
        $query = new WP_Query(
            array(
                'post_type' => 'hades_offre',
                'meta_query' => array(
                    'relation' => 'AND',
                    'hades_id' => array(
                        'key' => 'hades_id',
                        'value' => $hades_id,
                        'compare' => '='
                    ),
                    'dateheure_id' => array(
                        'key' => 'dateheure_id'
                    )
                ),
                'orderby' => array(
                    'dateheure_id' => 'ASC'
                )
            )
        );
        $results = $query->get_posts();
        if ($query->have_posts()) {
            if ($url) {
                $post_id = get_permalink($results[0]->ID);
            } else {
                $post_id = $results[0]->ID;
            }
        } else {
            $post_id = false;
        }
    } else {
        trigger_error("get_postId_by_hadesId() called with a Bad parameter, interger expression expected", E_USER_WARNING);
    }
    return $post_id;
}
