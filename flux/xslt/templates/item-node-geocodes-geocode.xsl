<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template match="geocodes/geocode">
    <xsl:variable name="offre-id" select="../../@id" />
    [geocode id="<xsl:value-of select="$offre-id" />" x="<xsl:value-of select="x" />" y="<xsl:value-of select="y" />" titre="<xsl:call-template name="helper-titre-escaping" />" /]
  </xsl:template>

  <xsl:template match="xxgeocodes/geocode">
      [osm_map_v3 map_center="<xsl:value-of select="y" />,<xsl:value-of select="x" />" zoom="17" width="100%" height="450" map_border="none" file_list="../../../../wp-content/uploads/2016/08/Donauradweg_Passau_Wien.gpx" file_color_list="grey" tagged_type="post" marker_name="mic_black_power_plant_01.png"]
    <xsl:text>
    </xsl:text>
  </xsl:template>

  <xsl:template match="xgeocodes/geocode">
    <!-- FIXME: no quote substitution in the javascript snippet
    <xsl:variable name="apos">'</xsl:variable>
    <xsl:variable name="apos_entity" value="'&apos;'" />
    -->
    <!-- FIXME: get a google apikey
    <script src='js/google-maps-api.js'></script> -->
    <!-- FIXME: mark is not centered -->
    <xsl:variable name="offre-id" select="../../@id" />

    <div class="center-block" style='overflow:hidden;height:300px; width:300px;'>
      <div id='gmap_canvas_{$offre-id}' style='height:300px; width:300px;'></div>
      <script type='text/javascript'><![CDATA[
      function init_map(){
      var myOptions = { zoom:10,
                        mapTypeControlOptions: {  style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
                                                  position: google.maps.ControlPosition.BOTTOM_LEFT},
                                                  center:new google.maps.LatLng(]]><xsl:value-of select="y"/><![CDATA[,
                                                                                ]]><xsl:value-of select="x"/><![CDATA[),
                                                  mapTypeIds: [ google.maps.MapTypeId.ROADMAP,
                                                                google.maps.MapTypeId.TERRAIN]};
      map = new google.maps.Map(document.getElementById('gmap_canvas_]]><xsl:value-of select="$offre-id" /><![CDATA['),
                                myOptions);
      marker = new google.maps.Marker({ map: map,
                                        position: new google.maps.LatLng(]]><xsl:value-of select="y"/><![CDATA[,]]><xsl:value-of select="x"/><![CDATA[)});
                                        infowindow = new google.maps.InfoWindow({content:'<strong>]]><xsl:call-template name="helper-titre-escaping" /><![CDATA[</strong>'});
      google.maps.event.addListener(marker,
                                    'click',
                                    function(){infowindow.open(map,marker);});
      infowindow.open(map,marker);}
      google.maps.event.addDomListener(window, 'load', init_map);
      ]]></script>
    </div>
  </xsl:template>

</xsl:stylesheet>
