<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template match="val">
  </xsl:template>

  <!--<xsl:template match="val" mode="badgepulldisabled">
    <xsl:text>&#160;</xsl:text><span class="badge alignright" style="float: none !important;"><xsl:value-of select="." /><xsl:call-template name="unit" /></span>
  </xsl:template>-->

  <xsl:template name="unit">
    <xsl:text>&#8239;</xsl:text>
    <xsl:if test=".././attribute::id='superficie'">
      <xsl:choose>
        <xsl:when test="$lg='fr'">ha.</xsl:when>
        <xsl:when test="$lg='en'"></xsl:when>
        <xsl:when test="$lg='nl'"></xsl:when>
        <xsl:when test="$lg='de'"></xsl:when>
      </xsl:choose>
    </xsl:if>

    <xsl:if test=".././attribute::id='duree_activi'">
      <xsl:choose>
        <xsl:when test="$lg='fr'">h</xsl:when>
        <xsl:when test="$lg='en'">h</xsl:when>
        <xsl:when test="$lg='nl'">u.</xsl:when>
        <xsl:when test="$lg='de'"></xsl:when>
      </xsl:choose>
    </xsl:if>

    <xsl:if test=".././attribute::id='grp_min' or
                  .././attribute::id='grp_max'">
      <xsl:choose>
        <xsl:when test="$lg='fr'">p</xsl:when>
        <xsl:when test="$lg='en'">p</xsl:when>
        <xsl:when test="$lg='nl'"></xsl:when>
        <xsl:when test="$lg='de'"></xsl:when>
      </xsl:choose>
    </xsl:if>

    <xsl:if test=".././attribute::id='dist_max' or
                  .././attribute::id='dist_min' or
                  .././attribute::id='dist_jou_min' or
                  .././attribute::id='dist_jou_max' or
                  .././attribute::id='distance'">km</xsl:if>

    <xsl:if test=".././attribute::id='nb_ps_tht' or
                  .././attribute::id='nb_ps_board' or
                  .././attribute::id='nb_ps_recpt' or
                  .././attribute::id='nb_ps_audi' or
                  .././attribute::id='nb_ps_semin' or
                  .././attribute::id='nb_ps_bquet' or
                  .././attribute::id='nb_ps_u'">
      <xsl:choose>
        <xsl:when test="$lg='fr'">p.</xsl:when>
        <xsl:when test="$lg='en'">s.</xsl:when>
        <xsl:when test="$lg='nl'">p.</xsl:when>
        <xsl:when test="$lg='de'"></xsl:when>
      </xsl:choose>
    </xsl:if>

    <xsl:if test=".././attribute::id='suf_expo' or
                  .././attribute::id='suf_recpt' or
                  .././attribute::id='suf_board'">m²</xsl:if>

    <xsl:if test=".././attribute::id='ht_plafond' or
                  .././attribute::id='altitude' or
                  .././attribute::id='taille_min'">m</xsl:if>

    <!--
    <xsl:if test=".././attribute::id='nb_lvb'"
                                                >&#8239;u</xsl:if> -->
    <xsl:if test=".././attribute::id='age_min'">
      <xsl:choose>
        <xsl:when test="$lg='fr'">ans</xsl:when>
        <xsl:when test="$lg='en'">yo.</xsl:when>
        <xsl:when test="$lg='nl'"></xsl:when>
        <xsl:when test="$lg='de'"></xsl:when>
      </xsl:choose>
    </xsl:if>
  </xsl:template>

</xsl:stylesheet>
