<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:output method="html"
              encoding="UTF-8"
              indent="yes" />
  <xsl:param name="lg" select="'fr'" />

  <xsl:include href="include/categorie.xsl"/>
  <xsl:include href="include/attribut.xsl"/>
  <xsl:include href="include/picto.xsl"/>
  <xsl:include href="include/localisation.xsl"/>
  <xsl:include href="include/description.xsl"/>
  <xsl:include href="include/contact.xsl"/>
  <xsl:include href="include/media.xsl"/>
  <xsl:include href="include/tarif.xsl"/>
  <xsl:include href="include/geocode.xsl"/>
  <xsl:include href="include/horaire.xsl"/>
  <xsl:param name="year_now" select="2019"/>
  <xsl:param name="picto_path" select="/"/>
 
  <xsl:template match="/">
    <xsl:for-each select="/offre/enfants/offre[@typ='h' and @rel='production']">
      <div class="fiche-enfant-h" >
        <h2 class="entry-title">
          <xsl:value-of select="titre[@lg='fr']"/>
        </h2>

        <div class='clearfix'> </div>
        <xsl:if test="medias/media[@ext='jpg']">
          <img class="attachment-post-thumbnail size-post-thumbnail wp-post-image " alt="" >  
            <xsl:attribute name="src">
              <xsl:value-of select="medias/media[@ext='jpg']/url[1]"/>
            </xsl:attribute>
          </img>  
        </xsl:if> 

        <xsl:if test="*/*[@lot='lot_descript'] | */*[@lot='lot_environ']">
          <!-- ouvre le cadre de description si il y a des élément dans le lot description ou environnement -->
          <div class="offre-descript" >                                                    
            <!-- Récupération des descriptifs de l'offre -->
            <xsl:if test="*/*[@lot='lot_descript']">
              <xsl:for-each select="*/*[@lot='lot_descript']">
                <xsl:sort select="picto" />
                <xsl:sort select="@tri" data-type="number" />
                <xsl:apply-templates select="."/>
              </xsl:for-each>
            </xsl:if>
          </div>                                                                                                                                                                                    
        </xsl:if>
      </div>
      <div class='clearfix'> </div>
    </xsl:for-each>
        
  </xsl:template>

</xsl:stylesheet>
