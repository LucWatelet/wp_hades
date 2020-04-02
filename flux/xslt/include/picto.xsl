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

  <!-- ============================  ATTRIBUTS ============================  --> 

  <xsl:template match="picto">
    <img class="picto">
      <xsl:attribute name="src">
        <xsl:value-of select="$picto_path"/>
        <xsl:value-of select="." />.png</xsl:attribute>
      <xsl:attribute name="title">
        <xsl:value-of select="../lib[@lg='fr']" />
      </xsl:attribute>
    </img>    
  </xsl:template>

</xsl:stylesheet>

