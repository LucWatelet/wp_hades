<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template name="groupe-trip-advisor">
    <xsl:if test="descriptions/description[lib='tripadvisor']">
      <!--<xsl:value-of disable-output-escaping="yes" select="descriptions/description[./lib='tripadvisor']/texte[attribute::lg=$lg]" />-->
          <xsl:value-of disable-output-escaping="yes" select="descriptions/description[./lib[text()='tripadvisor']]/texte[attribute::lg='fr']" />
    </xsl:if>
  </xsl:template>

</xsl:stylesheet>
