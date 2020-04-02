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

    <!-- ============================ HORAIRE ============================== -->
    <xsl:template match="horaires">
        <xsl:for-each select="horaire"> 
            <div>
                <xsl:attribute name="class">horaire-texte <xsl:value-of select="lib[not(@lg)]"/></xsl:attribute>   
                <xsl:value-of select="texte[@lg='fr']" />
            </div>
        </xsl:for-each>
    </xsl:template>

</xsl:stylesheet>
