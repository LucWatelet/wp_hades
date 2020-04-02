/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function send_excell_ajax() {
  jQuery("#hades_map_search_form input[name='action']").attr('value','get_excell_hades');
  jQuery("#hades_map_search_form").attr('action',Hades_excell_ajax_url);
  jQuery("#hades_map_search_form").attr('method','post');
  jQuery("#hades_map_search_form").attr('target','blank');
  jQuery("#hades_map_search_form").submit();

}


