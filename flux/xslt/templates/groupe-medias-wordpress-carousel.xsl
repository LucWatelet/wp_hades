<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template name="groupe-medias-wordpress-carousel">
    <xsl:if test="count(medias/media) > 0" >
      <xsl:if test="count(medias/media) > 0">
        <xsl:if test="count(medias/media[attribute::ext='jpg' or attribute::ext='png']) > 0">
        <xsl:text>[hades_gallery id="</xsl:text><xsl:value-of select="./attribute::id" /><xsl:text>"]</xsl:text>
        <xsl:for-each select="medias/media[attribute::ext='jpg' or attribute::ext='png']">
          <xsl:text>[hades_gallery_image </xsl:text>
            <xsl:text>url="</xsl:text>
            <xsl:value-of select="url" />
            <xsl:text>" </xsl:text>
            <xsl:text>titre="</xsl:text>
            <xsl:value-of select="titre[attribute::lg='fr']" />
            <xsl:text>" </xsl:text>
            <xsl:text>copyright="</xsl:text>
            <xsl:value-of select="copyright" />
            <xsl:text>"</xsl:text>
          <xsl:text>]</xsl:text>
        </xsl:for-each>
        <xsl:text>[/hades_gallery]</xsl:text>
      </xsl:if></xsl:if>
    </xsl:if>
  </xsl:template>

</xsl:stylesheet>
