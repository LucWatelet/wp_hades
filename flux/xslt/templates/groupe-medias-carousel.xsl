<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template name="groupe-medias-carousel">
    <xsl:param name="carousel-style" select="''" /> <!-- ARGUMENT: 'carousel-annexe', '' defaults to bootstrap settings -->
    <xsl:variable name="class"      select="'medias'" />
    <xsl:variable name="lot"        select="'lot_media'" />
    <xsl:variable name="ispictoenabledv"      select="$ispictoenabled" />
    <xsl:if test="count(medias/media) > 0" >
<!--  <dl>
        <dt><h3><xsl:call-template name="i18n-titre"><xsl:with-param name="titre" select="concat($lot, '_jpg')" /></xsl:call-template></h3></dt>
        <dd>-->
          <xsl:if test="count(medias/media) > 0">
            <xsl:if test="count(medias/media[attribute::ext='jpg' or attribute::ext='png']) > 0">
              <xsl:variable name="offre-id" select="./@id" />
              <div  id="carousel-generic_{$offre-id}" class="carousel slide center-block {$carousel-style}" data-ride="carousel" data-interval="false">
                <ol class="carousel-indicators">
                  <xsl:for-each select="medias/media[attribute::ext='jpg' or attribute::ext='png']">
                    <xsl:sort order="ascending" data-type="number" select="@ord" />
                    <xsl:choose>
                      <xsl:when test="position()-1=0">
                        <li data-target="#carousel-generic_{$offre-id}" data-slide-to="0" class="active"></li>
                      </xsl:when>
                      <xsl:otherwise>
                        <li data-target="#carousel-generic_{$offre-id}" data-slide-to="{position()-1}"></li>
                      </xsl:otherwise>
                    </xsl:choose>
                  </xsl:for-each>
                </ol>
                <div class="carousel-inner" role="listbox">
                  <xsl:for-each select="medias/media[attribute::ext='jpg' or attribute::ext='png']">
                    <xsl:choose>
                      <xsl:when test="$carousel-style='carousel-125'">
                        <xsl:choose>
                          <xsl:when test="position()-1=0"><xsl:call-template name="helper-carousel-image"><xsl:with-param name="activeclass" select="'active'" /><xsl:with-param name="size" select="'/thumb125'"/><xsl:with-param name="style" select="'thumb-125'"/></xsl:call-template></xsl:when>
                          <xsl:otherwise                 ><xsl:call-template name="helper-carousel-image"><xsl:with-param name="activeclass" select="''" />      <xsl:with-param name="size" select="'/thumb125'"/><xsl:with-param name="style" select="'thumb-125'"/></xsl:call-template></xsl:otherwise>
                        </xsl:choose>
                      </xsl:when>
                      <xsl:when test="$carousel-style='carousel-250'">
                        <xsl:choose>
                          <xsl:when test="position()-1=0"><xsl:call-template name="helper-carousel-image"><xsl:with-param name="activeclass" select="'active'" /><xsl:with-param name="size" select="'/thumb250'"/><xsl:with-param name="style" select="'thumb-250'"/></xsl:call-template></xsl:when>
                          <xsl:otherwise                 ><xsl:call-template name="helper-carousel-image"><xsl:with-param name="activeclass" select="''" />      <xsl:with-param name="size" select="'/thumb250'"/><xsl:with-param name="style" select="'thumb-250'"/></xsl:call-template></xsl:otherwise>
                        </xsl:choose>
                      </xsl:when>
                      <xsl:otherwise>
                        <xsl:choose>
                          <xsl:when test="position()-1=0"><xsl:call-template name="helper-carousel-image"><xsl:with-param name="activeclass" select="'active'" /></xsl:call-template></xsl:when>
                          <xsl:otherwise                 ><xsl:call-template name="helper-carousel-image"><xsl:with-param name="activeclass" select="''" /></xsl:call-template></xsl:otherwise>
                        </xsl:choose>
                      </xsl:otherwise>
                    </xsl:choose>
                  </xsl:for-each>
                </div>

                <!-- Controls -->
                <a class="left carousel-control" href="#carousel-generic_{$offre-id}" role="button" data-slide="prev">
                  <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                  <span class="sr-only">Précédent</span>
                </a>
                <a class="right carousel-control" href="#carousel-generic_{$offre-id}" role="button" data-slide="next">
                  <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                  <span class="sr-only">Suivant</span>
                </a>
              </div>
            </xsl:if> <!-- test="count(medias/media[attribute::ext='jpg']) > 0> -->
          </xsl:if>
<!--        </dd>
      </dl>-->
    </xsl:if>
  </xsl:template>

</xsl:stylesheet>
