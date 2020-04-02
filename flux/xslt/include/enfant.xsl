<?xml version="1.0" encoding="UTF-8"?>

<!--
Document   : enfant.xsl
Created on : 29 mai 2012, 10:53
Author     : l.watelet
Description:
Purpose of transformation follows.
-->

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html"/>
    <xsl:template match="//enfants/offre[@typ='l'] | //parents/offre[@typ='l']" priority="1">
            <div class="cadre-enfant-l" >
                <xsl:attribute name="off_id">
                    <xsl:value-of select="@id"/>
                </xsl:attribute>

                <!-- Récupération du premier média de l'offre -->                
                <xsl:attribute name="style">
                    <xsl:text>background-image:url('</xsl:text>
                    <xsl:value-of select="medias/media[@ext='jpg'][1]/url"/>
                    <xsl:text>');</xsl:text>
                </xsl:attribute>
                <a class="titre-enfant-l">
                <xsl:attribute name="href">
                    <xsl:text>http://w3.ftlb.be/interface/fiche/fiche.php?off_id=</xsl:text>
                    <xsl:value-of select="@id"/>
                    <xsl:text>&amp;fiche=ftlb</xsl:text>
                </xsl:attribute>
                <strong>
                    <xsl:value-of select="categories/categorie/lib"/>
                </strong>
                <xsl:text> - </xsl:text>
                <xsl:value-of select="titre[@lg='fr']"/>
                </a>
            </div>
    </xsl:template>
</xsl:stylesheet>
