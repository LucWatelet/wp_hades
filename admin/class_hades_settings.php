<?php
//namespace Atlb\Hades;
class Hades_Settings
{
    static $instance;

    public function __construct()
    {
        add_action($tag = 'admin_init', $function_to_add = array ($this, 'register_settings'));
    }

    public function register_settings()
    {
        /*         * *****************************************************************************
         *  Import
         * **************************************************************************** */
        add_settings_section(
            $id = 'hades-settings-import',
            $title = 'Importation',
            $callback = array($this, 'import_section'),
            $page = 'hades-settings'
        );
        add_settings_field(
            $id = 'hades_settings_langue', 
            $title = 'Langue d\'importation des offres',
            $callback = array ( $this, 'hades_settings_langue' ),
            $page = 'hades-settings',
            $section = 'hades-settings-import', $args = array('label_for' => $id)
        );
        add_settings_field(
                $id = 'hades_category_parent', $title = 'Catégorie WordPress', $callback = array ( $this, 'category_parent' ), $page = 'hades-settings', $section = 'hades-settings-import', $args = array ( 'label_for' => $id ) );

        add_settings_field(
                $id = 'hades_event_category_parent', $title = 'Catégorie des événements', $callback = array ( $this, 'event_category_parent' ), $page = 'hades-settings', $section = 'hades-settings-import', $args = array ( 'label_for' => $id ) );
        /* not used ?
        add_settings_field(
                $id = 'hades_event_long', $title = 'Longs événements', $callback = array ( $this, 'event_long' ), $page = 'hades-settings', $section = 'hades-settings-import', $args = array ( 'label_for' => $id ) );
        */


        register_setting(
                $option_group = 'hades_settings', // A settings group name. Must exist prior to the register_setting call. This must match the group name in settings_fields()
                $option_name = 'hades_settings' ); // on sauve tout dans le même panier
        }


        
    /*     * *****************************************************************************
     *  Import
     * **************************************************************************** */


    public function import_section()
        {
        ?>
        <p>Options d'importation des offres</p>
        <?php
        }

    public function flux_from_datetime()
        {
        $settings = get_option( $option = 'hades_settings' );
        if( $settings['flux_from_datetime'] == '' )
            {
            $settings['flux_from_datetime'] = "2016-01-01";
        }
        ?>
        <fieldset>
          <input size="64" id="hades_settings_flux_from_datetime" name="hades_settings[flux_from_datetime]" type="text" value="<?php echo $settings['flux_from_datetime']; ?>"  />
          <label for="hades_settings_flux_from_datetime">&nbsp;Dernier chargement des flux</label><br />
        </fieldset>
        <?php
        }
        
    public function time_extend()
        {
        $settings = get_option( $option = 'hades_settings' );
        ?>
        <fieldset>
          <input id="hades-settings-time-extend" name="hades_settings[time-extend]" type="checkbox" value="checked" <?php echo $settings['time-extend']; ?> />
          <label for="hades-settings-time-extend">&nbsp;Activer Set_Time_Limit</label><br />
          <p class="description">Idispensable pour les gros flux.</p>
        </fieldset>
        <?php
        }
        
    public function category_parent()
        {
        $settings = get_option( $option = 'hades_settings' );
        ?>
        <fieldset>
        <?php
        $args = array (
                    'hide_empty' => 0,
                    'selected' => $settings['hades_category_parent'],
                    'hierarchical' => 1,
                    'name' => "hades_settings[hades_category_parent]" );
        wp_dropdown_categories( $args );
        ?>
          <label for="hades_category_parent">&nbsp;Sélectionnez la catégorie WordPress où importer les offres</label><br />
        </fieldset>
        <?php
        }

    public function event_category_parent()
        {
        $settings = get_option( $option = 'hades_settings' );
        ?>

        <fieldset>
        <?php
        $args = array (
                    'hide_empty' => 0,
                    'selected' => $settings['hades_event_category_parent'],
                    'hierarchical' => 1,
                    'name' => "hades_settings[hades_event_category_parent]" );
        wp_dropdown_categories( $args );
        ?>
          <label for="hades_category_parent">&nbsp;Sélectionnez la catégorie WordPress où importer les événements</label><br />
        </fieldset>
        <?php
        }

    public function event_long()
        {
        $settings = get_option( $option = 'hades_settings' );
        ?>
        <fieldset>
          <input  id="hades_event_long" name="hades_settings[event_long]" type="text" value="<?php echo $settings['event_long']; ?>"  />
          <label for="hades_event_long">&nbsp;Evénements long à partir de (Nb Jours)</label><br />
        </fieldset>
          <?php
          }

      public function maj_en_cours()
          {
          $settings = get_option( $option = 'hades_settings' );
          ?>
        <fieldset>
          <input  id="hades_maj_en_cours" name="hades_settings[maj_en_cours]" type="text" value="<?php echo $settings['maj_en_cours']; ?>"  />
          <label for="hades_maj_en_cours">Ce champ est modifié par le système. Si une valeur apparait, une mise à jour est en cours. Si cette valeur stagne il se peut que le processus de mise à jour ait échoué.</label><br />

        </fieldset>
        <?php
        }

        
     public function hades_settings_langue()
        {
        $settings = get_option( $option = 'hades_settings' );
        if( !in_array($settings['langue'], array("fr","nl","en","de") ) )
            {
            $settings['langue'] = "fr";
			
			}
        ?>
        <fieldset>
          <select id="hades_settings_langue" name="hades_settings[langue]">
          <?php
          foreach ( array( 'de' => 'Allemand', 'en' => 'Anglais', 'fr' => 'Français', 'nl' => 'Néerlandais') as $language => $label ) {
            $selected = $language === $settings['langue'] ? 'selected' : '';
            ?><option value="<?php echo $language; ?>" <?php echo $selected; ?>><?php echo $label; ?></option><?php
          }
          ?>
          </select>
          <label for="hades_settings_langue">Par défaut les offres sont importées en français</label>
        </fieldset>
        <?php
        }            
        

    public static function get_instance()
        {
        if( !isset( self::$instance ) )
            {
            self::$instance = new self();
            }
        return self::$instance;
        }

    }
