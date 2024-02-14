<?php
function agd_menu_callback()
{
    add_menu_page('Importazione dati gruppo', 'Importazione dati gruppo', 'edit_posts', 'agd-menu-slug', 'agd_render_settings', 'dashicons-database-add');
}
function agd_render_settings()
{
    $mapbox_key_is_present = get_option('agd_mapbox_key') != "";
    ?>
    <div>
    <script type="text/javascript">
    function agd_get_headings() {
        var data = {
			'action': 'agd_group_data',
			'agd_additional_action': "get_headings",
		};
        
        jQuery.post(ajaxurl, data, function(response) {
            if(response.length == 26) {
			    alert('Un invio è stato creato con ID ' + response + ". Riceverai una mail quando tutte le mail saranno spedite.");
            } else {
                alert("Si è verificato un errore: " + response);
            }
		});
    }
    function agd_load_headings() {
        var data = {
			'action': 'agd_group_data',
			'agd_additional_action': "get_headings",
		};
        
        jQuery.post(ajaxurl, data, function(response) {
            if(response.length == 26) {
			    alert('Un invio è stato creato con ID ' + response + ". Riceverai una mail quando tutte le mail saranno spedite.");
            } else {
                alert("Si è verificato un errore: " + response);
            }
		});
    }
    </script>
        <h1>Importazione dati gruppo</h1>
        <section>
            <h2>Chiave API Mapbox</h2>
            <?php if(!$mapbox_key_is_present){ ?>
            <div class="notice notice-error">
                <p>Non è stato fornito il token di Mapbox. Inserirlo nel campo sottostante.</p>
            </div>
            <?php } else { ?>
            <div class="notice notice-info">
                <p>Mapbox è correttamente configurato. Se lo si vuole, è possibile cambiare il token qui sotto.</p>
            </div>
            <?php } ?>
                <form method="post" action="options.php">
                <?php settings_fields('agd_mapbox_key_group'); ?>
                <?php do_settings_sections('agd_mapbox_key_group'); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Chiave API MapBox:</th>
                        <td><input type="password" name="agd_mapbox_key" value="<?php echo esc_attr(get_option('agd_mapbox_key')); ?>" /></td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </section>
        <section id="agd_mapping">
        </section>
        <?php if($mapbox_key_is_present) {?>
        <p class="submit"><button id="agd_submit" onclick="agd_load_headings()" class="button button-primary">Carica</button></p>
        <?php }?>
    </div>
    <?php
}
