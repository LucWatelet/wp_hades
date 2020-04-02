<?php
namespace Atlb\Hades;

function get_titles_custom_field_from_xml_offer($xml_offer)
{
    $titles = $xml_offer->xpath('titre');
    $custom_field = [];
    foreach ($titles as $element) {
        if ($element['lg']) {
            $text = (string) $element[0];
            $text = str_replace('"', '&quot;', $text); // FIXME: use html_entities instead ?
            $custom_field[(string) $element['lg']] = $text;
        }
    } // FIXME: returns false if there really is no title ?
    return json_encode($custom_field, JSON_UNESCAPED_UNICODE);
}
