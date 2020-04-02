<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template name="i18n-titre">
    <xsl:param name="titre" />
    <xsl:choose>
      <xsl:when test="$titre='lot_accueil'">
        <xsl:choose>
          <xsl:when test="$lg='fr'">Accueil</xsl:when>
          <xsl:when test="$lg='en'"><xsl:value-of select="$titre" /></xsl:when>
          <xsl:when test="$lg='de'"><xsl:value-of select="$titre" /></xsl:when>
          <xsl:when test="$lg='nl'"><xsl:value-of select="$titre" /></xsl:when>
        </xsl:choose>
      </xsl:when>
      <xsl:when test="$titre='lot_activite'">
        <xsl:choose>
          <xsl:when test="$lg='fr'">Activités</xsl:when>
          <xsl:when test="$lg='en'">Activities</xsl:when>
          <xsl:when test="$lg='de'"><xsl:value-of select="$titre" /></xsl:when>
          <xsl:when test="$lg='nl'"><xsl:value-of select="$titre" /></xsl:when>
        </xsl:choose>
      </xsl:when>
      <xsl:when test="$titre='lot_artisan'">
        <xsl:choose>
          <xsl:when test="$lg='fr'">Artisan</xsl:when>
          <xsl:when test="$lg='en'">Artisan</xsl:when>
          <xsl:when test="$lg='de'"><xsl:value-of select="$titre" /></xsl:when>
          <xsl:when test="$lg='nl'"><xsl:value-of select="$titre" /></xsl:when>
        </xsl:choose>
      </xsl:when>
      <xsl:when test="$titre='lot_autre'">
        <xsl:choose>
          <xsl:when test="$lg='fr'">Autres</xsl:when>
          <xsl:when test="$lg='en'">Others(miscellaneous)</xsl:when>
          <xsl:when test="$lg='de'"><xsl:value-of select="$titre" /></xsl:when>
          <xsl:when test="$lg='nl'"><xsl:value-of select="$titre" /></xsl:when>
        </xsl:choose>
      </xsl:when>
      <xsl:when test="$titre='lot_capacite'">
        <xsl:choose>
          <xsl:when test="$lg='fr'">Capacités</xsl:when>
          <xsl:when test="$lg='en'">Holding bay</xsl:when>
          <xsl:when test="$lg='de'">Gurñelstadt</xsl:when>
          <xsl:when test="$lg='nl'">Kapacitkruik</xsl:when>
        </xsl:choose>
      </xsl:when>
      <xsl:when test="$titre='lot_carto'">
        <xsl:choose>
          <xsl:when test="$lg='fr'">Cartographie</xsl:when>
          <xsl:when test="$lg='en'"><xsl:value-of select="$titre" /></xsl:when>
          <xsl:when test="$lg='de'"><xsl:value-of select="$titre" /></xsl:when>
          <xsl:when test="$lg='nl'"><xsl:value-of select="$titre" /></xsl:when>
        </xsl:choose>
      </xsl:when>
      <xsl:when test="$titre='lot_categorie'">
        <xsl:choose>
          <xsl:when test="$lg='fr'">Catégories</xsl:when>
          <xsl:when test="$lg='en'">Categories</xsl:when>
          <xsl:when test="$lg='de'"><xsl:value-of select="$titre" /></xsl:when>
          <xsl:when test="$lg='nl'"><xsl:value-of select="$titre" /></xsl:when>
        </xsl:choose>
      </xsl:when>
      <xsl:when test="$titre='lot_charges'">
        <xsl:choose>
          <xsl:when test="$lg='fr'">Charges</xsl:when>
          <xsl:when test="$lg='en'">Fees</xsl:when>
          <xsl:when test="$lg='de'">Fäech</xsl:when>
          <xsl:when test="$lg='nl'">Bosterlijk</xsl:when>
        </xsl:choose>
      </xsl:when>
      <xsl:when test="$titre='lot_contact'">
        <xsl:choose>
          <xsl:when test="$lg='fr'">Contacts</xsl:when>
          <xsl:when test="$lg='en'">Contact</xsl:when>
          <xsl:when test="$lg='de'"><xsl:value-of select="$titre" /></xsl:when>
          <xsl:when test="$lg='nl'"><xsl:value-of select="$titre" /></xsl:when>
        </xsl:choose>
      </xsl:when>
      <xsl:when test="$titre='lot_cuisine'">
        <xsl:choose>
          <xsl:when test="$lg='fr'">Cuisine</xsl:when>
          <xsl:when test="$lg='en'"><xsl:value-of select="$titre" /></xsl:when>
          <xsl:when test="$lg='de'"><xsl:value-of select="$titre" /></xsl:when>
          <xsl:when test="$lg='nl'"><xsl:value-of select="$titre" /></xsl:when>
        </xsl:choose>
      </xsl:when>
      <xsl:when test="$titre='lot_description'">
        <xsl:choose>
          <xsl:when test="$lg='fr'">Description</xsl:when>
          <xsl:when test="$lg='en'">Description</xsl:when>
          <xsl:when test="$lg='de'"><xsl:value-of select="$titre" /></xsl:when>
          <xsl:when test="$lg='nl'"><xsl:value-of select="$titre" /></xsl:when>
        </xsl:choose>
      </xsl:when>
      <xsl:when test="$titre='lot_environ'">
        <xsl:choose>
          <xsl:when test="$lg='fr'">Aux alentours</xsl:when>
          <xsl:when test="$lg='en'"><xsl:value-of select="$titre" /></xsl:when>
          <xsl:when test="$lg='de'"><xsl:value-of select="$titre" /></xsl:when>
          <xsl:when test="$lg='nl'"><xsl:value-of select="$titre" /></xsl:when>
        </xsl:choose>
      </xsl:when>
      <xsl:when test="$titre='lot_equ_grp'">
        <xsl:choose>
          <xsl:when test="$lg='fr'">Équipement pour groupes</xsl:when>
          <xsl:when test="$lg='en'"><xsl:value-of select="$titre" /></xsl:when>
          <xsl:when test="$lg='de'"><xsl:value-of select="$titre" /></xsl:when>
          <xsl:when test="$lg='nl'"><xsl:value-of select="$titre" /></xsl:when>
        </xsl:choose>
      </xsl:when>
      <xsl:when test="$titre='lot_equip'">
        <xsl:choose>
          <xsl:when test="$lg='fr'">Équipement</xsl:when>
          <xsl:when test="$lg='en'"><xsl:value-of select="$titre" /></xsl:when>
          <xsl:when test="$lg='de'"><xsl:value-of select="$titre" /></xsl:when>
          <xsl:when test="$lg='nl'"><xsl:value-of select="$titre" /></xsl:when>
        </xsl:choose>
      </xsl:when>
      <xsl:when test="$titre='lot_geocode'">
        <xsl:choose>
          <xsl:when test="$lg='fr'">Géolocalisation</xsl:when>
          <xsl:when test="$lg='en'">Geolocation</xsl:when>
          <xsl:when test="$lg='de'"><xsl:value-of select="$titre" /></xsl:when>
          <xsl:when test="$lg='nl'"><xsl:value-of select="$titre" /></xsl:when>
        </xsl:choose>
      </xsl:when>
      <xsl:when test="$titre='lot_horaire'">
        <xsl:choose>
          <xsl:when test="$lg='fr'">Ouverture</xsl:when>
          <xsl:when test="$lg='en'">Openings</xsl:when>
          <xsl:when test="$lg='de'"><xsl:value-of select="$titre" /></xsl:when>
          <xsl:when test="$lg='nl'"><xsl:value-of select="$titre" /></xsl:when>
        </xsl:choose>
      </xsl:when>
      <xsl:when test="$titre='lot_info'">
        <xsl:choose>
          <xsl:when test="$lg='fr'">Information</xsl:when>
          <xsl:when test="$lg='en'"><xsl:value-of select="$titre" /></xsl:when>
          <xsl:when test="$lg='de'"><xsl:value-of select="$titre" /></xsl:when>
          <xsl:when test="$lg='nl'"><xsl:value-of select="$titre" /></xsl:when>
        </xsl:choose>
      </xsl:when>
      <xsl:when test="$titre='lot_label'">
        <xsl:choose>
          <xsl:when test="$lg='fr'">Labels</xsl:when>
          <xsl:when test="$lg='en'"><xsl:value-of select="$titre" /></xsl:when>
          <xsl:when test="$lg='de'"><xsl:value-of select="$titre" /></xsl:when>
          <xsl:when test="$lg='nl'"><xsl:value-of select="$titre" /></xsl:when>
        </xsl:choose>
      </xsl:when>
      <xsl:when test="$titre='lot_localite'">
        <xsl:choose>
          <xsl:when test="$lg='fr'">Coordonnées GPS</xsl:when>
          <xsl:when test="$lg='en'">GPS coordinates</xsl:when>
          <xsl:when test="$lg='de'"><xsl:value-of select="$titre" /></xsl:when>
          <xsl:when test="$lg='nl'"><xsl:value-of select="$titre" /></xsl:when>
        </xsl:choose>
      </xsl:when>
      <xsl:when test="$titre='lot_media'">
        <xsl:choose>
          <xsl:when test="$lg='fr'">Autres documents</xsl:when>
          <xsl:when test="$lg='en'">Other documents</xsl:when>
          <xsl:when test="$lg='de'"><xsl:value-of select="$titre" /></xsl:when>
          <xsl:when test="$lg='nl'"><xsl:value-of select="$titre" /></xsl:when>
        </xsl:choose>
      </xsl:when>
      <xsl:when test="$titre='lot_media_jpg'">
        <xsl:choose>
          <xsl:when test="$lg='fr'">Photos</xsl:when>
          <xsl:when test="$lg='en'">Pictures</xsl:when>
          <xsl:when test="$lg='de'"><xsl:value-of select="$titre" /></xsl:when>
          <xsl:when test="$lg='nl'"><xsl:value-of select="$titre" /></xsl:when>
        </xsl:choose>
      </xsl:when>
      <xsl:when test="$titre='lot_media_not_jpg'">
        <xsl:choose>
          <xsl:when test="$lg='fr'">Autres documents</xsl:when>
          <xsl:when test="$lg='en'">Other documents</xsl:when>
          <xsl:when test="$lg='de'"><xsl:value-of select="$titre" /></xsl:when>
          <xsl:when test="$lg='nl'"><xsl:value-of select="$titre" /></xsl:when>
        </xsl:choose>
      </xsl:when>
      <xsl:when test="$titre='lot_mice'">
        <xsl:choose>
          <xsl:when test="$lg='fr'">M.I.C.E</xsl:when>
          <xsl:when test="$lg='en'">M.I.C.E</xsl:when>
          <xsl:when test="$lg='de'">M.I.C.E</xsl:when>
          <xsl:when test="$lg='nl'">M.I.C.E</xsl:when>
        </xsl:choose>
      </xsl:when>
      <xsl:when test="$titre='lot_paiement'">
        <xsl:choose>
          <xsl:when test="$lg='fr'">Moyens de paiement</xsl:when>
          <xsl:when test="$lg='en'">Payement method</xsl:when>
          <xsl:when test="$lg='de'"><xsl:value-of select="$titre" /></xsl:when>
          <xsl:when test="$lg='nl'"><xsl:value-of select="$titre" /></xsl:when>
        </xsl:choose>
      </xsl:when>
      <xsl:when test="$titre='lot_pmr'">
        <xsl:choose>
          <xsl:when test="$lg='fr'">Accessibilité</xsl:when>
          <xsl:when test="$lg='en'">Accessibility</xsl:when>
          <xsl:when test="$lg='de'"><xsl:value-of select="$titre" /></xsl:when>
          <xsl:when test="$lg='nl'"><xsl:value-of select="$titre" /></xsl:when>
        </xsl:choose>
      </xsl:when>
      <xsl:when test="$titre='lot_public'">
        <xsl:choose>
          <xsl:when test="$lg='fr'">Public cible</xsl:when>
          <xsl:when test="$lg='en'"><xsl:value-of select="$titre" /></xsl:when>
          <xsl:when test="$lg='de'"><xsl:value-of select="$titre" /></xsl:when>
          <xsl:when test="$lg='nl'"><xsl:value-of select="$titre" /></xsl:when>
        </xsl:choose>
      </xsl:when>
      <xsl:when test="$titre='lot_restrict'">
        <xsl:choose>
          <xsl:when test="$lg='fr'">Restrictions</xsl:when>
          <xsl:when test="$lg='en'">Restrictions</xsl:when>
          <xsl:when test="$lg='de'"><xsl:value-of select="$titre" /></xsl:when>
          <xsl:when test="$lg='nl'"><xsl:value-of select="$titre" /></xsl:when>
        </xsl:choose>
      </xsl:when>
      <xsl:when test="$titre='lot_selection'">
        <xsl:choose>
          <xsl:when test="$lg='fr'">Sélections</xsl:when>
          <xsl:when test="$lg='en'">Selections</xsl:when>
          <xsl:when test="$lg='de'"><xsl:value-of select="$titre" /></xsl:when>
          <xsl:when test="$lg='nl'"><xsl:value-of select="$titre" /></xsl:when>
        </xsl:choose>
      </xsl:when>
      <xsl:when test="$titre='lot_ser_grp'">
        <xsl:choose>
          <xsl:when test="$lg='fr'">Services pour groupe</xsl:when>
          <xsl:when test="$lg='en'"><xsl:value-of select="$titre" /></xsl:when>
          <xsl:when test="$lg='de'"><xsl:value-of select="$titre" /></xsl:when>
          <xsl:when test="$lg='nl'"><xsl:value-of select="$titre" /></xsl:when>
        </xsl:choose>
      </xsl:when>
      <xsl:when test="$titre='lot_service'">
        <xsl:choose>
          <xsl:when test="$lg='fr'">Services diponibles</xsl:when>
          <xsl:when test="$lg='en'"><xsl:value-of select="$titre" /></xsl:when>
          <xsl:when test="$lg='de'"><xsl:value-of select="$titre" /></xsl:when>
          <xsl:when test="$lg='nl'"><xsl:value-of select="$titre" /></xsl:when>
        </xsl:choose>
      </xsl:when>
      <xsl:when test="$titre='lot_tarif'">
        <xsl:choose>
          <xsl:when test="$lg='fr'">Tarifs</xsl:when>
          <xsl:when test="$lg='en'"><xsl:value-of select="$titre" /></xsl:when>
          <xsl:when test="$lg='de'"><xsl:value-of select="$titre" /></xsl:when>
          <xsl:when test="$lg='nl'"><xsl:value-of select="$titre" /></xsl:when>
        </xsl:choose>
      </xsl:when>
      <xsl:when test="$titre='egalement'">
        <xsl:choose>
          <xsl:when test="$lg='fr'">Également</xsl:when>
          <xsl:when test="$lg='en'"><xsl:value-of select="$titre" /></xsl:when>
          <xsl:when test="$lg='de'"><xsl:value-of select="$titre" /></xsl:when>
          <xsl:when test="$lg='nl'"><xsl:value-of select="$titre" /></xsl:when>
        </xsl:choose>
      </xsl:when>
      <xsl:otherwise>
        lot_* ou titre inconnu
      </xsl:otherwise>
    </xsl:choose>
  </xsl:template>

</xsl:stylesheet>
