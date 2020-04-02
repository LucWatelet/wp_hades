<?xml version="1.0" encoding="UTF-8"?>

<!--
    Document   : form.xsl
    Created on : 3 mai 2012, 13:38
    Author     : l.watelet
    Description: Transformation des informations de type "attribut"
-->

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
  <xsl:output method="html"/>

  <!-- ============================  ATTRIBUTS ============================  --> 

  <xsl:template match="attribut">
        
    <xsl:choose>
      <!-- ============================ lb_lcpn ============================  -->            
      <xsl:when test="@id='lb_lcpn'">
      </xsl:when>
            
      <!-- ============================ lb_cgt ============================  -->                
      <xsl:when test="@id='lb_cgt'">
        <div >
          <xsl:attribute name="class">attribut pic chk</xsl:attribute>
          <xsl:if test="val &gt; 0" >
            <img> 
              <xsl:attribute name="src">
                <xsl:value-of select="$picto_path"/>
                <xsl:text>etoile.gif</xsl:text>
              </xsl:attribute>  
            </img>
          </xsl:if>
          <xsl:if test="val &gt; 1" >
            <img> 
              <xsl:attribute name="src">
                <xsl:value-of select="$picto_path"/>
                <xsl:text>etoile.gif</xsl:text>
              </xsl:attribute>  
            </img>
          </xsl:if>  
          <xsl:if test="val &gt; 2" >
            <img> 
              <xsl:attribute name="src">
                <xsl:value-of select="$picto_path"/>
                <xsl:text>etoile.gif</xsl:text>
              </xsl:attribute>  
            </img>
          </xsl:if> 
          <xsl:if test="val &gt; 3" >
            <img> 
              <xsl:attribute name="src">
                <xsl:value-of select="$picto_path"/>
                <xsl:text>etoile.gif</xsl:text>
              </xsl:attribute>  
            </img>
          </xsl:if> 
          <xsl:if test="val &gt; 4" >
            <img> 
              <xsl:attribute name="src">
                <xsl:value-of select="$picto_path"/>
                <xsl:text>etoile.gif</xsl:text>
              </xsl:attribute>  
            </img>
          </xsl:if>                    
        </div>
      </xsl:when>                

            
      <xsl:when test="@typ='chk' and not(picto)">
        <div >
          <xsl:attribute name="class">attribut npic <xsl:value-of select="@typ"/></xsl:attribute> 
          <xsl:value-of select="lib[@lg='fr']"/> 
        </div>
      </xsl:when>
            
      <xsl:when test="@typ='chk' and picto">
        <div>
          <xsl:attribute name="class">attribut pic <xsl:value-of select="@typ"/></xsl:attribute> 
          <xsl:apply-templates select="picto"/> 
        </div>    
      </xsl:when>

      <xsl:when test="@typ='stxt'">
        <div>
          <xsl:attribute name="class">attribut npic <xsl:value-of select="@typ"/></xsl:attribute>   
          <xsl:value-of select="lib[@lg='fr']"/> :<xsl:value-of select="val" />
          <xsl:value-of select="val/@suf" />
        </div>
      </xsl:when>

      <xsl:when test="@typ='ent'">
        <div>
          <xsl:attribute name="class">attribut npic <xsl:value-of select="@typ"/></xsl:attribute>  
          <xsl:value-of select="lib[@lg='fr']"/> :<xsl:value-of select="val" />
          <xsl:value-of select="val/@suf" />
        </div>
      </xsl:when>            
            
      <xsl:otherwise>
        <div>
          <xsl:attribute name="class">attribut npic <xsl:value-of select="@typ"/></xsl:attribute>  
          <xsl:value-of select="lib[@lg='fr']"/> :<xsl:value-of select="val" />
          <xsl:value-of select="val/@suf" /> 
        </div>
      </xsl:otherwise>

    </xsl:choose>

  </xsl:template>

</xsl:stylesheet>
