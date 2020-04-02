/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var hades_map = L.map('hades_ml_map');
/*            var hades_markers=[];
            var hades_map_marker = [];
           
            var hades_bounds = [];
            var hades_json_offres=[];  */       

if (!$) {
  $ = jQuery;
}

$(document).ready(function () {

  ml_flt_button_text = $("#ml_flt_button").html();

  $("#ml_flt_button").click(toggle_filter_pane);
  $("#ml_flt_submit").click(function () {
    send_maplist_ajax();
  });
 hades_map.on("moveend", maskinlist);
  //hades_map.on("zoomend",maskinlist );
});

function toggle_filter_pane() {
  if ($("#hades_ml_filter").css("width") != "200px") {
    $("#hades_ml_filter").animate({width: "200px"}, 500);
    $("#ml_flt_button").animate({right: "200px"}, 500);
    $("#ml_flt_button").html("Masquer<br/>le filtre");

  } else {
    $("#hades_ml_filter").animate({width: "0px"}, 500);
    $("#ml_flt_button").animate({right: "0px"}, 500);
    $("#ml_flt_button").html(ml_flt_button_text);
  }
}

function send_maplist_ajax() {
  jQuery("#hades_maplist_form").append("<input type='hidden' name='action' value='maplist_hades' />");
  jQuery.post(
          Hades_maplist_ajax_url,
          jQuery("#hades_maplist_form").serializeArray(),
          hades_maplist_success,
          "json"
          );

}

function hades_list_set_offres(response) {
  var liste = jQuery("#hades_ml_list");
  var html;
  liste.html("");
  for (i in response) {
    html = "<div class='maplist_offre_wrapper' id='offre_mrk_" + i + "'  >";
    if(response[i].thumbnail){
    html += "<img class='maplist_thumbnail' src='" + response[i].thumbnail + "' />";
  }
    html += "<h4><a href='" + response[i].permalink + "'>" + response[i].title + "</a></h4>";
    html += "<p>" + response[i].adresse + " " + response[i].cp + " " + response[i].localite_commune + "</p>";
    html += "<div class='clearfix'></div></div>";
    liste.append(html)

    //console.log(response[i]);
  }

}



function hades_maplist_success(response) {
  hades_map_set_markers(response);
  hades_list_set_offres(response)
}

function maskinlist() {
  
  var b = hades_map.getBounds();
  for (var i = 0; i < hades_map_marker.length; ++i)
  {
    if (b.contains(hades_map_marker[i].getLatLng()))
    {
      jQuery("#offre_mrk_" + i).show();
    } else
    {
      jQuery("#offre_mrk_" + i).hide();
    }
  }
}