<?php
function agd_menu_callback()
{
    add_menu_page('Importazione dati gruppo', 'Importazione dati gruppo', 'edit_posts', 'agd-menu-slug', 'agd_render_settings', 'dashicons-database-add');
}
function agd_render_settings()
{
    $mapbox_key_is_present = false;
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
    function agd_submit() {
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
        </section>
        <?php if($mapbox_key_is_present) {?>
        <p class="submit"><button onclick="agd_submit()" class="button button-primary">Invia newsletter</button></p>
        <?php }?>
    </div>
    <?php
}
