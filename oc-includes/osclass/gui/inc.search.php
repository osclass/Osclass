<form action="<?php echo osc_base_url(true) ; ?>" method="post" class="search">
    <input type="hidden" name="page" value="search" />
    <fieldset class="main">
        <input type="text" name="query"  id="query" value="Ej: Programador PHP" />
        <?php osc_categories_select("sCategory");?>
        <button type="submit"><?php _e('Send') ; ?></button>

    </fieldset>
</form>
