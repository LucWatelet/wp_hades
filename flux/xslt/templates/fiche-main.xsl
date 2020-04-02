<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template name="fiche-main">
    <xsl:text>[hades_id]</xsl:text><xsl:value-of select="./attribute::id" /><xsl:text>[/hades_id]</xsl:text>
    <xsl:text>[post_id/]</xsl:text>
    <!--
    <xsl:if test="count(medias/media[attribute::ext='jpg' or attribute::ext='png']) > 0">
      <xsl:text>[hades_gallery ids="</xsl:text>
      <xsl:for-each select="medias/media[attribute::ext='jpg' or attribute::ext='png']">
        <xsl:value-of select="url" /><xsl:text>,</xsl:text>
      </xsl:for-each>
      <xsl:text>"]</xsl:text>
    </xsl:if>
    -->
    <!--<h1><xsl:call-template name="titre" /><xsl:text>&#160;</xsl:text><xsl:call-template name="flag" /><small>--><!--<a href="?hadesoff=hadesoff_id{./attribute::id}"><xsl:value-of select="./attribute::id" /></a>--><!--</small></h1>-->
    <ul class="lot_label">
      <xsl:apply-templates select="attributs/attribut[attribute::lot='lot_label' and attribute::id!='lb_lcpn']">
        <xsl:sort order="ascending" data-type="number" select="@tri" />
      </xsl:apply-templates>
      <xsl:if test="(contacts/contact/communications/communication[./lib=concat('url_rsv_', $lg)])[1]">
        <li>
          <span style="text-align: center;">
            <a href="{(contacts/contact/communications/communication[./lib=concat('url_rsv_', $lg)])[1]/val}"><img src="http://www.luxembourg-belge.be/lae/services1.0/plugins/laetis/diffusio-254/ext-ftlb_site_SQL3c/assets/fonds/resa.png" /><!--<br /><span style="font-size:8px; text-decoration: none;"><xsl:value-of select="(contacts/contact/communications/communication[./lib=concat('url_rsv_', $lg)])[1]/lib[attribute::lg=$lg]" /></span>--></a>
          </span>
        </li>
      </xsl:if>
    </ul>
    <div><!--
      <xsl:if test="count(descendant::offre[attribute::rel='annexe_e' or attribute::rel='annexe_p']) > 0">
        <dl class="alignright hades-dl">
          <dt><xsl:call-template name="i18n-titre"><xsl:with-param name="titre" select="'egalement'" /></xsl:call-template></dt>
          <dd>
            <ul>
              <xsl:for-each select="descendant::offre[attribute::rel='annexe_e' or attribute::rel='annexe_p']">
                <xsl:sort order="ascending" data-type="number" select="@tri" />
                <xsl:call-template name="fiche-annexe" />
              </xsl:for-each>
            </ul>
          </dd>
        </dl>
      </xsl:if>-->
      <xsl:call-template name="groupe-descriptions" />
    </div>
    <xsl:call-template name="groupe-medias-wordpress-carousel" />
    <!--<div class="clearfix"></div>-->
    <!--<xsl:call-template name="groupe-medias-carousel"><xsl:with-param name="carousel-style" select="'carousel-main'" /></xsl:call-template>-->
    <xsl:call-template name="groupe-medias" />
    <xsl:call-template name="groupe-accueils" />
    <!--<xsl:call-template name="groupe-localisations" />-->
    <xsl:call-template name="groupe-activites" />
    <xsl:call-template name="groupe-artisans" />
    <xsl:call-template name="groupe-capacites" />
    <xsl:call-template name="groupe-cartos" />
    <xsl:call-template name="groupe-charges" />
    <xsl:call-template name="groupe-cuisines" />
    <xsl:call-template name="groupe-environs" />
    <xsl:call-template name="groupe-equ_grps" />
    <xsl:call-template name="groupe-equipements" />
    <xsl:call-template name="groupe-horaires" />
    <xsl:call-template name="groupe-infos" />
    <xsl:call-template name="groupe-mices" />
    <xsl:call-template name="groupe-paiements" />
    <xsl:call-template name="groupe-pmrs" />
    <xsl:call-template name="groupe-publics" />
    <xsl:call-template name="groupe-restricts" />
    <xsl:call-template name="groupe-ser_grps" />
    <xsl:call-template name="groupe-services" />
    <xsl:call-template name="groupe-tarifs" />
    <xsl:call-template name="groupe-contacts" />
    <!--<xsl:call-template name="groupe-geocodes-osm" />-->
    <xsl:if test="$isgooglemapenabled"><xsl:call-template name="groupe-geocodes" /></xsl:if>
    <xsl:if test="$istripadvisorenabled"><xsl:call-template name="groupe-trip-advisor" /></xsl:if>
  </xsl:template>

</xsl:stylesheet>
