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

    <!-- ============================  LOCALISATION  ==========================  -->

    <xsl:template match="localisation">
        <xsl:for-each select="localite"> 
            <div class="localite">
                <xsl:value-of select="l_nom"/>
                <xsl:if test="c_nom != l_nom">
                    (<xsl:value-of select="c_nom"/>)
                </xsl:if> 
            </div>
        </xsl:for-each>
    </xsl:template>
</xsl:stylesheet>
