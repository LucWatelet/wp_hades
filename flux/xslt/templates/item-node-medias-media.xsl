<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template match="medias/media[attribute::ext!='jpg']">
    <li>
      <span class="glyphicon glyphicon-download-alt"></span><xsl:text>&#160;</xsl:text><a href="{url}"><xsl:value-of select='titre[attribute::lg=$lg]' /></a>
    </li>
  </xsl:template>

</xsl:stylesheet>
