<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template name="helper-carousel-image">
    <xsl:param name="activeclass" />
    <xsl:variable name="size" select="''" /> <!-- '/thumb250', '/thumb125', '' -->
    <!-- use a template
    <xsl:choose>
      <xsl:when test="../../../../parents"><xsl:param name="size" select="'/thumb250'" /></xsl:when>
      <xsl:when test="../../../../enfants"><xsl:param name="size" select="'/thumb125'" /></xsl:when>
      <xsl:otherwise><xsl:param name="size" select="''" /></xsl:otherwise>
    </xsl:choose>
    -->
    <xsl:variable name="src" select="concat(substring-before(url, 'stock_doc'), 'stock_doc', $size, substring-after(url, 'stock_doc'))" />
    <!--<div class="item {$activeclass}" style="background-image: url('{$src}'); background-size: cover;">-->
      <div class="item {$activeclass}">
        <img src="{$src}" alt="offer photo" title="{title}" class="{$activeclass} img-responsive" />
      <div class="carousel-caption">
        <xsl:value-of select="copyright" />
      </div>
      </div>
  </xsl:template>

</xsl:stylesheet>
