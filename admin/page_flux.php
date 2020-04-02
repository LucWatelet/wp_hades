<?php
require_once HADES_DIR . '/flux/table_flux.php';

function page_flux()
    {

    $flux = Hades_Flux::get_instance();

    if( isset( $_POST['formaction'] ) && $_POST['formaction'] == "Enregistrer" )
        {

        if( $_POST['flux_id'] > 0 )
            $chk = $flux->save( $_POST );
        else
            $chk = $flux->add_new( $_POST );
        }

    if( $chk )
        {
        ?><div id="message" class="updated below-h2"><p>Mise à jour du flux réussie.</p></div><?php
        }

    $loadtable = TRUE;

    switch( $_GET['action'] )
        {
        case 'addmodif':
            formflux( $flux );
            $loadtable = FALSE;
            break;


        case 'deactiver':
            $f = $flux->get_flux( $_GET['flux'] );
            $f->actif = ($f->actif ? "0" : "1");
            $chk = $flux->save( (array) $f );
            if( $chk )
                {
                ?><div id="message" class="updated below-h2"><p>Mise à jour du flux réussie.</p></div><?php
                }

            break;

        case 'delete':
            $chk = $flux->delete( $_GET['flux'] );
            if( $chk )
                {
                ?><div id="message" class="updated below-h2"><p>Suppression du flux réussie.</p></div><?php
                }

            break;

        case 'load':
            $fload = $flux->get_flux( $_GET['flux'] );
            break;

        case 'lastimport':
            $flast = $flux->get_flux( $_GET['flux'] );
            break;

        default:

            break;
        }

    if( $loadtable )
        {
        $FluxTable = new table_flux();
         echo '<div class="wrap"><h2>Liste des flux ' . sprintf( '<a class="page-title-action" href="edit.php?post_type=%s&page=%s&action=%s">Ajouter</a>', $_REQUEST['post_type'], $_REQUEST['page'], 'addmodif' ) . '</h2>';
        $FluxTable->load_items( $flux->get_all_flux() );
        $FluxTable->prepare_items();
        $FluxTable->column_width_style();
        $FluxTable->display();
        echo '</div>';
        echo '<form method="post" action="' . sprintf( 'edit.php?post_type=%s&page=%s', $_REQUEST['post_type'], $_REQUEST['page'] ) . '">';
        echo '<input type="submit" name="formmaj" value="Mise à jour des données XML" class="button-primary" /> 
              <input type="submit" name="formreset" value="Réinitialisation des données XML" class="button-primary" />';
        echo '</form>';
        }

    if( isset( $_POST['formmaj'] ) )
        {
        echo '<div class="postbox" style="padding:30px">';
        $flux->loadallflux();
        echo '</div>';
        }

    if( isset( $_POST['formreset'] ) )
        {
        echo '<div class="postbox" style="padding:30px">';
        $flux->loadallflux($reset=TRUE);
        echo '</div>';
        }

    if( $fload )
        {
        echo '<div class="postbox" style="padding:30px">';
        $flux->loadoneflux( $fload );
        echo '</div>';
        }

    if( $flast )
        {
        $fich = HADES_TMP . '/' . "Tmpfile" . $flast->flux_id . ".xml";
        echo '<div class="postbox" style="padding:30px">';
        echo "<h2> Dernier fichier importé </h2>";
        echo "<p>URL source : " . $flast->url . "<br/>";
        echo "Nom du fichier : " . $fich . "<br/>";
        setlocale( LC_TIME, "FR" );
        echo "Chargé le " . strftime( "%d %B %Y à %H:%I:%S", filemtime( $fich ) ) . "<br/>";
        echo "Taille : " . intval( filesize( $fich ) / 1000 ) . "Ko</p> </div>";
        echo '<div class="postbox" style="padding:30px">';
        echo "<pre>";
        echo htmlentities( file_get_contents( HADES_TMP . '/' . "Tmpfile" . $flast->flux_id . ".xml" ) );
        echo "</pre>";
        echo '</div>';
        }
    }

function formflux( $flux )
    {
    if( $_GET['flux'] )
        {
        $f = $flux->get_flux( $_GET['flux'] );
        $titre = "Édition d'un flux";
        }
    else
        {
        $f = new stdClass();
        $f->actif = 1;
        $f->flux_id = 0;
        $titre = "Ajout d'un flux";
        }
    ?>
    <div class="wrap">
      <h1><?php echo $titre; ?></h1>
      <form method="post" action="<?php echo sprintf( '?post_type=%s&page=%s', $_REQUEST['post_type'], $_REQUEST['page'] ) ?>">
        <table class="form-table">
          <tr>
            <th scope="row"><label for="url">Adresse du flux</label></th>
            <td><input name="url" id="url" aria-describedby="tagline-description" value="<?php echo $f->url; ?>" class="regular-text" type="text" style='width: 90%;'>
              <input type="hidden" name="flux_id" value="<?php echo $f->flux_id; ?>" />
              <p class="description" id="tagline-description">L'adresse du flux doit commencer par "http://w3.ftlb.be/webservice/h2o.php?"
                Les paramètres à passer dans l'url sont définis dans le wiki : <a href="http://w3.ftlb.be/wiki/index.php/Flux">http://w3.ftlb.be/wiki/index.php/flux</a>.</p></td>
          </tr>
          <tr>
            <th scope="row">Status</th>
            <td> <fieldset><legend class="screen-reader-text"><span>Actif</span></legend><label for="actif">
                  <input name="actif" id="actif" value="1" type="checkbox" <?php echo $f->actif ? "checked='checked'" : ""; ?>>
                  Le flux est actif.</label>
              </fieldset>
              <p class="description" id="tagline-description">Si le flux n'est pas actif, il sera ignoré lors de la prochaine synchronisation.</p></td>
            </td>
          </tr>
          <tr>
            <th scope="row">&nbsp;</th>
            <td ><input type="submit" name="formaction" value="Enregistrer" class="button-primary" />
              <input type="submit" name="formaction" value="Annuler" class="button-primary" /></td>
          </tr>
        </table>
      </form>
    </div>
    <?php
    }
