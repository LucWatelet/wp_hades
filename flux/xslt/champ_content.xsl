<?xml version="1.0" encoding="UTF-8"?>

<!--
    Document   : form.xsl
    Created on : 3 mai 2012, 13:38
    Author     : l.watelet
    Description:
        Purpose of transformation follows.
-->

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
  <xsl:output method="html"/>
    
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
  <xsl:include href="include/enfant.xsl"/>

  <!-- création d'un index sur les enfants ayant une relation forte -->
  <xsl:key name="offre-enfant-h" match="/offre/enfants/offre[@typ='h']/lib[@tri]" use="."/> 
  <!-- création d'un index sur les parents ayant une relation forte -->
  <xsl:key name="offre-parent-h" match="/offre/parents/offre[@typ='h']/lib[@tri]" use="."/>
  <!-- création d'un index sur les enfants ayant une relation faible -->
  <xsl:key name="offre-relation-l" match="/offre/enfants/offre[@typ='l']/lib[@tri] | /offre/parents/offre[@typ='l']/lib[@tri]" use="."/>

  <!-- liste des tarifs de référence -->
  <xsl:variable name="prix-ref">par_ps,chb_dej_2ps,wk_bss</xsl:variable>
  <xsl:param name="year_now" select="2017"/>
  <xsl:param name="picto_path" select="/"/>
    
  <xsl:template match="/" name='document'>
    <xsl:apply-templates select="offre"/>
  </xsl:template>

  <xsl:template match="offre">
      
    <div class='offre-contener'>
      <!-- titres et labels -->
      <div class='offre-titre'>

        <!-- Récupération de tous les labels directement sous l'offre ( sans récupérer ceux des offres imbriquées ) -->
        <xsl:if test="*/*[@lot='lot_label']">
          <div class="offre-labels">
            <xsl:for-each select="*/*[@lot='lot_label']">
              <xsl:sort select="@tri" data-type="number" />
              <xsl:apply-templates select="."/>
            </xsl:for-each>
          </div>
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
                
                
        <div class="cadre-info">
          <!-- Récupération des informations de l'offre -->
          <xsl:if test="*/*[@lot='lot_info']">
            <div class="offre-info" >
              <xsl:for-each select="*/*[@lot='lot_info']">
                <xsl:sort select="picto" />
                <xsl:sort select="@tri" data-type="number" />
                <xsl:apply-templates select="."/>
              </xsl:for-each>
            </div>
          </xsl:if> 
        
          <!-- Récupération des langues maitrisées -->
          <xsl:if test="*/*[@lot='lot_accueil']">
            <div class="offre-accueil">
              <div class="lot-titre">Accueil</div>
              <xsl:for-each select="*/*[@lot='lot_accueil']">
                <xsl:sort select="@tri" data-type="number" />
                <xsl:apply-templates select="."/>
              </xsl:for-each>
            </div>
          </xsl:if>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            
                                    
          <!-- Récupération des capacités de l'offre -->
          <xsl:if test="*/*[@lot='lot_capacite']">
            <div class="offre-capacite" >
              <div class="lot-titre">Capacités</div>
              <xsl:for-each select="*/*[@lot='lot_capacite']">
                <xsl:sort select="@tri" data-type="number" />
                <div class="cell">
                  <xsl:apply-templates select="."/>
                </div>
              </xsl:for-each>
            </div>
          </xsl:if>

          <!-- Récupération des type de cuisines de l'offre -->
          <xsl:if test="*/*[@lot='lot_cuisine']">
            <div class="offre-cuisine">
              <div class="lot-titre">Types de cuisine</div>
              <xsl:for-each select="*/*[@lot='lot_cuisine']">
                <xsl:sort select="@tri" data-type="number" />
                <xsl:apply-templates select="."/>
              </xsl:for-each>
            </div>               
          </xsl:if>

          <!-- Récupération des équipements de l'offre -->
          <xsl:if test="*/*[@lot='lot_equip']">
            <div class="offre-equip" >
              <div class="lot-titre">Équipements</div>
              <xsl:for-each select="*/*[@lot='lot_equip']">
                <xsl:sort select="picto" order="descending"/>
                <xsl:sort select="@tri" data-type="number" />
                <xsl:apply-templates select="."/>
              </xsl:for-each>
            </div>
          </xsl:if>
          <!-- Récupération des équipements spécifiques aux groupes de l'offre -->
          <xsl:if test="*/*[@lot='lot_equ_grp']">
            <div class='offre-equip-grp'>
              <div class="lot-titre">Equipements pour groupes</div>
              <xsl:for-each select="*/*[@lot='lot_equ_grp']">
                <xsl:sort select="@tri" data-type="number" />
                <xsl:apply-templates select="."/>
              </xsl:for-each>
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
                   
          <!-- Récupération des services spécifiques aux groupes de l'offre -->        
          <xsl:if test="*/*[@lot='lot_ser_grp']">
            <div class="offre-service-grp" >
              <div class="lot-titre">Services pour groupes</div>
              <xsl:for-each select="//*[@lot='lot_ser_grp']">
                <xsl:sort select="@tri" data-type="number" />
                <xsl:apply-templates select="."/>
              </xsl:for-each>
            </div> 
          </xsl:if>
            
          <!-- Informations spécifiques au MICE de l'offre -->
          <xsl:if test="*/*[@lot='lot_mice']">             
            <div class="offre-mice">
              <div class="lot-titre">M.I.C.E.</div>
              <ul>
                <xsl:for-each select="*/*[@lot='lot_mice']">
                  <xsl:sort select="@tri" data-type="number" />
                  <li>
                    <xsl:apply-templates select="."/>
                  </li>
                </xsl:for-each>
              </ul>
            </div>
          </xsl:if>
                    
          <!-- Récupération des activités de l'offre -->
          <xsl:if test="*/*[@lot='lot_activite']">
            <div class="offre-activite" >
              <div class="lot-titre">Activité</div>
              <xsl:for-each select="*/*[@lot='lot_activite']">
                <xsl:sort select="picto" order="descending" />
                <xsl:sort select="@tri" data-type="number" />
                <xsl:apply-templates select="."/>
              </xsl:for-each>
            </div> 
          </xsl:if>

          <!-- Récupération des restrictions de l'offre -->
          <xsl:if test="*/*[@lot='lot_restrict']">             
            <div class="offre-service">
              <div class="lot-titre">Restrictions</div>
              <xsl:for-each select="*/*[@lot='lot_restrict']">
                <xsl:sort select="picto" order="descending"/>
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

        <!-- Récupération des médias de l'offre --> 
        <xsl:if test="medias">                                   
					<div class="offres-medias">
            <xsl:for-each select="medias">
              <xsl:sort select="@ord" data-type="number" />
              <xsl:apply-templates/>
            </xsl:for-each>
          </div>                                                
        </xsl:if>  
  
    
        <!-- Affichage de parents à relation forte  -->
 
