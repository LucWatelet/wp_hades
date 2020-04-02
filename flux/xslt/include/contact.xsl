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
    <xsl:include href="communication.xsl"/>
    
    <xsl:template match="contacts">
        <xsl:apply-templates select="contact"/>
    </xsl:template>
   

    <xsl:template match="contact">
        
        
        <xsl:choose>
            
            <xsl:when test="lib = 'ap'">
                <div>
                    <xsl:if test="adresse">
                        <xsl:value-of select="adresse" />, N°<xsl:value-of select="numero" />
                    </xsl:if>
                    <xsl:if test="boite"> Bte <xsl:value-of select="boite" /> </xsl:if>
                    <xsl:if test="l_nom">
                        <br/>  
                        <xsl:value-of select="postal" /> - <xsl:value-of select="l_nom" />
                    </xsl:if>
                    <xsl:if test="remarque_fr">Remarque :<xsl:value-of select="remarque_fr" /></xsl:if>
                </div>           
            </xsl:when>

            <xsl:when test="lib = 'proprio'" />
           
            
            <xsl:otherwise>
                <div class="offre-contact">
                    <div class="contact-lib"> 
                        <xsl:value-of select="lib[@lg='fr']" />
                    </div>
                    
                    <xsl:if test="noms or prenoms">
                        <p>
                            <xsl:value-of select="civilite" />
                            <xsl:text> </xsl:text>
                            <xsl:value-of select="noms" />
                            <xsl:text> </xsl:text>
                            <xsl:value-of select="prenoms" />
                        </p>
                    </xsl:if>
                    
                    <xsl:if test="societe">
                        <p>
                            <xsl:value-of select="societe" />
                        </p>
                    </xsl:if>
                    
                    <xsl:if test="adresse">
                        <p>
                            <xsl:value-of select="adresse" />, N°<xsl:value-of select="numero" />
                            <xsl:if test="boite"> Bte <xsl:value-of select="boite" /> </xsl:if>
                        </p>    
                        <xsl:if test="l_nom">
                            <p>
                                <xsl:value-of select="postal" /> - <xsl:value-of select="l_nom" />
                            </p>
                        </xsl:if>
                        
                    </xsl:if>
                
                    <xsl:if test="remarque_fr">
                        <p>
                            <xsl:value-of select="remarque_fr" />
                        </p>
                    </xsl:if>
                    <xsl:apply-templates select="communications"/> 
                </div> 
            </xsl:otherwise>
       
        </xsl:choose>
    </xsl:template>
   
</xsl:stylesheet>
