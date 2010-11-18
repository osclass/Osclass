<h3>Job attributes</h3>
<table>
    <tr>
        <td><label for="relation"><?php _e('Relation'); ?></label></td>
        <td>
            <label for="hire"><input type="radio" name="relation" value="HIRE" id="hire" /><?php _e('Hire someone'); ?></label><br />
            <label for="look"><input type="radio" name="relation" value="LOOK" id="look" /><?php _e('Looking for a job'); ?></label><br />
	</td>
    </tr>
    <tr>
        <td><label for="companyName"><?php _e('Company name'); ?></label></td>
        <td><input type="text" name="companyName" value="" /></td>
    </tr>
    <tr>
        <td><label for="positionType"><?php _e('Position type'); ?></label></td>
        <td>
            <select name="positionType" id="positionType">
                <option value="UNDEF"><?php _e('Undefined'); ?></option>
                <option value="PART"><?php _e('Part time'); ?></option>
                <option value="FULL"><?php _e('Full-time'); ?></option>
            </select>
        </td>
    </tr>
    <tr>
        <td><label for="salaryRange"><?php _e('Salary range'); ?></label></td>
        <td>
            <input type="text" name="salaryMin" value="0" size="7" maxlength="6" /> - <input type="text" name="salaryMax" value="0" size="7" maxlength="6" />
            <select name="salaryPeriod" id="salaryPeriod">
                <option value="HOUR"><?php _e('Hour'); ?></option>
                <option value="WEEK"><?php _e('Week'); ?></option>
                <option value="MONTH"><?php _e('Month'); ?></option>
                <option value="YEAR"><?php _e('Year'); ?></option>
            </select>
        </td>
    </tr>
</table>
<?php
    $locales = Locale::newInstance()->listAllEnabled();
    if(count($locales)==1) {
        $locale=$locales[0];
?>
        <p>
            <label for="desired_exp"><?php _e('Desired experience'); ?></label><br />
            <input type="text" name="<?php echo $locale['pk_c_code']; ?>#desired_exp" id="desired_exp" style="width: 100%;" />
        </p>
        <p>
            <label for="studies"><?php _e('Studies'); ?></label><br />
            <input type="text" name="<?php echo $locale['pk_c_code']; ?>#studies" id="studies" style="width: 100%;" />
        </p>
        <p>
            <label for="min_reqs"><?php _e('Minimum requirements'); ?></label><br />
            <textarea name="<?php echo $locale['pk_c_code']; ?>#min_reqs" id="min_reqs" style="width: 100%;"></textarea>
        </p>
        <p>
            <label for="desired_reqs"><?php _e('Desired requirements'); ?></label><br />
            <textarea name="<?php echo $locale['pk_c_code']; ?>#desired_reqs" id="desired_reqs" style="width: 100%;"></textarea>
        </p>
        <p>
            <label for="contract"><?php _e('Contract'); ?></label><br />
            <input type="text" name="<?php echo $locale['pk_c_code']; ?>#contract" id="contract" style="width: 100%;" />
        </p>
        <p>
            <label for="company_desc"><?php _e('Company description'); ?></label><br />
            <textarea name="<?php echo $locale['pk_c_code']; ?>#company_desc" id="company_desc" style="width: 100%;"></textarea>
        </p>
<?php } else { ?>
        <div class="tabber">
        <?php foreach($locales as $locale) {?>
            <div class="tabbertab">
                <h2><?php echo $locale['s_name']; ?></h2>
                <p>
                    <label for="desired_exp"><?php _e('Desired experience'); ?></label><br />
                    <input type="text" name="<?php echo $locale['pk_c_code']; ?>#desired_exp" id="desired_exp" style="width: 100%;" />
                </p>
                <p>
                    <label for="studies"><?php _e('Studies'); ?></label><br />
                    <input type="text" name="<?php echo $locale['pk_c_code']; ?>#studies" id="studies" style="width: 100%;" />
                </p>
                <p>
                    <label for="min_reqs"><?php _e('Minimum requirements'); ?></label><br />
                    <textarea name="<?php echo $locale['pk_c_code']; ?>#min_reqs" id="min_reqs" style="width: 100%;"></textarea>
                </p>
                <p>
                    <label for="desired_reqs"><?php _e('Desired requirements'); ?></label><br />
                    <textarea name="<?php echo $locale['pk_c_code']; ?>#desired_reqs" id="desired_reqs" style="width: 100%;"></textarea>
                </p>
                <p>
                    <label for="contract"><?php _e('Contract'); ?></label><br />
                    <input type="text" name="<?php echo $locale['pk_c_code']; ?>#contract" id="contract" style="width: 100%;" />
                </p>
                <p>
                    <label for="company_desc"><?php _e('Company description'); ?></label><br />
                    <textarea name="<?php echo $locale['pk_c_code']; ?>#company_desc" id="company_desc" style="width: 100%;"></textarea>
                </p>
            </div>
        <?php }; ?>
        </div>
<?php }; ?>

<script type="text/javascript">
    tabberAutomatic();
</script>