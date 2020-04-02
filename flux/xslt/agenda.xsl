<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl">
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
    <xsl:for-each select="/offre/enfants/offre[@rel='agenda']">
      <xsl:sort select="php:function('date_eu_to_my',string(horaires/horaire/horline[libelle='date-heure']/date_deb[1]))" />
      <div class="fiche-enfant-h" >
          <xsl:copy-of select="php:function('xsl_event_date', horaires/horaire/horline[libelle='date-heure'] )"/>    
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
            <xsl:if test="*/*[@lot='lot_environ']">
              <div class="offre-environ">
                <xsl:apply-templates select="*/*[@lot='lot_environ']"/>
              </div>
            </xsl:if>  
          </div>                                                                                                                                                                                    
        </xsl:if>


        <!-- Récupération des services de l'offre -->
        <xsl:if test="*/*[@lot='lot_service']">
          <div class="offre-service" >
            <div class="lot-titre">Services</div>
            <xsl:for-each select="*/*[@lot='lot_service']">
              <xsl:sort select="picto" order="descending" />
              <xsl:sort select="@tri" data-type="number" />
              <xsl:apply-templates select="."/>
            </xsl:for-each>
          </div> 
        </xsl:if>     



        <!-- Récupération des horaires de l'offre -->
        <xsl:if test="horaires[not(@lot)]|*/*[@lot='lot_horaire']">                
          <div class="offre-horaire">
            <div class="lot-titre">Horaires</div>
            <xsl:for-each select="horaires|*/*[@lot='lot_horaire']">
              <xsl:sort select="@tri" data-type="number" />
              <xsl:apply-templates select="."/>
            </xsl:for-each>
          </div>                 
        </xsl:if>




        <!-- Données annuelles tarifs + horaires + descriptions -->

        <xsl:variable name="maint" select="$year_now" />
        <xsl:variable name="apres" select="$year_now + 1" />

        <!-- Récupération des tarifs de l'offre -->
        <xsl:if test="*/*[@lot='lot_tarif']">
                 
          <div class="offre-tarif" >  
            <div class="lot-titre">Tarifs</div> 
                    
                    
            <!-- Récupération des tarifs de l'année en cours -->
                    
            <xsl:if test="tarifs/tarif[@an=$maint]|*/*[@lot='lot_tarif' and substring(date_fin,1,4)=$maint]|*/*[@lot='lot_charges' and substring(date_fin,1,4)=$maint]">
              <div class="groupeannuel">    
                <xsl:if test="tarifs/tarif[@an=$maint and @lot='lot_tarif']|*[not(tarif)]/*[@lot='lot_tarif' and substring(date_fin,1,4)=$maint]">
                  <div class="lot-titre">Tarifs <xsl:value-of select="$maint" /></div>
                  <xsl:for-each select="tarifs/tarif[@an=$maint and @lot='lot_tarif']|*[not(tarif)]/*[@lot='lot_tarif' and substring(date_fin,1,4)=$maint]">
                    <xsl:sort select="@an" data-type="number" order="descending"  />
                    <xsl:sort select="@tri" data-type="number" order="ascending"  />
                    <xsl:apply-templates select="."/>
                  </xsl:for-each>
                </xsl:if>
                <!-- Récupération des charges de l'offre de l'année en cours -->
                <xsl:if test="*/*[@lot='lot_charges' and substring(date_fin,1,4)=$maint]">
                  <div class="offre-charges" >  
                    <div class="lot-titre">Charges <xsl:value-of select="$maint" /></div>                   
                    <xsl:for-each select="*/*[@lot='lot_charges' and substring(date_fin,1,4)=$maint]">
                      <xsl:sort select="@tri" data-type="number" />
                      <xsl:apply-templates select="."/>
                    </xsl:for-each>   
                  </div>                  
                </xsl:if>                   
              </div>
            </xsl:if>
                    
            <!-- Récupération des tarifs de l'année prochaine -->
                    
            <xsl:if test="tarifs/tarif[@an=$apres]|*/*[@lot='lot_tarif' and substring(date_fin,1,4)=$apres]|*/*[@lot='lot_charges' and substring(date_fin,1,4)=$apres]">
              <div class="groupeannuel">    
                    
                <xsl:if test="tarifs/tarif[@an=$apres and @lot='lot_tarif']|*[not(tarif)]/*[@lot='lot_tarif' and substring(date_fin,1,4)=$apres]">
                  <div class="lot-titre">Tarifs <xsl:value-of select="$apres" /></div>
                  <xsl:for-each select="tarifs/tarif[@an=$apres and @lot='lot_tarif']|*[not(tarif)]/*[@lot='lot_tarif' and substring(date_fin,1,4)=$apres]">
                    <xsl:sort select="@an" data-type="number" order="descending"  />
                    <xsl:sort select="@tri" data-type="number" order="ascending"  />
                    <xsl:apply-templates select="."/>
                  </xsl:for-each>
                   
                  <!-- Récupération des charges de l'offre de l'année prochaine -->
                  <xsl:if test="*/*[@lot='lot_charges' and substring(date_fin,1,4)=$apres]">
                    <div class="offre-charges" >  
                      <div class="lot-titre">Charges <xsl:value-of select="$apres" /></div>                   
                      <xsl:for-each select="*/*[@lot='lot_charges' and substring(date_fin,1,4)=$apres]">
                        <xsl:sort select="@tri" data-type="number" />
                        <xsl:apply-templates select="."/>
                      </xsl:for-each>   
                    </div>                  
                  </xsl:if> 
                   
                </xsl:if>
              </div>                  
            </xsl:if>
          </div>
        </xsl:if>  
        
      </div>
      <div class='clearfix'> </div>
    </xsl:for-each>
    
    
    
        
  </xsl:template>

</xsl:stylesheet>
