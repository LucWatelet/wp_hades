/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var hades_map_icons=[];


function send_map_ajax() {
  jQuery("#hades_map_search_form input[name='action']").attr('value','get_map_hades');
  jQuery.post(
          Hades_map_ajax_url,
          jQuery("#hades_map_search_form").serializeArray(),
          hades_map_set_markers,
          "json"
          );
}

function hades_map_set_markers(response) {
//hades_cluster.clearLayers();

  for (var i = 0; i < hades_map_marker.length; ++i)
  {
    hades_map.removeLayer(hades_map_marker[i]);
  }
  

  hades_markers = [];
  for (var j = 0; j < response.length; ++j)
  {
    //console.log(response[j]);
    hades_markers[j] = [];
    hades_markers[j]['lat'] = response[j].y;
    hades_markers[j]['lng'] = response[j].x;
    hades_markers[j]['titre'] = response[j].title;
    hades_markers[j]['contact'] = response[j].contact;
    hades_markers[j]['permalink']=response[j].permalink;
    hades_markers[j]['cat']=response[j].cat;
  }

  for (var i = 0; i < hades_markers.length; ++i)
  {
    hades_bounds[i] = [hades_markers[i].lat, hades_markers[i].lng];
    hades_map_marker[i] = L.marker(new L.LatLng(hades_markers[i]['lat'], hades_markers[i]['lng']), { title: hades_markers[i]['titre'],icon:icon(hades_markers[i]['cat']) });
    hades_map_marker[i].bindPopup("<h4><a href='"+hades_markers[i]['permalink']+"'>"+hades_markers[i]['titre']+"</a></h4><p>"+hades_markers[i]['contact']+"</p>");
    //hades_cluster.addLayer(hades_map_marker);
    hades_map.addLayer(hades_map_marker[i]);
  }
  if (hades_bounds.length > 1) {
    hades_map.fitBounds(hades_bounds);
  }
//hades_map.addLayer(hades_cluster);
//hades_map.addLayer(hades_cluster);

}

function icon(cat){
  if(!hades_map_icons[cat] ){
    //hades_map_icons[cat]= new L.Icon({iconUrl: 'wp-content/uploads/categories/markers/'+cat+'.png',
    //hades_map_icons[cat]= new L.Icon({iconUrl: 'wp-content/themes/twentyseventeen-child/assets/images/categories/markers/'+cat+'.png',
    hades_map_icons[cat]= new L.Icon({iconUrl: 'https://pivotweb.tourismewallonie.be/PivotWeb-3.1/img/urn:fld:catering:bbq;h=48',
        iconSize:     [48, 48],
        iconAnchor:   [32, 63]});
    console.log("cree "+cat); 
  }

return hades_map_icons[cat];  
  
}