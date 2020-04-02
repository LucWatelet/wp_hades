<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template name="titre">
    <xsl:choose>
    <xsl:when test="titre[attribute::lg=$lg] != ''">
      <xsl:value-of select="titre[attribute::lg=$lg]" />
    </xsl:when>
    <xsl:otherwise>
      <xsl:value-of select="titre[attribute::lg='fr']" />
    </xsl:otherwise>
    </xsl:choose>
  </xsl:template>

</xsl:stylesheet>
