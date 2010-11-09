<?php
    $relations = array('HIRE' => 'Hire someone', 'LOOK' => 'Looking for a job');
    $index = trim($detail['e_relation']);

    $locales = Locale::newInstance()->listAllEnabled();
?>

<h3><?php _e('Job attributes'); ?></h3>
<table>
    <tr>
        <td><label for="relation"><?php _e('Relation'); ?></label></td>
        <td><?php _e($relations[$index]); ?></td>
    </tr>
    <tr>
        <td><label for="companyName"><?php _e('Company name'); ?></label></td>
        <td><?php echo $detail['s_company_name']; ?></td>
    </tr>
    <tr>
        <td><label for="positionType"><?php _e('Position type'); ?></label></td>
        <td><?php echo $detail['e_position_type']; ?></td>
    </tr>
    <tr>
        <td><label for="salaryRange"><?php _e('Salary range'); ?></label></td>
        <td><?php echo $detail['i_salary_min']; ?> - <?php echo $detail['i_salary_max']; ?> <?php echo $detail['e_salary_period']; ?></td>
    </tr>
    <?php
    if(count($locales)==1) {
        $locale = $locales[0];?>
        <p>
            <label for="desired_exp"><?php _e('Desired experience'); ?></label><br />
            <?php echo @$detail['locale'][$locale['pk_c_code']]['s_desired_exp']; ?>
        </p>
        <p>
            <label for="studies"><?php _e('Studies'); ?></label><br />
            <?php echo @$detail['locale'][$locale['pk_c_code']]['s_studies']; ?>
        </p>
        <p>
            <label for="min_reqs"><?php _e('Minimum requirements'); ?></label><br />
            <?php echo @$detail['locale'][$locale['pk_c_code']]['s_minimum_requirements']; ?>
        </p>
        <p>
            <label for="desired_reqs"><?php _e('Desired requirements'); ?></label><br />
            <?php echo @$detail['locale'][$locale['pk_c_code']]['s_desired_requirements']; ?>
        </p>
        <p>
            <label for="contract"><?php _e('Contract'); ?></label><br />
            <?php echo @$detail['locale'][$locale['pk_c_code']]['s_contract']; ?>
        </p>
        <p>
            <label for="company_desc"><?php _e('Company description'); ?></label><br />
            <?php echo @$detail['locale'][$locale['pk_c_code']]['s_company_description']; ?>
        </p>
    <?php }else { ?>
        <div class="tabber">
        <?php foreach($locales as $locale) {?>
            <div class="tabbertab">
                <h2><?php echo $locale['s_name']; ?></h2>
                <p>
                    <label for="desired_exp"><?php _e('Desired experience'); ?></label><br />
                    <?php echo @$detail['locale'][$locale['pk_c_code']]['s_desired_exp']; ?>
                </p>
                <p>
                    <label for="studies"><?php _e('Studies'); ?></label><br />
                    <?php echo @$detail['locale'][$locale['pk_c_code']]['s_studies']; ?>
                </p>
                <p>
                    <label for="min_reqs"><?php _e('Minimum requirements'); ?></label><br />
                    <?php echo @$detail['locale'][$locale['pk_c_code']]['s_minimum_requirements']; ?>
                </p>
                <p>
                    <label for="desired_reqs"><?php _e('Desired requirements'); ?></label><br />
                    <?php echo @$detail['locale'][$locale['pk_c_code']]['s_desired_requirements']; ?>
                </p>
                <p>
                    <label for="contract"><?php _e('Contract'); ?></label><br />
                    <?php echo @$detail['locale'][$locale['pk_c_code']]['s_contract']; ?>
                </p>
                <p>
                    <label for="company_desc"><?php _e('Company description'); ?></label><br />
                    <?php echo @$detail['locale'][$locale['pk_c_code']]['s_company_description']; ?>
                </p>
            </div>
        <?php }; ?>
        </div>
    <?php }; ?>
</table>