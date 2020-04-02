<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template name="fiche-annexe">
    <li>
      <xsl:value-of select="categories/categorie[1]/lib[attribute::lg=$lg]" />
      <xsl:text>&#160;</xsl:text>
      <a href="?hadesoff=hadesoff_id{./attribute::id}">
        <xsl:call-template name="titre" />
      </a>
      <xsl:text>&#160;</xsl:text>
      <xsl:call-template name="flag" />
    </li>
  </xsl:template>

</xsl:stylesheet>
