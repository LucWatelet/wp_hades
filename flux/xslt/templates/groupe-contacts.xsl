<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template name="groupe-contacts">
    <xsl:variable name="class"      select="'hades-ul-contact'" />
    <xsl:variable name="lot"        select="'lot_contact'" />
    <xsl:variable name="ispictoenabledv"      select="$ispictoenabled" />
    <xsl:if test="count(attributs/attribut[attribute::lot=$lot])        > 0 or
                  count(contacts/contact[not(./lib='proprio')])         > 0 or
                  count(descriptions/description[attribute::lot=$lot])  > 0">
      <xsl:if test="count(contacts/contact) > 0">
        <xsl:choose>
          <xsl:when test="./attribute::typ='h' or ./attribute::typ='l'">
            <xsl:apply-templates select="(contacts/contact[not(./lib='proprio')])[1]">
              <xsl:sort order="ascending" data-type="number" select="@tri" />
            </xsl:apply-templates>
          </xsl:when>
          <xsl:otherwise>
            <xsl:apply-templates select="contacts/contact[not(./lib='proprio')]">
              <xsl:sort order="ascending" data-type="number" select="@tri" />
            </xsl:apply-templates>
          </xsl:otherwise>
        </xsl:choose>
      </xsl:if>
      <xsl:if test="count(attributs/attribut[attribute::lot=$lot])       > 0 or
                    count(descriptions/description[attribute::lot=$lot]) > 0 ">
        <dl>
          <dt><xsl:call-template name="i18n-titre"><xsl:with-param name="titre" select="$lot" /></xsl:call-template></dt>
          <xsl:if test="count(attributs/attribut[attribute::lot=$lot]) > 0">
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
            <xsl:if test="count(attributs/attribut[attribute::lot=$lot]) > 0 or count(contacts/contact) > 0"><hr /></xsl:if>
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
    </xsl:if>
  </xsl:template>

</xsl:stylesheet>
