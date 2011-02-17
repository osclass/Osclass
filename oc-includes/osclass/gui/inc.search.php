<form action="<?php echo osc_base_url(true) ; ?>" method="post" class="search">
    <input type="hidden" name="page" value="search" />
    <fieldset class="main">
        <input type="text" name="query"  id="query" value="Ej: Programador PHP" />
        <?php osc_categories_select("sCategory");?>
        <button type="submit"><?php _e('Send') ; ?></button>

        <a id="expand_advanced" href="#"><?php _e('Advanced search') ; ?></a>
    </fieldset>
    <fieldset class="extras">
        <p class="fieldset_title"><strong><?php _e('Narrow your search') ; ?>:</strong></p>
        <select name="country" id="country">
            <option value="0"><?php _e("Country");?></option>
        </select>

        <select name="state" id="state">
            <option value="0"><?php _e("Region");?></option>
            <option value="1"><?php _e("Choose a country first");?></option>
        </select>
    </fieldset>
</form>
