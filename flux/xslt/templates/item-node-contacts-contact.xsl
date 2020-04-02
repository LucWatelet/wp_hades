<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template match="contacts/contact[not(./lib='proprio')]">
    <dl class="contact">
      <dt>
        <strong>
          <xsl:choose>
            <xsl:when test="lib='contact'"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span><xsl:text>&#160;</xsl:text></xsl:when>
            <xsl:when test="lib='ap'"><span class="glyphicon glyphicon-home" aria-hidden="true"></span><xsl:text>&#160;</xsl:text></xsl:when>
            <xsl:when test="lib='proprio'"><span class="glyphicon glyphicon-user" aria-hidden="true"></span><xsl:text>&#160;</xsl:text></xsl:when>
          </xsl:choose>
          <xsl:value-of select="lib[attribute::lg=$lg]" />
        </strong>
      </dt>
      <dd>
        <ul class="list-unstyled">
          <xsl:choose>
            <xsl:when test="societe">
              <li><strong><xsl:value-of select="societe" /></strong></li>
              <xsl:if test="noms or prenoms">
                <li><xsl:value-of select="concat(civilite, ' ', noms, ' ', prenoms)" /></li>
              </xsl:if>
            </xsl:when>
            <xsl:otherwise>
              <xsl:if test="noms or prenoms">
                <li><strong><xsl:value-of select="concat(civilite, ' ', noms, ' ', prenoms)" /></strong></li>
              </xsl:if>
            </xsl:otherwise>
          </xsl:choose>
          <xsl:if test="not(./lib='contact')">
            <li>
              <xsl:if test="adresse">
                <xsl:value-of select="adresse" /><xsl:if test="numero">, <xsl:value-of select="numero" /></xsl:if><br />
              </xsl:if>
              <xsl:if test="pays or postal or l_nom">
                <xsl:value-of select="pays" /><xsl:if test="postal"> - </xsl:if><xsl:value-of select="postal" /><xsl:text>&#160;</xsl:text><xsl:value-of select="l_nom" />
              </xsl:if>
            </li>
          </xsl:if>
          <xsl:if test="not(./lib='ap')">
            <xsl:for-each select="communications/communication">
              <xsl:sort order="ascending" data-type="number" select="@tri" />
              <li>
                <xsl:choose>
                  <xsl:when test="starts-with(lib, 'url') and not(lib='url_mobi') and not(starts-with(lib, 'url_rsv'))">
                    <span class="glyphicon glyphicon-globe" aria-hidden="true"></span>
                    <xsl:choose>
                      <xsl:when test="starts-with(val, 'http://www.facebook')"><xsl:text>&#160;</xsl:text><a href="{val}"><xsl:value-of select="substring-after(val, 'http://www.')" /></a></xsl:when>
                      <xsl:when test="starts-with(val, 'https://www.facebook')"><xsl:text>&#160;</xsl:text><a href="{val}"><xsl:value-of select="substring-after(val, 'https://www.')" /></a></xsl:when>
                      <xsl:when test="starts-with(val, 'http://www.')"><xsl:text>&#160;</xsl:text><a href="{val}"><xsl:value-of select="substring-after(val, 'http://')" /></a></xsl:when>
                      <xsl:when test="starts-with(val, 'https://www.')"><xsl:text>&#160;</xsl:text><a href="{val}"><xsl:value-of select="substring-after(val, 'https://')" /></a></xsl:when>
                      <xsl:otherwise><xsl:text>&#160;</xsl:text><a href="{val}"><xsl:value-of select="val" /></a></xsl:otherwise>
                    </xsl:choose>
                  </xsl:when>
                  <xsl:when test="lib='mail'"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span><xsl:text>&#160;</xsl:text><a href="mailto:{val}"><xsl:value-of select="val" /></a></xsl:when>
                  <xsl:when test="starts-with(lib, 'tel')"><span class="glyphicon glyphicon-earphone" aria-hidden="true"></span><xsl:text>&#160;</xsl:text><xsl:value-of select="val" /></xsl:when>
                  <xsl:when test="lib='gsm'"><span class="glyphicon glyphicon-phone" aria-hidden="true"></span><xsl:text>&#160;</xsl:text><xsl:value-of select="val" /></xsl:when>
                  <xsl:when test="lib='url_mobi'"><span class="glyphicon glyphicon-phone" aria-hidden="true"></span><xsl:text>&#160;</xsl:text><a href="{val}"><xsl:value-of select="val" /></a></xsl:when>
                  <xsl:when test="lib='fax'"><span class="fa fa-fax"></span><xsl:text>&#160;</xsl:text><xsl:value-of select="val" /></xsl:when>
                  <xsl:when test="starts-with(lib, 'url_rsv')">
                    <xsl:if test="contains(lib, $lg)">
                      <span class="glyphicon glyphicon-globe" aria-hidden="true"></span><xsl:text>&#160;</xsl:text><a href="{val}"><xsl:value-of select="lib[attribute::lg=$lg]" /></a>
                    </xsl:if>
                  </xsl:when>
                  <xsl:otherwise><xsl:value-of select="val" /></xsl:otherwise>
                </xsl:choose>
              </li>
            </xsl:for-each>
          </xsl:if>
        </ul>
      </dd>
    </dl>
    <xsl:if test="remarque">
      <hr />
      <p>
        <em><xsl:value-of select="remarque[attribute::lg=$lg]"/></em>
      </p>
    </xsl:if>
  </xsl:template>

</xsl:stylesheet>
