<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template name="groupe-horaires">
    <xsl:variable name="class"      select="'hades-ul-horaire'" />
    <xsl:variable name="lot"        select="'lot_horaire'" />
    <xsl:variable name="ispictoenabledv"      select="$ispictoenabled" />
    <xsl:if test="count(attributs/attribut[attribute::lot=$lot])        > 0 or
                  count(horaires/horaire)                               > 0 or
                  count(descriptions/description[attribute::lot=$lot])  > 0">

        <xsl:if test="count(horaires/horaire) > 0">
              <xsl:apply-templates select="horaires/horaire">
                <xsl:sort order="ascending" data-type="number" select="@tri" />
              </xsl:apply-templates>
        </xsl:if>
        <xsl:if test="count(attributs/attribut[attribute::lot=$lot]) > 0">
          <xsl:if test="count(horaires/horaire) > 0"></xsl:if>

            <ul class="{$class}">
              <xsl:for-each select="attributs/attribut[attribute::lot=$lot]">
                <xsl:sort order="ascending" data-type="number" select="@tri" />
                <xsl:call-template name="item-attribute-lot-generic" >
                  <xsl:with-param name="ispictoenabled" select="$ispictoenabledv" />
                </xsl:call-template>
              </xsl:for-each>
            </ul>
        </xsl:if>
        <xsl:if test="count(descriptions/description[attribute::lot=$lot]) > 0">
          <xsl:if test="count(attributs/attribut[attribute::lot=$lot]) > 0 or count(horaires/horaire) > 0"></xsl:if>

              <xsl:for-each select="descriptions/description[attribute::lot=$lot]">
                <xsl:sort order="ascending" data-type="number" select="@tri" />
                <xsl:call-template name="item-description-lot-generic" />
              </xsl:for-each>
        </xsl:if>
    </xsl:if>
  </xsl:template>

</xsl:stylesheet>
