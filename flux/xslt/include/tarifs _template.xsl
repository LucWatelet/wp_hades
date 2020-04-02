<?xml version="1.0" encoding="UTF-8"?>

<!--
    Document   : tarifs _template.xsl
    Created on : 23 octobre 2017, 12:40
    Author     : l.watelet
    Description:
        Purpose of transformation follows.
-->

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
  <xsl:output method="html"/>

  <!-- TODO customize transformation rules 
       syntax recommendation http://www.w3.org/TR/xslt 
  -->
  <xsl:template name="tarifs_template" >
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
  </xsl:template>

</xsl:stylesheet>
