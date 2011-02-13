<form action="<?php echo osc_base_url(true) ; ?>" method="post" class="search">
    <fieldset class="main">
        <input type="text" name="query"  id="query" value="Ej: Programador PHP" />
        <select name="categories" id="categories">
            <option value="0">All Categories</option>
            <option value="1">Una categoria</option>
            <option value="2">Otra categoria</option>
        </select>
        <button type="submit"><?php _e('Send') ; ?></button>

        <a id="expand_advanced" href="#"><?php _e('Advanced search') ; ?></a>
    </fieldset>
    <fieldset class="extras">
        <p class="fieldset_title"><strong><?php _e('Narrow your search') ; ?>:</strong></p>
        <select name="country" id="country">
            <option value="0">Country</option>
            <option value="1">Spain</option>
            <option value="2">France</option>
        </select>

        <select name="state" id="state">
            <option value="0">State</option>
            <option value="1">Choose a country first</option>
        </select>
    </fieldset>
</form>