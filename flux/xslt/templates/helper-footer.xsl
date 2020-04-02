<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template name="footer">
    <small>Validity date<xsl:text>:&#160;</xsl:text><xsl:value-of select="publiable" /> Last updated<xsl:text>:&#160;</xsl:text><xsl:value-of select="modif_date" /></small>
  </xsl:template>

</xsl:stylesheet>
