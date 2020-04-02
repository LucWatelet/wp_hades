<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template name="item-attribute-lot-generic">
    <xsl:param name="ispictoenabled" />
    <li>
      <xsl:if test="$ispictoenabled">
        <xsl:apply-templates select="picto" />
      </xsl:if>
      <xsl:value-of select="lib[attribute::lg=$lg]" />
      <xsl:if test="not(./attribute::typ='chk')">
        <xsl:text>&#160;</xsl:text><span class="badge"><xsl:value-of select="./val" /><xsl:call-template name="unit" /></span>
      </xsl:if>
      <xsl:if test="./attribute::typ='chk'">
        <span class="badge visibility-glyphicon-ok"><span class="glyphicon glyphicon-ok"></span></span>
      </xsl:if>
    </li>
  </xsl:template>

</xsl:stylesheet>
