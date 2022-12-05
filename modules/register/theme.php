<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

function get_schedule()
{
    global $module_name, $lang_module, $lang_global, $module_info, $module_data, $client_info, $page_config, $db_slave;
    $db_slave->sqlreset()
             ->select('*')
             ->from(NV_PREFIXLANG.'_'.$module_data.'_teaches');
    $xtpl = new XTemplate('main.tpl', NV_ROOTDIR.'/themes/'.$module_info['template'].'/modules/'.$module_info['module_theme']);

    $data = $db_slave->query($db_slave->sql())->fetchAll();
    foreach ($data as $row) {
        $xtpl->assign('DATA', $row);
        $xtpl->parse('main.loop');
    }
    $xtpl->parse('main');

    return $xtpl->text('main');
}

function weeks()
{
    $nextWeek = strtotime('+1 week');
    /* change the upper bound of the loop using `date('W')` */
    $ddate = "2022-12-31";
    $date  = new DateTime($ddate);
    $week  = $date->format("W");
    // echo "Weeknummer: $week";
    for ($i = 0; $i < date('w'); $i++) {
        $date  = date('Y-m-d', strtotime('-'.$i.' week'));
        $nbDay = date('N', strtotime($date));

        $monday = new \DateTime($date);
        $sunday = new \DateTime($date);

        $monday->modify('-'.($nbDay - 1).' days');
        $sunday->modify('+'.(7 - $nbDay).' days');

        if ($nextWeek > strtotime($sunday->format('Y-m-d'))) {
            $weeks[$monday->format('W')] = $monday->format('Y-m-d').' - '.$sunday->format('Y-m-d');
        }
    }

    var_dump($weeks);
    exit();

    return $weeks;
}