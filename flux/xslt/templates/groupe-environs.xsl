<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template name="groupe-environs">
    <xsl:variable name="class"      select="'hades-ul-environ'" />
    <xsl:variable name="lot"        select="'lot_environ'" />
    <xsl:variable name="ispictoenabledv"      select="$ispictoenabled" />
    <xsl:if test="count(attributs/attribut[attribute::lot=$lot])        > 0 or
                  count(environs/environ)                               > 0 or
                  count(descriptions/description[attribute::lot=$lot])  > 0">
      <dl>
        <dt><xsl:call-template name="i18n-titre"><xsl:with-param name="titre" select="$lot" /></xsl:call-template></dt>
        <xsl:if test="count(environs/environ) > 0">
          <dd>
            <xsl:apply-templates select="environs/environ">
              <xsl:sort order="ascending" data-type="number" select="@tri" />
            </xsl:apply-templates>
          </dd>
        </xsl:if>
        <xsl:if test="count(attributs/attribut[attribute::lot=$lot]) > 0">
          <xsl:if test="count(environs/environ) > 0"><hr /></xsl:if>
          <dd>
            <ul class="{$class}">
              <xsl:for-each select="attributs/attribut[attribute::lot=$lot]">
                <xsl:sort order="ascending" data-type="number" select="@tri" />
                <xsl:call-template name="item-attribute-lot-generic" >
                  <xsl:with-param name="ispictoenabled" select="$ispictoenabledv" />
                </xsl:call-template>
              </xsl:for-each>
            </ul>
          </dd>
        </xsl:if>
        <xsl:if test="count(descriptions/description[attribute::lot=$lot]) > 0">
          <xsl:if test="count(attributs/attribut[attribute::lot=$lot]) > 0 or count(environs/environ) > 0"><hr /></xsl:if>
          <dd>
            <dl>
              <xsl:for-each select="descriptions/description[attribute::lot=$lot]">
                <xsl:sort order="ascending" data-type="number" select="@tri" />
                <xsl:call-template name="item-description-lot-generic" />
              </xsl:for-each>
            </dl>
          </dd>
        </xsl:if>
      </dl>
    </xsl:if>
  </xsl:template>

</xsl:stylesheet>
