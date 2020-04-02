/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function send_loc_ajax(search_term) {
  jQuery.post(
          Hades_loc_ajax_url,
          {
            searchloc: search_term,
            action:"search_loc_hades"
          },
          hades_loc_send_list,
          "json"
          );
}

function hades_loc_send_list(response) {
//hades_cluster.clearLayers();
jQuery("#searchloc_list").html("");

  for (var j = 0; j < response.length; ++j)
  {
    jQuery("#searchloc_list").append(
            "<div class='searchloc_item' slug='" + response[j].slug + "' name='" + response[j].name + "'  >" + response[j].name + ' (' + response[j].count + ')</div>'
            );
  }
  
jQuery("#searchloc_list").show(300);

jQuery(".searchloc_item").click(
        function(){
          jQuery("#searchloc_localite").val(jQuery(this).attr('slug'));
          jQuery("#searchloc").val(jQuery(this).attr('name'));
          jQuery("#searchloc_list").hide(300);
          jQuery("#searchloc_list").html("");
        });
  
  
}

