<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template name="helper-header">
    <xsl:param name="url-header" />
    <header style=" background-image: url({$url-header})">
      <div style="text-align: right;">
        <img class="" src="./img/signature-code-marque.png" />
      </div>
    </header>
  </xsl:template>

</xsl:stylesheet>
