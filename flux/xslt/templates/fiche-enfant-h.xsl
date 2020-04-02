<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template name="fiche-enfant-h">
    <h2 >
        <xsl:call-template name="titre" />
    </h2>
    <xsl:call-template name="groupe-descriptions" />
    <xsl:call-template name="groupe-medias-wordpress-carousel" />
    <!--<xsl:call-template name="groupe-medias-carousel"><xsl:with-param name="carousel-style" select="'carousel-enfant'" /></xsl:call-template>-->
    <xsl:call-template name="groupe-medias" />
    <xsl:call-template name="groupe-accueils" />
    <xsl:call-template name="groupe-activites" />
    <xsl:call-template name="groupe-artisans" />
    <xsl:call-template name="groupe-capacites" />
    <xsl:call-template name="groupe-cartos" />
    <xsl:call-template name="groupe-charges" />
    <xsl:call-template name="groupe-cuisines" />
    <xsl:call-template name="groupe-environs" />
    <xsl:call-template name="groupe-equ_grps" />
    <xsl:call-template name="groupe-equipements" />
    <xsl:call-template name="groupe-horaires" />
    <xsl:call-template name="groupe-infos" />
    <xsl:call-template name="groupe-mices" />
    <xsl:call-template name="groupe-paiements" />
    <xsl:call-template name="groupe-pmrs" />
    <xsl:call-template name="groupe-publics" />
    <xsl:call-template name="groupe-restricts" />
    <xsl:call-template name="groupe-ser_grps" />
    <xsl:call-template name="groupe-services" />
    <xsl:call-template name="groupe-tarifs" />
    <xsl:call-template name="groupe-contacts" />
  </xsl:template>

</xsl:stylesheet>
