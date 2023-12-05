<?php
namespace Atlb\Hades;

function get_contact_custom_field_from_xml_offer($xml_offer)
{
    $contact = array();
    $xpath = 'contacts/contact[lib="contact"] | parents/offre/contacts/contact[lib="contact"]';
    $public_contact = @$xml_offer->xpath($xpath)[0];
    if ($public_contact) {
        if ((string) @$public_contact->noms[0] || (string) $public_contact->prenoms[0]) {
            $contact['public_contact']['salutation'] = (string) $public_contact->civilite[0];
            $contact['public_contact']['last_name'] = (string) $public_contact->noms[0];
            $contact['public_contact']['first_name'] = (string) $public_contact->prenoms[0];
        }
        if ((string) @$public_contact->societe[0]) {
            $contact['public_contact']['company'] = addslashes((string) $public_contact->societe[0]);
        }
		if(is_array(@$contact['public_contact'])){
        $contact['public_contact'] = array_filter($contact['public_contact'], 'strlen');
		}
		
        $communications = $public_contact->communications->communication;
        if ($communications) {
            $contact['public_contact']['channels'] = array();
            foreach ($communications as $communication) {
                $channel['type'] = (string) $communication['typ'];
                $channel['label'] = (string) @$communication->xpath('lib[not(@*)]')[0]; // FIXME: should be named subtype ?
                $channel['val'] = (string) @$communication->val[0];
                foreach ($communication->xpath('lib[@lg]') as $label) {
                    $channel['labels'][(string) $label['lg']] = (string) $label[0];
                }
                $channel['sort_key'] = (int) $communication['tri'];
                array_push($contact['public_contact']['channels'], $channel);
            }
        }
    }
    $xpath = 'contacts/contact[lib="ap"] | parents/offre[@rel="agenda"]/contacts/contact[lib="ap"]';
    $main_contact = @$xml_offer->xpath($xpath)[0];
    if ($main_contact) {
		$contact['main_contact']=array();
        if ((string) @$main_contact->societe[0] && (string) $main_contact->societe[0] != @$contact['public_contact']['company']) {
            $contact['main_contact']['company'] = addslashes((string) $main_contact->societe[0]);
        }
        if ((string) @$main_contact->adresse[0]) {
            $contact['main_contact']['number'] = (string) $main_contact->numero[0];
            $contact['main_contact']['unit'] = (string) $main_contact->boite[0];
            $contact['main_contact']['street'] = (string) $main_contact->adresse[0];
        }
        if ((string) @$main_contact->postal[0] || (string) $main_contact->l_nom[0]) {
            $contact['main_contact']['zip_code'] = (string) $main_contact->postal[0];
            $contact['main_contact']['city'] = (string) $main_contact->l_nom[0];
        }
        $contact['main_contact'] = array_filter($contact['main_contact'], 'strlen');
    }
    if (empty($contact)) {
        $contact = false;
    }
    return json_encode($contact, JSON_UNESCAPED_UNICODE);
}
