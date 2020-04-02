<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template name="date_deb-date_fin">
    &#160;<small><xsl:value-of select="concat('(', date_deb, ' - ', date_fin, ')')" disable-output-escaping="yes" /></small>
  </xsl:template>

</xsl:stylesheet>
