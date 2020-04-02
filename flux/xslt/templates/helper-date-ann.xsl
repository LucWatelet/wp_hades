<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template name="date-ann">
    <xsl:text>&#160;</xsl:text><small><xsl:value-of select=".././attribute::date='ann'" /></small>
  </xsl:template>

</xsl:stylesheet>
