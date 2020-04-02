<?xml version="1.0" encoding="UTF-8"?>

<!--
    Document   : communication.xsl
    Created on : 25 mai 2012, 13:47
    Author     : l.watelet
    Description:
        Purpose of transformation follows.
-->

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html"/>

    <xsl:template match="communications">
        <div  class='contact-communications'>
            <!-- limitation des communication aux téléphones url et mails   -->
            <xsl:for-each select="communication[lib='tel' or lib='url' or lib='mail']">
                <xsl:sort select="@tri" data-type="number" />
                <div>
                    <xsl:apply-templates select="."/>
                </div>
            </xsl:for-each>
        </div>       

    </xsl:template>
   
    <xsl:template match="communication">
        <span class="lib_com">
            <xsl:value-of select="lib[@lg='fr']"/> :
        </span>
        <xsl:value-of select="val" />
    </xsl:template>

</xsl:stylesheet>
