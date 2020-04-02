<?php
namespace Atlb\Hades;

function get_medias_custom_field_from_xml_offer($xml_offer)
{
    $medias = $xml_offer->xpath('medias/media[@ext="jpg"]');
    $custom_field = [];
    if (count($medias) > 0) {
        $custom_field = array_map(
            function ($media) {
                return [
                    'copyright' => (string) $media->copyright,
                    'url' => (string) $media->url
                ];
            },
            $medias
        );
    } else {
        return false;
    }

    return json_encode($custom_field, JSON_UNESCAPED_UNICODE);
}
