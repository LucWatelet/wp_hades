<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template match="localisation/localite">
    <li class="list-group-item">
      <xsl:if test="postal"><xsl:value-of select="postal" />&#160;-&#160;</xsl:if>
      <xsl:choose>
        <xsl:when test="c_nom=l_nom"><xsl:value-of select="l_nom" /></xsl:when>
        <xsl:when test="c_nom and not(l_nom)"><xsl:value-of select="c_nom" /></xsl:when>
        <xsl:when test="not(c_nom) and l_nom"><xsl:value-of select="l_nom" /></xsl:when>
        <xsl:otherwise><xsl:value-of select="concat(l_nom, ' (', c_nom, ')')" /></xsl:otherwise>
      </xsl:choose>
      <xsl:if test="x and y">
        <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span><xsl:text>&#160;</xsl:text><xsl:variable name="geo" select="concat(y,',',x)" /><a href="https://www.google.fr/maps/dir//{$geo}/"><xsl:value-of select="concat('Lg:', x, ', ', 'La:', y)" /></a>
      </xsl:if>
    </li>
  </xsl:template>

</xsl:stylesheet>
