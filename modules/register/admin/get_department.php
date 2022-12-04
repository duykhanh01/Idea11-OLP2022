<?php

$sql = 'SELECT * FROM ' .NV_PREFIXLANG . '_' . $module_data .  '_units WHERE type=1 order by name asc';
$result = $db->query($sql);
$arrRtn = $result->fetchAll();
$html = '';
foreach ($arrRtn as $row) {
    $html .= "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
}
echo $html;