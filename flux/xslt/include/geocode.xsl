<?xml version="1.0" encoding="UTF-8"?>

<!--
    Document   : form.xsl
    Created on : 3 mai 2012, 13:38
    Author     : l.watelet
    Description:
        Purpose of transformation follows.
-->

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html"/>

    <!-- ============================  GEOCODE ============================  --> 

    <xsl:template match="geocode">
        <div class="map-prov">          
            <xsl:attribute name="id">map<xsl:value-of select="../../@id"/></xsl:attribute> 
            <img class="mapimg" src="/images/cartescom.jpg" />
            <img class="mappin" src="/images/bluepin.png"/>
        </div>        
        <script>setTimeout(function(){carteprovlux(<xsl:value-of select="x" />,<xsl:value-of select="y" />,"#map<xsl:value-of select="../../@id"/>");},500)</script>           
    </xsl:template>

</xsl:stylesheet>
