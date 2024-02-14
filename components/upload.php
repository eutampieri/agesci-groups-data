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
    function agd_send_data() {
        var data = {
            'action': 'agd_group_data',
            'agd_additional_action': "send_data",
            'agd_file': document.getElementById("agd_b64").value,
        };

        for(const m of document.getElementById("mapping").getElementsByTagName("select")) {
            data[m.name] = m.value;
        }
        
        jQuery.post(ajaxurl, data, function(response) {
            alert(response);
        });
    }
    function agd_load_headings() {
        var data = {
            'action': 'agd_group_data',
            'agd_additional_action': "get_headings",
            'agd_file': document.getElementById("agd_b64").value,
        };
        
        jQuery.post(ajaxurl, data, function(response) {
            let headings = [];
            for(const dd of response) {
                let d = dd[0];
                let human_readable = d.replace(/_/g, ' ');
                human_readable = human_readable.charAt(0).toUpperCase() + human_readable.slice(1);
                let element = document.createElement("option");
                element.value = d;
                element.innerText = `${human_readable} (${dd[1]})`;
                headings.push(element);
            }
            
            for(const s of document.getElementById("mapping").getElementsByTagName("select")) {
                for(const h of headings) {
                    s.appendChild(h.cloneNode(true));
                }
            }
        });
    }

    function agd_file_to_b64(i) {
        let file = i.files[0];
        let reader = new FileReader();
        reader.onloadend = () => {
            document.getElementById("agd_b64").value = reader.result
                .replace('data:', '')
                .replace(/^.+,/, '');
            agd_load_headings();
        }
        reader.readAsDataURL(file);
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
        <section>
            <form>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">File da caricare</th>
                        <td><input type="file" onchange="agd_file_to_b64(this)"></select></td>
                    </tr>
                </table>
                <input id="agd_b64" type="hidden">
            </form>
        </section>
        <section id="agd_mapping">
            <h2>Corrispondenza campi</h2>
            <form id="mapping">
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Ordinale gruppo</th>
                        <td><select name="id"></select></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Nome gruppo</th>
                        <td><select name="name"></select></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Via gruppo</th>
                        <td><select name="street"></select></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Numero civico gruppo</th>
                        <td><select name="street_number"></select></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Città gruppo</th>
                        <td><select name="city"></select></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">CAP gruppo</th>
                        <td><select name="zip"></select></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Provincia gruppo</th>
                        <td><select name="province"></select></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Email gruppo</th>
                        <td><select name="email"></select></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">CF gruppo</th>
                        <td><select name="vat_no"></select></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Rappresentante legale</th>
                        <td><select name="legal_representative"></select></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Zona</th>
                        <td><select name="zone"></select></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Parrocchia</th>
                        <td><select name="parish"></select></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Diocesi</th>
                        <td><select name="diocesee"></select></td>
                    </tr>
                </table>
            </form>
        </section>
        <?php if($mapbox_key_is_present) {?>
        <p class="submit"><button id="agd_submit" onclick="agd_send_data()" class="button button-primary">Carica</button></p>
        <?php }?>
    <?php
}
