<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template name="fiche-parent-h">
    <h1 class="uppercase-firstl"><xsl:value-of select="lib[attribute::lg=$lg]" /></h1>
    <h2 class="uppercase-firstl"><a href="?hadesoff=hadesoff_id{./attribute::id}"><xsl:call-template name="titre" /></a><xsl:call-template name="flag" /><xsl:text>&#160;</xsl:text></h2>
      <xsl:call-template name="groupe-descriptions" />
      <xsl:call-template name="groupe-medias-wordpress-carousel" />
      <!--<xsl:call-template name="groupe-medias-carousel"><xsl:with-param name="carousel-style" select="'carousel-parent'" /></xsl:call-template>-->
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
      <xsl:if test="$isgooglemapenabled"><xsl:call-template name="groupe-geocodes" /></xsl:if>
      <xsl:if test="$istripadvisorenabled"><xsl:call-template name="groupe-trip-advisor" /></xsl:if>
  </xsl:template>

</xsl:stylesheet>
