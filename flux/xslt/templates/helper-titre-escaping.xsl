<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template name="helper-titre-escaping">
    <xsl:variable name="titre" select="../../titre[attribute::lg=$lg]" />
    <xsl:variable name="titre-esc-quot" select="translate($titre,           '&quot;', '&#34;')" />
    <xsl:variable name="titre-esc-apos" select='translate($titre-esc-quot,  "&apos;", "&#8217;")' />
    <xsl:variable name="titre-esc" select="$titre-esc-apos" />
    <xsl:value-of select="$titre-esc" />
  </xsl:template>

</xsl:stylesheet>
