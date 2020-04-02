<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template name="item-node-tarifs-tarif">
    <tr>
      <td>
        <strong><xsl:value-of select="lib[attribute::lg=$lg]" /></strong><xsl:call-template name="tarif-date" />
        <xsl:call-template name="tarif-remarque" />
        <xsl:call-template name="tarif-texte" />
      </td>
      <td>
        <span class="badge"><xsl:call-template name="tarif-badge" /></span>
      </td>
    </tr>
    <!--
    -->
  </xsl:template>

  <xsl:template name="tarif-texte">
    <xsl:if test="texte">
      <br /><xsl:value-of select="texte[attribute::lg=$lg]" disable-output-escaping="yes" />
    </xsl:if>
  </xsl:template>

  <xsl:template name="tarif-remarque">
    <xsl:if test="remarque">
      <br /><em><xsl:value-of select="remarque[attribute::lg=$lg]" /></em>
    </xsl:if>
  </xsl:template>

  <xsl:template match="tarifs/tarif">
    <p><strong><xsl:value-of select="lib[attribute::lg=$lg]" /></strong><xsl:call-template name="tarif-date" /></p>
    <xsl:if test="texte">
      <p><xsl:value-of select="texte[attribute::lg=$lg]" disable-output-escaping="yes" /></p>
    </xsl:if>
    <p>
      <xsl:if test="remarque">
        <em><xsl:value-of select="remarque[attribute::lg=$lg]"/></em>
      </xsl:if>
      <span class="badge">
        <xsl:call-template name="tarif-badge" />
      </span>
    </p>
    <div class="clearfix"></div>
  </xsl:template>

  <xsl:template name="tarif-date">
    <xsl:choose>
      <xsl:when test="./attribute::dat='ann'">
        <small><xsl:text>&#160;</xsl:text><xsl:value-of select="./attribute::an" /></small>
      </xsl:when>
      <xsl:when test="./attribute::date='dad'">
        <small><xsl:text>&#160;</xsl:text><xsl:value-of select="date_deb"/><xsl:text>&#8239;-&#8239;</xsl:text><xsl:value-of select="date_fin" /></small>
      </xsl:when>
    </xsl:choose>
  </xsl:template>

  <xsl:template name="tarif-badge">
    <xsl:variable name="unit">
      <xsl:if test="pourcent"><xsl:text>&#8239;%</xsl:text></xsl:if>
      <xsl:if test="not(pourcent)"><xsl:text>&#8239;€</xsl:text></xsl:if>
    </xsl:variable>
    <xsl:choose>
      <xsl:when test="min and max='0.00'"><xsl:value-of select="min" /></xsl:when>
      <xsl:when test="min and max and (min != max)"><xsl:value-of select="min"/>&#160;-&#160;<xsl:value-of select="max"/></xsl:when>
      <xsl:when test="min=max"><xsl:value-of select="max" /></xsl:when>
    </xsl:choose>
    <xsl:value-of select="$unit" />
  </xsl:template>

</xsl:stylesheet>
