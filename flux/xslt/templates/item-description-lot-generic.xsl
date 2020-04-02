<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template name="item-description-lot-generic">
    <xsl:if test="texte != ''">
      <dd><xsl:value-of select="./texte[attribute::lg=$lg]" disable-output-escaping="yes" /></dd>
    </xsl:if>
  </xsl:template>

</xsl:stylesheet>
