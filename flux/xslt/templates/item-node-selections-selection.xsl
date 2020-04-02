<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template match="selections/selection">
    <li><xsl:value-of select="lib" /></li>
  </xsl:template>

</xsl:stylesheet>
