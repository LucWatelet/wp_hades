<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template name="groupe-tarifs">
    <xsl:variable name="class"      select="'hades-ul-tarif'" />
    <xsl:variable name="lot"        select="'lot_tarif'" />
    <xsl:variable name="ispictoenabledv"      select="$ispictoenabled" />

    <xsl:if test="count(attributs/attribut[attribute::lot=$lot])        > 0 or
                  count(tarifs/tarif)                                   > 0 or
                  count(descriptions/description[attribute::lot=$lot])  > 0">
      <dl>
        <dt><xsl:call-template name="i18n-titre"><xsl:with-param name="titre" select="$lot" /></xsl:call-template></dt>
        <xsl:if test="count(tarifs/tarif) > 0">
          <xsl:variable name="current-year" select="tarifs/attribute::an" />
          <dd>
            <xsl:if test="count(tarifs/tarif[attribute::an=$current-year]) > 0">
              <table class="tarif">
                <xsl:for-each select="tarifs/tarif[attribute::an=$current-year]">
                  <xsl:sort order="ascending" data-type="number" select="@tri" />
                  <xsl:call-template name="item-node-tarifs-tarif" />
                </xsl:for-each>
              </table>
            </xsl:if>
            <xsl:if test="count(tarifs/tarif[attribute::an=$current-year+1]) > 0 and $isnextyearfeeenabled">
              <xsl:if test="count(tarifs/tarif[attribute::an=$current-year]) > 0"><hr /></xsl:if>
              <table class="tarif">
                <xsl:for-each select="tarifs/tarif[attribute::an=$current-year+1]">
                  <xsl:sort order="ascending" data-type="number" select="@an" /> <!-- NOTE: ne fonctionne correctement que si les années supplémentaires de tarifs sont effectivement triées dans le flux -->
                  <xsl:call-template name="item-node-tarifs-tarif" />
                </xsl:for-each>
              </table>
            </xsl:if>
          </dd>
        </xsl:if>
        <xsl:if test="count(attributs/attribut[attribute::lot=$lot]) > 0">
          <xsl:if test="count(tarifs/tarif) > 0"><hr /></xsl:if>
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
          <xsl:if test="count(attributs/attribut[attribute::lot=$lot]) > 0 or count(tarifs/tarif) > 0"><!--<hr />--></xsl:if>
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
