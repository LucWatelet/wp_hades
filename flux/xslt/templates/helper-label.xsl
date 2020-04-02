<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template match="attributs/attribut[attribute::lot='lot_label' and attribute::id!='lb_lcpn']">
    <li>
    <xsl:if test="picto">
      <img alt="{./picto}" title="{./lib[attribute::lg=$lg]}" src="http://www.ftlb.be/logos/default/{./picto}.png" />
    </xsl:if>
    <xsl:if test="not(picto)">
      <xsl:value-of select="./lib[attribute::lg=$lg]" />
      <xsl:if test="val"><xsl:text>&#160;</xsl:text><span class="badge"><xsl:value-of select="./val" /></span></xsl:if>
    </xsl:if>
    </li>
  </xsl:template>

</xsl:stylesheet>
