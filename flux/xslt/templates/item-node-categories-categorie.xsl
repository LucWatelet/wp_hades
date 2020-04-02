<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template match="categories/categorie">
    <li><xsl:value-of select="lib[attribute::lg=$lg]" /></li>
  </xsl:template>

</xsl:stylesheet>
