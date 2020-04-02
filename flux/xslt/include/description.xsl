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


    <!-- ============================ DESCRIPTION ============================== -->

    <xsl:template match="description">
        <div >   
            <xsl:attribute name="class">descript <xsl:value-of select="lib"/></xsl:attribute>    
            <xsl:if test="lib[not(@lg) and .!='general' and .!='gn_comp' ]">
                <span class="lib_des">
                    <xsl:value-of select="lib[@lg='fr']"/> :
                </span>            
            </xsl:if>  
         
            <!-- ========================= PARTIE TEMPORELLE  ========================= -->           
           
            <xsl:choose>

                <!--  ANNUEL   -->                
                <xsl:when test="@dat='ann'">        
                    
                </xsl:when>
               
                <!--  TOUJOURS   -->
                <xsl:when test="@dat='tjr'">        

                </xsl:when>                
                
                <!--  FOURCHETTE DE DATES   -->
                <xsl:when test="date_deb and date_fin">        
                    Du <xsl:value-of select="date_deb" /> au <xsl:value-of select="date_fin" /> :
                </xsl:when>

                <!--  A PARTIR DU   -->
                <xsl:when test="date_deb and not(date_fin)">        
                    A partir du <xsl:value-of select="date_deb" /> :
                </xsl:when>
                
                <!--  JUSQU AU   -->
                <xsl:when test="not(date_deb) and date_fin">        
                    Jusqu'au <xsl:value-of select="date_fin" /> :
                </xsl:when>                

            </xsl:choose>
            
            <!-- ========================= PARTIE TEXTUELLE  ========================= -->

            <xsl:choose>
                <!--  TEXTE SIMPLE   --> 
                <xsl:when test="@typ='stxt'">    
                    <xsl:value-of select="texte[@lg='fr']" />
                </xsl:when>
                <!--  TEXTE MULTILIGNE   -->
                <xsl:when test="@typ='mtxt'">
                    <xsl:value-of select="texte[@lg='fr']" />
                </xsl:when>                 
                <!--  TEXTE RICHE  --> 
                <xsl:when test="@typ='rtxt'">
                    <xsl:copy-of select="htmltext[@lg='fr']/*" />
                </xsl:when> 

                <xsl:otherwise>
                    <xsl:value-of select="texte[@lg='fr']" />
                </xsl:otherwise>
            
            </xsl:choose>                        
        </div>   

    </xsl:template>    



</xsl:stylesheet>
