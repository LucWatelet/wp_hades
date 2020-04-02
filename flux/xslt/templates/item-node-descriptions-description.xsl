<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template match="descriptions/description[attribute::lot='lot_descript']">
    <xsl:choose>
      <xsl:when test="lib='general'"><p class='hades_desc_general'><xsl:value-of select="texte[attribute::lg=$lg]" disable-output-escaping="yes" /></p></xsl:when>
      <xsl:when test="lib='gn_comp'"><p  class='hades_desc_gn_comp'><xsl:value-of select="texte[attribute::lg=$lg]" disable-output-escaping="yes" /></p></xsl:when>
      <xsl:when test="lib='des_plus'">
        <div class="hades_desc__plus">
            <xsl:value-of select="texte[attribute::lg=$lg]" disable-output-escaping="yes" />
        </div>
      </xsl:when>
      <xsl:otherwise><p><xsl:value-of select="texte[attribute::lg=$lg]" disable-output-escaping="yes" /></p></xsl:otherwise>
    </xsl:choose>
  </xsl:template>

</xsl:stylesheet>