<!--         <xsl:if test="*/offre/lib[generate-id() = generate-id(key('offre-parent-h',.)[1])]">
          <div class="cadre-parents-h">
            <xsl:for-each select="*/offre/lib[generate-id() = generate-id(key('offre-parent-h',.)[1])]">
              <xsl:sort select="@tri" data-type="number" />
              <div class='sub-offre-titre'>
                <xsl:choose>
                  <xsl:when test="../lib[@lg='fr']">
                    <xsl:value-of select="../lib[@lg='fr']"/>
                  </xsl:when>
                  <xsl:otherwise>
                    <xsl:value-of select="../lib_court"/>
                  </xsl:otherwise>
                </xsl:choose>
              </div>
              <xsl:for-each select="key('offre-parent-h',.)">
                <div class='sub-offre'>
                  <xsl:apply-templates select="../."/>
                </div>
              </xsl:for-each>
            </xsl:for-each>                                                                                               
          </div>
        </xsl:if>-->

        <!-- Affichage d'enfants à relation forte  -->
   <!--     <xsl:if test="*/offre/lib[generate-id() = generate-id(key('offre-enfant-h',.)[1])]">
          <div class="cadre-enfants-h">
            <xsl:for-each select="*/offre/lib[generate-id() = generate-id(key('offre-enfant-h',.)[1])]">
              <xsl:sort select="@tri" data-type="number" />
              <div class='sub-offre-titre'>
                <xsl:choose>
                  <xsl:when test="../lib[@lg='fr']">
                    <xsl:value-of select="../lib[@lg='fr']"/>
                  </xsl:when>
                  <xsl:otherwise>
                    <xsl:value-of select="../lib_court"/>
                  </xsl:otherwise>
                </xsl:choose>
              </div>
              <xsl:for-each select="key('offre-enfant-h',.)">
                <div class='sub-offre'>
                  <xsl:apply-templates select="../."/>
                </div>
              </xsl:for-each>
            </xsl:for-each>                                                                                               
          </div>
        </xsl:if>  -->              
      </div>
     
    </div>
  </xsl:template>
</xsl:stylesheet>
