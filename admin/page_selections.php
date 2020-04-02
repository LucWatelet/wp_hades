<?php

function page_selections()
    {

    global $chk;

    $sels = new Hades_Selections();

    if( isset( $_POST['hades_sels_submit'] ) )
        {
        $chk = $sels->save( $_POST );
        }
    ?>

    <div class="wrap">
      <h1>Convertion des sélections Hadès en étiquettes WordPress</h1>
      
      Attention, pour des raisons de cohérence les associations d'étiquettes ne peuvent être modifiée. 
      
      <?php if( isset( $_POST['hades_sels_submit'] ) && $chk ): ?>
          <div id="message" class="updated below-h2">
            <p>Mise à jour effectuée</p>
          </div>
      <?php endif; ?>
      <div class="metabox-holder">
        <form method="post" action="">
        <?php
        $Table = new hades_table_selections();
        $Table->load_items( $sels->get_all_sel() );
        $Table->prepare_items();
        $Table->column_width_style();
        $Table->display();
          ?>
          <input type="submit" name="hades_sels_submit" value="Enregistrer" class="button-primary" />
        </form>
      </div>
    </div>
    <?php
    }
