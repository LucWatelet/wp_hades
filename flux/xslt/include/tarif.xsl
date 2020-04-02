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

 
    <!-- ============================  TARIF ============================  -->
   
     
    <xsl:template match="tarif"> 
        <div class="tarif_ligne"> 
            <!-- ========================= PARTIE TEMPORELLE  ========================= -->           
            <xsl:choose>
                <!--  FOURCHETTE DE DATES   -->
                <xsl:when test="@dat='dad'">        
                    <div class="tarif_dat">
                        Du <xsl:value-of select="date_deb" /> au <xsl:value-of select="date_fin" />
                    </div>
                </xsl:when>
                
                <!--  ANNUEL   -->                
                <xsl:when test="@dat='ann'">        
                </xsl:when>

                <!--  TOUJOURS   -->
                <xsl:when test="@dat='tjr'">        
                </xsl:when>
                
            </xsl:choose>
      
            <div class="tarif_lib"> 
                <xsl:value-of select="lib[@lg='fr']"/> :
            </div>
            <div class="tarif_prix">
                <xsl:choose>
                    <!--  VALEUR FIXE   -->
                    <xsl:when test="min=max and min!=0">        
                        <xsl:value-of select="min" />
                        <xsl:if test="pourcent=1" >%</xsl:if>
                        <xsl:if test="not(pourcent)" >€</xsl:if>
                        <xsl:if test="suf" >
                            <xsl:value-of select="suf" />
                        </xsl:if>  
                    </xsl:when>
                    <!--  JUSQUE   -->                 
                    <xsl:when test="min=0 and max!=0">
                        jusque <xsl:value-of select="max" />
                        <xsl:if test="pourcent=1" >%</xsl:if>
                        <xsl:if test="not(pourcent)" >€</xsl:if> 
                        <xsl:if test="suf" >
                            <xsl:value-of select="suf" />
                        </xsl:if>
                    </xsl:when>
                    <!--  A PARTIR DE   -->
                    <xsl:when test="min!=0 and max=0">
                        A partir de <xsl:value-of select="min" />
                        <xsl:if test="pourcent=1" >%</xsl:if>
                        <xsl:if test="not(pourcent)" >€</xsl:if>
                        <xsl:if test="suf" >
                            <xsl:value-of select="suf" />
                        </xsl:if>
                    </xsl:when>
                    <!--  FOURCHETTE   -->               
                    <xsl:when test="min!=0 and max!=0">        
                        de <xsl:value-of select="min" /> à <xsl:value-of select="max" />
                        <xsl:if test="pourcent=1" >%</xsl:if>
                        <xsl:if test="not(pourcent)" >€</xsl:if>
                        <xsl:if test="suf" >
                            <xsl:value-of select="suf" />
                        </xsl:if>  
                    </xsl:when>                
                </xsl:choose>
            </div>            
            <!-- ========================= PARTIE TEXTUELLE  ========================= -->
            <xsl:if test="remarque[@lg='fr']">    
                <div class="tarif_rem" >
                    <xsl:value-of select="remarque[@lg='fr']" />
                </div>
            </xsl:if> 
        </div>                   
        
    </xsl:template>

</xsl:stylesheet>
