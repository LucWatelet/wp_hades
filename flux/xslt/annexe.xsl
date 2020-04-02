<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:output method="html"
    encoding="UTF-8"
    indent="yes" />
  <xsl:param name="lg" select="'fr'" />

  <xsl:param name="ispictoenabled" select="true()" /><!-- paramètre global pouvant être défini par groupe -->
  <xsl:param name="isgooglemapenabled" select="true()" />
  <xsl:param name="istripadvisorenabled" select="true()" />
  <xsl:param name="isnextyearfeeenabled" select="true()" />

  <xsl:include href="templates/list-group-templates.xsl" />
  <xsl:include href="templates/list-helper-templates.xsl" />
  <xsl:include href="templates/list-item-templates.xsl" />

  <xsl:include href="templates/fiche-main.xsl" />
  <xsl:include href="templates/fiche-enfant-agenda.xsl" />

  <xsl:template match="/">
   <!-- <xsl:value-of select="."/>-->
        <xsl:for-each select="/offre/enfants/offre[@rel='annexe_e']|/offre/parents/offre[@rel='annexe_e']">
          <div class="fiche-annexe" >
         <xsl:call-template name="fiche-enfant-agenda" select="." />
         </div>
        </xsl:for-each>
        
  </xsl:template>

</xsl:stylesheet>
