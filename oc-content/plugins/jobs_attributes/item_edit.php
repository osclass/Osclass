<h3>Job attributes</h3>
<table>
    <tr>
        <td><label for="relation"><?php _e('Relation'); ?></label></td>
        <td>
            <label for="hire"><input type="radio" name="relation" value="HIRE" id="hire" <?php if( $detail['e_relation'] == 'HIRE' ) { echo 'checked'; }; ?>/><?php _e('Hire someone'); ?></label><br />
            <label for="look"><input type="radio" name="relation" value="LOOK" id="look" <?php if( $detail['e_relation'] == 'LOOK' ) { echo 'checked'; }; ?>/><?php _e('Looking for a job'); ?></label><br />
        </td>
    </tr>
    <tr>
        <td><label for="companyName"><?php _e('Company name'); ?></label></td>
        <td><input type="text" name="companyName" value="<?php echo $detail['s_company_name']; ?>" /></td>
    </tr>
    <tr>
        <td><label for="positionType"><?php _e('Position type'); ?></label></td>
        <td>
            <select name="positionType" id="positionType">
                <option value="UNDEF" <?php if( $detail['e_position_type'] == 'UNDEF' ) { echo 'selected'; }; ?>><?php _e('Undefined'); ?></option>
                <option value="PART" <?php if( $detail['e_position_type'] == 'PART' ) { echo 'selected'; }; ?>><?php _e('Part time'); ?></option>
                <option value="FULL" <?php if( $detail['e_position_type'] == 'FULL' ) { echo 'selected'; }; ?>><?php _d('Full-time'); ?></option>
            </select>
        </td>
    </tr>
    <tr>
        <td><label for="salaryRange"><?php echo __('Salary range'); ?></label></td>
        <td>
            <input type="text" name="salaryMin" value="<?php echo  $detail['i_salary_min']; ?>" size="7" maxlength="6" /> - <input type="text" name="salaryMax" value="<?php echo  $detail['i_salary_max']; ?>" size="7" maxlength="6" />
            <select name="salaryPeriod" id="salaryPeriod">
                <option value="HOUR" <?php if($detail['e_salary_period']=='HOUR') { echo 'selected'; }; ?> ><?php echo __('Hour'); ?></option>
                <option value="WEEK" <?php if($detail['e_salary_period']=='WEEK') { echo 'selected'; }; ?> ><?php echo __('Week'); ?></option>
                <option value="MONTH" <?php if($detail['e_salary_period']=='MONTH') { echo 'selected'; }; ?> ><?php echo __('Month'); ?></option>
                <option value="YEAR" <?php if($detail['e_salary_period']=='YEAR') { echo 'selected'; }; ?> ><?php echo __('Year'); ?></option>
            </select>
        </td>
    </tr>
    <?php
    $locales = Locale::newInstance()->listAllEnabled();
    if(count($locales)==1) {
        $locale = $locales[0];
    ?>
        <p>
            <label for="desired_exp"><?php _e('Desired experience'); ?></label><br />
            <input type="text" name="<?php echo $locale['pk_c_code']; ?>#desired_exp" id="desired_exp" style="width: 100%;" value="<?php echo @$detail['locale'][$locale['pk_c_code']]['s_desired_exp']; ?>" />
        </p>
        <p>
            <label for="studies"><?php _e('Studies'); ?></label><br />
            <input type="text" name="<?php echo $locale['pk_c_code']; ?>#studies" id="studies" style="width: 100%;" value="<?php echo  @$detail['locale'][$locale['pk_c_code']]['s_studies']; ?>" />
        </p>
        <p>
            <label for="min_reqs"><?php _e('Minimum requirements'); ?></label><br />
            <textarea name="<?php echo $locale['pk_c_code']; ?>#min_reqs" id="min_reqs" style="width: 100%;" ><?php echo @$detail['locale'][$locale['pk_c_code']]['s_minimum_requirements']; ?></textarea>
        </p>
        <p>
            <label for="desired_reqs"><?php _e('Desired requirements'); ?></label><br />
            <textarea name="<?php echo $locale['pk_c_code']; ?>#desired_reqs" id="desired_reqs" style="width: 100%;"><?php echo @$detail['locale'][$locale['pk_c_code']]['s_desired_requirements']; ?></textarea>
        </p>
        <p>
            <label for="contract"><?php _e('Contract'); ?></label><br />
            <input type="text" name="<?php echo $locale['pk_c_code']; ?>#contract" id="contract" style="width: 100%;" value="<?php echo @$detail['locale'][$locale['pk_c_code']]['s_contract']; ?>" />
        </p>
        <p>
            <label for="company_desc"><?php _e('Company description'); ?></label><br />
            <textarea name="<?php echo $locale['pk_c_code']; ?>#company_desc" id="company_desc" style="width: 100%;"><?php echo @$detail['locale'][$locale['pk_c_code']]['s_company_description']; ?></textarea>
        </p>
    <?php } else { ?>
        <div class="tabber">
        <?php foreach($locales as $locale) {?>
            <div class="tabbertab">
                <h2><?php echo $locale['s_name']; ?></h2>
                <p>
                    <label for="desired_exp"><?php _e('Desired experience'); ?></label><br />
                    <input type="text" name="<?php echo $locale['pk_c_code']; ?>#desired_exp" id="desired_exp" style="width: 100%;" value="<?php echo @$detail['locale'][$locale['pk_c_code']]['s_desired_exp']; ?>" />
                </p>
                <p>
                    <label for="studies"><?php _e('Studies'); ?></label><br />
                    <input type="text" name="<?php echo $locale['pk_c_code']; ?>#studies" id="studies" style="width: 100%;" value="<?php echo @$detail['locale'][$locale['pk_c_code']]['s_studies']; ?>" />
                </p>
                <p>
                    <label for="min_reqs"><?php _e('Minimum requirements'); ?></label><br />
                    <textarea name="<?php echo $locale['pk_c_code']; ?>#min_reqs" id="min_reqs" style="width: 100%;" ><?php echo @$detail['locale'][$locale['pk_c_code']]['s_minimum_requirements']; ?></textarea>
                </p>
                <p>
                    <label for="desired_reqs"><?php _e('Desired requirements'); ?></label><br />
                    <textarea name="<?php echo $locale['pk_c_code']; ?>#desired_reqs" id="desired_reqs" style="width: 100%;"><?php echo @$detail['locale'][$locale['pk_c_code']]['s_desired_requirements']; ?></textarea>
                </p>
                <p>
                    <label for="contract"><?php _e('Contract'); ?></label><br />
                    <input type="text" name="<?php echo $locale['pk_c_code']; ?>#contract" id="contract" style="width: 100%;" value="<?php echo @$detail['locale'][$locale['pk_c_code']]['s_contract']; ?>" />
                </p>
                <p>
                    <label for="company_desc"><?php _e('Company description'); ?></label><br />
                    <textarea name="<?php echo $locale['pk_c_code']; ?>#company_desc" id="company_desc" style="width: 100%;"><?php echo @$detail['locale'][$locale['pk_c_code']]['s_company_description']; ?></textarea>
                </p>
            </div>
        <?php }; ?>
        </div>
<?php }; ?>
</table>

