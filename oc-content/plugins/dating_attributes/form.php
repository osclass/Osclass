<h3><?php _e('Dating attributes' ) ; ?></h3>
<table>
    <tr>
        <td><?php _e('You are:'); ?></td>
        <td>
            <label for="gfm"><input type="radio" name="genderFrom" id="gfm" value="MAN" /><?php _e('Man'); ?></label><br />
            <label for="gfw"><input type="radio" name="genderFrom" id="gfw" value="WOMAN" /><?php _e('Woman'); ?></label><br />
            <label for="gfn"><input type="radio" name="genderFrom" id="gfn" value="NI" /><?php _e('Not informed'); ?></label><br />
        </td>
    </tr>
    <tr>
        <td><?php _e('Looking for:'); ?></td>
        <td>
            <label for="gtm"><input type="radio" name="genderTo" id="gtm" value="MAN" /><?php _e('Man'); ?></label><br />
            <label for="gtw"><input type="radio" name="genderTo" id="gtw" value="WOMAN" /><?php _e('Woman'); ?></label><br />
            <label for="gtn"><input type="radio" name="genderTo" id="gtn" value="NI" /><?php _e('Not informed'); ?></label><br />
        </td>
    </tr>
    <tr>
        <td><?php _e('Relation type:'); ?></td>
        <td>
            <label for="grm"><input type="radio" name="relation" id="grm" value="FRIENDSHIP" /><?php _e('Friendship'); ?></label><br />
            <label for="grw"><input type="radio" name="relation" id="grw" value="FORMAL" /><?php _e('Formal relation'); ?></label><br />
            <label for="grn"><input type="radio" name="relation" id="grn" value="INFORMAL" /><?php _e('Informal relation'); ?></label><br />
        </td>
    </tr>
</table>