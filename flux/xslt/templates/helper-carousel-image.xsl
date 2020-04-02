<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template name="helper-carousel-image">
    <xsl:param name="activeclass" />
    <xsl:param name="size" select="''" /> <!-- '/thumb250', '/thumb125', '' -->
    <xsl:param name="src" select="concat(substring-before(url, 'stock_doc'), 'stock_doc', $size, substring-after(url, 'stock_doc'))" />
    <xsl:param name="style" select="''" /> <!-- 'thumb-250', 'thumb-125', '' -->
    <div class="item {$style} {$activeclass}" style="background-image: url('{$src}');">
      <div class="carousel-caption">
        <xsl:value-of select="copyright" />
      </div>
    </div>
    </xsl:template>

  </xsl:stylesheet>
