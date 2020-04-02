<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template name="groupe-categories">
    <xsl:variable name="class"      select="'hades-ul-categorie'" />
    <xsl:variable name="lot"        select="'lot_categorie'" />
    <xsl:variable name="ispictoenabledv"      select="$ispictoenabled" />
    <xsl:if test="count(attributs/attribut[attribute::lot=$lot])        > 0 or
                  count(categories/categorie)                           > 0 or
                  count(descriptions/description[attribute::lot=$lot])  > 0">
      <dl>
        <dt><xsl:call-template name="i18n-titre"><xsl:with-param name="titre" select="$lot" /></xsl:call-template></dt>
        <xsl:if test="count(categories/categorie) > 0">
          <dd>
            <ul class="{$class}">
              <xsl:apply-templates select="categories/categorie">
                <xsl:sort order="ascending" data-type="number" select="@tri" />
              </xsl:apply-templates>
            </ul>
          </dd>
        </xsl:if>
        <xsl:if test="count(attributs/attribut[attribute::lot=$lot]) > 0">
          <xsl:if test="count(categories/categorie) > 0"><hr /></xsl:if>
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
          <xsl:if test="count(attributs/attribut[attribute::lot=$lot]) > 0 or count(categories/categorie) > 0"><hr /></xsl:if>
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
