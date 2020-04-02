<?php
namespace Atlb\Hades;

function get_generic_custom_field_from_xml_offer($xml_offer, $lot_name)
{
    $attributes = $xml_offer->xpath('attributs/attribut[@lot="' . $lot_name . '"]');
    $descriptions = $xml_offer->xpath('descriptions/description[@lot="' . $lot_name . '"]');
    $openings = array();
    if ($lot_name === 'lot_horaire') {
        $openings = $xml_offer->xpath('horaires/horaire');
    }
    if (count($attributes) === 0 && count($descriptions) === 0 && count($openings) === 0) {
        return false;
    }
    if (count($attributes) > 0) {
        foreach ($attributes as $attribute) {
            $id = (string) $attribute->xpath('lib[not(@*)]')[0]; // FIXME: should we take the id attribute instead ?
            $custom_field['attributes'][$id]['id'] = $id;
            foreach ($attribute->xpath('lib[@lg]') as $label) {
                $custom_field['attributes'][$id]['labels'][(string) $label['lg']] = (string) $label[0];
            }
            $custom_field['attributes'][$id]['sort_key'] = (int) $attribute['tri']; // FIXME: is there always a sort key ?
            if ('ent' === (string) $attribute['typ']) { // FIXME: check if typ is present ?
                $custom_field['attributes'][$id]['value'] = (int) $attribute->val;
            } elseif ('chk' === (string) $attribute['typ']) {
                $custom_field['attributes'][$id]['value'] = true;
            } elseif ('stxt' === (string) $attribute['typ']) {
                $custom_field['attributes'][$id]['value'] = (string) $attribute->val;
            }
            if (isset($attribute->picto)) {
                $custom_field['attributes'][$id]['picture'] = (string) $attribute->picto;
            }
        }
    }
    if (count($descriptions) > 0) {
        foreach ($descriptions as $description) {
            $id = (string) $description->xpath('lib[not(@*)]')[0];
            $custom_field['descriptions'][$id]['id'] = $id; // FIXME: should be removed ?
            $custom_field['descriptions'][$id]['sort_key'] = (int) $description['tri']; // FIXME: is there always a sort key ?
            foreach ($description->xpath('lib[@lg]') as $label) {
                $custom_field['descriptions'][$id]['labels'][(string) $label['lg']] = (string) $label[0];
            }
            foreach ($description->xpath('texte[@lg]') as $content) {
                $text = (string) $content[0];
                $text = str_replace(array("\r\n", "\r", "\n"), '<br>', $text);
                $text = str_replace('"', '&quot;', $text); // FIXME: use html_entities instead ?
                //$text = htmlentities($text); // FIXME: use html_entities instead ?
                $custom_field['descriptions'][$id]['contents'][(string) $content['lg']] = $text; // FUCK YOU WP https://wordpress.stackexchange.com/questions/53336/wordpress-is-stripping-escape-backslashes-from-json-strings-in-post-meta
            }
        }
    }
    if (count($openings) > 0) {
        foreach ($openings as $opening) {
            foreach ($opening->xpath('lib[@lg]') as $label) {
                $custom_field['openings']['labels'][(string) $label['lg']] = (string) $label[0];
            }
            foreach ($opening->xpath('texte[@lg]') as $content) {
                $text = (string) $content[0];
                $text = str_replace(array("\r\n", "\r", "\n"), '<br>', $text);
                $text = str_replace('"', '&quot;', $text); // FIXME: use html_entities instead ?
                //$text = htmlentities($text); // FIXME: use html_entities instead ?
                $custom_field['openings']['contents'][(string) $content['lg']] = $text; // FUCK YOU WP https://wordpress.stackexchange.com/questions/53336/wordpress-is-stripping-escape-backslashes-from-json-strings-in-post-meta
            }
            foreach ($opening->xpath('horline/date_fin') as $dates) {
                $text = (string) $dates[0];
                $custom_field['openings']['end_date'][] = $text; // FUCK YOU WP https://wordpress.stackexchange.com/questions/53336/wordpress-is-stripping-escape-backslashes-from-json-strings-in-post-meta
            }
        }
    }

    return json_encode($custom_field, JSON_UNESCAPED_UNICODE);
}
