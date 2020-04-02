<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template name="flag">
    <xsl:for-each select="attributs/attribut[attribute::lot='lot_accueil']">
      <xsl:sort order="ascending" data-type="number" select="@tri" />
      <xsl:choose>
        <xsl:when test="./attribute::id='acc_fr'">
          <span class="f16"><span class="f16 flag fr"></span></span>
        </xsl:when>
        <xsl:when test="./attribute::id='acc_nl'">
          <span class="f16"><span class="f16 flag nl"></span></span>
        </xsl:when>
        <xsl:when test="./attribute::id='acc_de'">
          <span class="f16"><span class="f16 flag de"></span></span>
        </xsl:when>
        <xsl:when test="./attribute::id='acc_en'">
          <span class="f16"><span class="f16 flag en"></span></span>
        </xsl:when>
      </xsl:choose>
    </xsl:for-each>
  </xsl:template>

</xsl:stylesheet>
