<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template name="fiche-enfant-l">
    <h1 class="uppercase-firstl"><xsl:value-of select="lib[attribute::lg=$lg]" /></h1>
    <h2 class="uppercase-firstl"><a href="?hadesoff=hadesoff_id{./attribute::id}"><xsl:call-template name="titre" /></a><xsl:call-template name="flag" /><xsl:text>&#160;</xsl:text></h2>
    <xsl:call-template name="groupe-descriptions" />
    <!-- vérifier si mieux premier media -->
    <xsl:call-template name="groupe-medias-carousel"><xsl:with-param name="carousel-style" select="'carousel-enfant'" /></xsl:call-template>
    <xsl:call-template name="groupe-medias" />
  </xsl:template>

</xsl:stylesheet>
