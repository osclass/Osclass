<form action="<?php echo osc_base_url(true) ; ?>" method="post" class="search">
    <input type="hidden" name="page" value="search" />
    <fieldset class="main">
        <input type="text" name="sPattern"  id="sPattern" value="<?php echo isset($sPattern)?$sPattern:'Ej: Programador PHP';?>" />
        <?php osc_categories_select("sCategory", (isset($sCategory) && isset($sCategory[0]))?$sCategory[0]:-1);?>
        <button type="submit"><?php _e('Send') ; ?></button>

    </fieldset>
</form>
