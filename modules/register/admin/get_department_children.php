<?php

$parentId = $nv_Request->get_int('parent_id', 'get', 0);
$sql = 'SELECT * FROM ' .NV_PREFIXLANG . '_' . $module_data .  '_units WHERE type=2 and s_id=' .$parentId. ' order by name asc';
$result = $db->query($sql);
$arrRtn = $result->fetchAll();
var_dump($arrRtn);

$html = '<option value="0">Chọn phòng</option>';
foreach ($arrRtn as $row) {
    $html .= "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
}
echo $html;