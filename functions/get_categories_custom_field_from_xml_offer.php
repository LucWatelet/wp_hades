<?php
namespace Atlb\Hades;

function get_categories_custom_field_from_xml_offer($xml_offer)
{
    $categories = $xml_offer->xpath('categories/categorie/@id');
    $custom_field = [];
    if (count($categories) > 0) {
        $custom_field = array_map(
            function ($category) {
                return (string) $category->id;
            },
            $categories
        );
    } else {
        return false;
    }
    return json_encode($custom_field, JSON_UNESCAPED_UNICODE);
}
