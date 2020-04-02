<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template name="groupe-descriptions">
    <xsl:variable name="isbadgepulldisabledv" select="false()" />
    <xsl:variable name="ispictoenabledv"      select="$ispictoenabled" />
    <xsl:apply-templates select="descriptions/description[attribute::lot='lot_descript']">
      <xsl:sort order="ascending" data-type="number" select="@tri" />
    </xsl:apply-templates>
    <xsl:if test="count(attributs/attribut[attribute::lot='lot_descript']) > 0">
      <div class="well">
        <ul class="description">
          <xsl:for-each select="attributs/attribut[attribute::lot='lot_descript']">
            <xsl:sort order="ascending" data-type="number" select="@tri" />
            <xsl:call-template name="item-attribute-lot-generic" >
              <xsl:with-param name="isbadgepulldisabled" select="$isbadgepulldisabledv" />
              <xsl:with-param name="ispictoenabled" select="$ispictoenabledv" />
            </xsl:call-template>
          </xsl:for-each>
        </ul>
      </div>
    </xsl:if>
  </xsl:template>

</xsl:stylesheet>
