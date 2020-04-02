<?xml version="1.0" encoding="UTF-8"?>

<!--
    Document   : media.xsl
    Created on : 12 juin 2012, 11:50
    Author     : l.watelet
    Description:
        Purpose of transformation follows.
-->

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html"/>

    <!-- ============================  MEDIA ============================  --> 

    <xsl:template match="media">
        <div>
            <xsl:attribute name="class">fiche_media ord<xsl:value-of select="@ord" /></xsl:attribute> 
            <div class="fiche_media_aff">

                <xsl:choose>

                    <xsl:when test="@ext='jpg' or @ext='gif' or @ext='png'">
                        <img class="photo"  >
                            <xsl:attribute name="src">
                                <xsl:value-of select="url" />
                            </xsl:attribute>
                        </img>
                    </xsl:when>

                    <xsl:when test="@ext='pdf' or @ext='doc' or @ext='docx' or @ext='rtf' or @ext='zip' or @ext='txt' or @ext='xls' or @ext='inc'">
                        <img class="doc_icon">
                            <xsl:attribute name="src">/images/doc_<xsl:value-of select="@ext" />.png</xsl:attribute>
                        </img>

                    </xsl:when>
            
                    <xsl:when test="@ext='mp3'">
                        <img class="doc_icon" src="/images/doc_mp3.png"/>
                        <object class="lecteurmp3" data="/outils/flashdir/dewplayer.swf" type="application/x-shockwave-flash">
                            <param name="wmode" value="transparent"/>
                            <param name="flashvars"> 
                                <xsl:attribute name="value">mp3=<xsl:value-of select="url"/></xsl:attribute>
                            </param>
                            <param name="movie"> 
                                <xsl:attribute name="value">/outils/flashdir/dewplayer.swf?mp3=<xsl:value-of select="url"/></xsl:attribute>
                            </param>
                        </object>
                    </xsl:when>
            
                    <xsl:when test="@ext='wav'">
                        <img class="doc_icon" src="/images/doc_wav.png"/>

                        <audio controls="controls" class="lecteurmp3" >
                            <source  type="audio/wav" >
                                <xsl:attribute name="src">
                                    <xsl:value-of select="url" />
                                </xsl:attribute>  
                            </source>
                            Your browser does not support the audio element.
                        </audio> 
                    </xsl:when>
            
                    <xsl:when test="@ext='flv'">
                        <img class="flv_icon" src="/images/filmstrip.png">
                            <xsl:attribute name="id">
                                <xsl:value-of select="@id" />
                            </xsl:attribute>
                        </img>                
                        <object width="150" height="120" class="lecteurflv"  data="/outils/flashdir/dewtube.swf" type="application/x-shockwave-flash" >
                            <xsl:attribute name="id">
                                <xsl:value-of select="@id" />
                            </xsl:attribute> 
                            <param value="true" name="allowFullScreen"/>
                            <param value="/outils/flashdir/dewtube.swf" name="movie"/>
                            <param value="high" name="quality"/>
                            <param value="#000000" name="bgcolor"/>
                            <param name="flashvars"> 
                                <xsl:attribute name="value">movie=<xsl:value-of select="url"/></xsl:attribute>
                            </param>
                        </object>
                
                    </xsl:when>            


                    <xsl:otherwise>
                        <img class="doc_icon">
                            <xsl:attribute name="href">
                                <xsl:value-of select="url" />
                            </xsl:attribute>
                            <xsl:attribute name="src">/images/doc_inc.png</xsl:attribute>
                        </img>
                        <div class='ext'>
                            <xsl:value-of select="@ext" />
                        </div>
                    </xsl:otherwise>

                </xsl:choose>                
            </div>        
            <!-- &copy;-->
            <div class="copyright">
              <xsl:text>(&#xA9; :</xsl:text>
              <xsl:value-of select="copyright"/>
              <xsl:text>)</xsl:text>
                <xsl:value-of select="titre[@lg='fr']"/>
            </div>
        </div>
    </xsl:template>

</xsl:stylesheet>
