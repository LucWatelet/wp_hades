<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template match="picto">
    <img alt="{.}" title="{./../lib[attribute::lg=$lg]}" class="hades-picto"  src="http://w3.ftlb.be/images/picto/{.}.png" />
  </xsl:template>

</xsl:stylesheet>
