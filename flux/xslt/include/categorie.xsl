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

    <!-- ============================  CATEGORIES ============================  --> 
 
    <xsl:template match="categorie">
        <xsl:if test="position()>2">
            <xsl:text>/</xsl:text>
        </xsl:if>
        <xsl:value-of select="lib[@lg='fr']"/>
    </xsl:template>

</xsl:stylesheet>
