<?php

function page_assoc_cat()
    {

    global $chk;

    $cats = new Hades_Categories();

    if( isset( $_POST['hades_cats_submit'] ) )
        {
        $chk = $cats->save_taxocat( $_POST['taxocat'] );
        }
    ?>

    <div class="wrap">
      <h1>Association des catégories Hadès -> WordPress</h1>
    <?php if( isset( $_POST['hades_cats_submit'] ) && $chk ): ?>
          <div id="message" class="updated below-h2">
            <p>Mise à jour effectuée</p>
          </div>
        <?php endif; ?>
      <div class="metabox-holder">
        <?php
        echo "<select id='select_cat_model' name='model-select' style='display:none' >";
        echo "<option value=''>Aucune</option>";
        foreach( $cats->get_all_cat() as $cathd )
            {
            echo "<option  value='" . $cathd->fk_cat_id . "'>" . $cathd->fk_cat_id . " (" . $cathd->nb . ")</option>";
            }
        echo "</select>";
        ?>
        <form method="post" action="">

          <table class="table-cathdwp widefat fixed striped ">
            <thead>
              <tr>
                <th scope="col" id="cat_wp" class="manage-column column-primary"><span>Catégories WordPress</span></th>
                <th scope="col" id="cat_hd" class="manage-column column-primary"><span>Catégories Hadès associées</span></th>
              </tr>
            </thead>
            <tbody id="the-list">
              <?php
              $taxocats = $cats->get_all_cat_tax();
              $settings = get_option( $option = 'hades_settings' );
              $racine = $settings['hades_category_parent'];
              $cpt = 0;
              if( $racine )
                  {
                  $categories = get_categories( array (
                              'orderby' => 'name',
                              'child_of' => $racine,
                              'hide_empty' => 0,
                              'hierarchical' => true
                          ) );
                  $decalage = array ();
                  foreach( $categories as $cat )
                      {
                      if( $cat->parent > 0 )
                          {
                          $decalage[$cat->term_id] = $decalage[$cat->parent] + 1;
                          }
                      echo "<tr>";
                      echo "<td class='wphades_cat_niv" . $decalage[$cat->term_id] . "'>" . $cat->name . "</td>";
                      echo "<td class='cat_hades' style='color:#AAA;font-weight:normal;font-size:10px;'>";
                      echo '<span class="dashicons dashicons-plus cat-plus" term_id="' . $cat->term_id . '"  ></span>&nbsp;&nbsp;&nbsp; ';
                      if( is_array( $taxocats[$cat->term_id] ) )
                          {
                          foreach( $taxocats[$cat->term_id] as $taxocat )
                              {
                              echo '<div class="cat_button">' . $taxocat->fk_cat_id . '<input type="hidden" '
                              . 'name="taxocat[' . $taxocat->term_id . '][' . $cpt . '][fk_cat_id]" '
                              . 'value="' . $taxocat->fk_cat_id . '" />';
                              echo '<input class="hades_xpath_input" type="' . ( $taxocat->xpath != '' ? 'text' : 'hidden' ) . '" '
                              . 'name="taxocat[' . $taxocat->term_id . '][' . $cpt . '][xpath]" '
                              . 'value="' . $taxocat->xpath . '" />';
                              if( $taxocat->xpath == '' )
                                  {
                                  echo '<span class="dashicons dashicons-chart-pie cat-xpath" '
                                  . 'term_id="' . $taxocat->term_id . '" '
                                  . 'fk_cat_id="' . $taxocat->fk_cat_id . '" ></span>';
                                  }

                              echo '<span class="dashicons dashicons-no cat-moins" term_id="' . $taxocat->term_id . '" fk_cat_id="' . $taxocat->fk_cat_id . '" ></span>';
                              echo'</div>';
                              $cpt++;
                              }
                          }

                      echo"</td>";
                      echo "</tr>";
                      }
                  }
              else
                  {
                  echo "<tr><td></td>
							<td><h2>&nbsp;</h2>
							<div class='error notice-error below-h2'>
								<p>L'association des catégories est impossible tant que la catégorie 'Racine' des offres Hadès n'est pas définie. ( Voir les options générales de ce plugin.)</p>
							</div>

							</td></tr>";
                  }
              echo "<tr><td><h2>Catégories non-associées :</h2></td><td><ul>";
              foreach( $cats->get_free_cat() as $freecathd )
                  {
                  echo "<li>" . $freecathd->fk_cat_id . " (" . $freecathd->nb . ")</li>";
                  }
              echo "<ul></td></tr>";
              ?>
              <tr>
                <th scope="row">&nbsp;</th>
                <td style="padding-top:10px; padding-bottom:10px;"><input type="submit" name="hades_cats_submit" value="Save changes" class="button-primary" /></td>
              </tr>
            </tbody>
          </table>
        </form>
      </div>
    </div>
    <?php
    }
