<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if ( ! defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$my_author_detail = my_author_detail($admin_info['userid']);


$num       = $db_slave->query('SELECT COUNT(*) FROM '.NV_PREFIXLANG.'_'.$module_data.'_users')->fetchColumn();
$base_url  = NV_BASE_ADMINURL.'index.php?'.NV_LANG_VARIABLE.'='.NV_LANG_DATA.'&amp;'.NV_NAME_VARIABLE.'='.$module_name.'&amp;'.NV_OP_VARIABLE.'=users';
$num_items = ($num > 1) ? $num : 1;
$per_page  = 20;
$page      = $nv_Request->get_int('page', 'get', 1);
$authors   = [];
if ($num) {
    $db_slave->sqlreset()
             ->select('*')
             ->from(NV_PREFIXLANG.'_'.$module_data.'_users')
             ->limit($per_page)
             ->offset(($page - 1) * $per_page);
    $result = $db_slave->query($db_slave->sql());
    while ($row = $result->fetch()) {
        $authors[] = $row;
    }
}


$data = [
    'title'       => $lang_module['add_author'],
    'aid'         => 0,
    'pseudonym'   => '',
    'uid'         => 0,
    'u_account'   => '',
    'image'       => '',
    'description' => ''
];


$xtpl = new XTemplate('users.tpl', NV_ROOTDIR.'/themes/'.$global_config['module_theme'].'/modules/'.$module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$paramUpdate = '';
if (isset($_GET['id'])) {
    $paramUpdate = '&id='.$_GET['id'];
}
$xtpl->assign('PARAM_UPDATE', $paramUpdate);
$xtpl->assign('NV_BASE_ADMINURL', $base_url);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('MODULE_UPLOAD', $module_upload);
$xtpl->assign('OP', $op);
//$xtpl->assign('DATA', $data);
$dataRtn = [];
if ( ! empty($authors)) {
    foreach ($authors as $row) {
        if ($row['gender'] == '1') {
            $row['gender'] = 'Nam';
        } elseif ($row['gender'] == '0') {
            $row['gender'] = 'Nữ';
        } else {
            $row['gender'] = 'Khác';
        }
        $row['unit']       = json_decode($row['meta'])->t_label ?? '';
        $row['url_edit']   = NV_BASE_ADMINURL.'index.php?'.NV_LANG_VARIABLE.'='.NV_LANG_DATA.'&amp;'.NV_NAME_VARIABLE.'='.$module_name
                             .'&amp;'.NV_OP_VARIABLE.'='.$op.'&amp;id='.$row['id'];
        $row['url_delete'] = NV_BASE_ADMINURL.'index.php?'.NV_LANG_VARIABLE.'='.NV_LANG_DATA.'&amp;'.NV_NAME_VARIABLE.'='.$module_name
                             .'&amp;'.NV_OP_VARIABLE.'='.$op.'&amp;uid_delete='.$row['id'];
        $xtpl->assign('ROW', $row);
        $xtpl->parse('main.authorlist.loop');
    }

    $generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);
    if ( ! empty($generate_page)) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.authorlist.generate_page');
    }

    $xtpl->parse('main.authorlist');

    if ($nv_Request->isset_request('id', 'get')) {
        $uid = $nv_Request->get_int('id', 'get', 0);
        $db_slave->sqlreset()
                 ->select('*')
                 ->where('id = '.$uid)
                 ->from(NV_PREFIXLANG.'_'.$module_data.'_users');
        $result = $db_slave->query($db_slave->sql())->fetch();
        $xtpl->assign('DATA', $result);
        $xtpl->parse('main.edit');

//         Lay So
        $departmentArr = [];
        $db_slave->sqlreset()
                 ->select('*')
                 ->from(NV_PREFIXLANG.'_'.$module_data.'_units')
                 ->where('type = 1');
        $departments = $db_slave->query($db_slave->sql());
        while ($row = $departments->fetch()) {
            $departmentArr[] = $row;
        }
        $meta = json_decode($result['meta']);
        $s_id = $meta->s_id ?? 0;
        if ( ! empty($departmentArr)) {
            foreach ($departmentArr as $row) {
                $row['value'] = $row['id'].'-'.$row['name'];
                if ($row['id'] == $s_id) {
                    $row['selected'] = 'selected';
                } else {
                    $row['selected'] = '';
                }
                $html = "<option value='".$row['value']."' ".$row['selected'].">".$row['name']."</option>";
                $xtpl->assign('DEPARTMENT', $html);
                $xtpl->parse('main.loop_department');
            }
        }

        $departmentChildrenArr = [];
        $db_slave->sqlreset()
                 ->select('*')
                 ->from(NV_PREFIXLANG.'_'.$module_data.'_units')
                 ->where('type = 2 and s_id='.$s_id.'');
        $departments = $db_slave->query($db_slave->sql());
        while ($row = $departments->fetch()) {
            $departmentChildrenArr[] = $row;
        }
        $meta = json_decode($result['meta']);
        $p_id = $meta->p_id ?? 0;

        if ( ! empty($departmentChildrenArr)) {
            foreach ($departmentChildrenArr as $row) {
                $row['value'] = $row['id'].'-'.$row['name'];
                if ($row['id'] == $p_id) {
                    $row['selected'] = 'selected';
                } else {
                    $row['selected'] = '';
                }
                $html = "<option value='".$row['value']."' ".$row['selected'].">".$row['name']."</option>";
                $xtpl->assign('DEPARTMENT_CHILDREN', $html);
                $xtpl->parse('main.loop_department_children');
            }
        }
        $schools = [];
        $db_slave->sqlreset()
                 ->select('*')
                 ->from(NV_PREFIXLANG.'_'.$module_data.'_units')
                 ->where('type = 3 and p_id='.$p_id.'');
        $schoolArr = $db_slave->query($db_slave->sql());
        while ($row = $schoolArr->fetch()) {
            $schools[] = $row;
        }
        $meta = json_decode($result['meta']);
        $t_id = $meta->t_id ?? 0;

        if ( ! empty($schools)) {
            foreach ($schools as $row) {
                $row['value'] = $row['id'].'-'.$row['name'];
                if ($row['id'] == $t_id) {
                    $row['selected'] = 'selected';
                } else {
                    $row['selected'] = '';
                }
                $html = "<option value='".$row['value']."' ".$row['selected'].">".$row['name']."</option>";
                $xtpl->assign('SCHOOL', $html);
                $xtpl->parse('main.loop_school');
            }
        }

        if ($result['gender'] == "1") {
            $xtpl->assign('checkM', "checked");
        } elseif ($result['gender'] == "0") {
            $xtpl->assign('checkF', "checked");
        } else {
            $xtpl->assign('checkO', "checked");
        }
    }
}


// Them/Sua tac gia
if ($nv_Request->isset_request('save', 'post')) {
    $full_name                 = $nv_Request->get_title('full_name', 'post', '', 0);
    $email                     = $nv_Request->get_title('email', 'post', '', 1);
    $phone                     = $nv_Request->get_title('phone', 'post', '', 1);
    $address                   = $nv_Request->get_title('address', 'post', '', 1);
    $birth                     = $_POST['birth'] ?? '';
    $gender                    = $nv_Request->get_int('gender', 'post', 1);
    $password                  = $nv_Request->get_title('password', 'post', '', 1);
    $school_id                 = $nv_Request->get_int('school_id', 'post', 0);
    $department                = $_POST['unit_id'] ?? '';
    $department_children       = $_POST['unit_children_id'] ?? '';
    $school                    = $_POST['school_id'] ?? '';
    $department                = explode('-', $department);
    $department_children       = explode('-', $department_children);
    $school                    = explode('-', $school);
    $department_id             = $department[0] ?? 0;
    $department_label          = $department[1] ?? '';
    $department_children_id    = $department_children[0] ?? 0;
    $department_children_label = $department_children[1] ?? '';
    $school_id                 = $school[0] ?? 0;
    $school_label              = $school[1] ?? '';
    $sql                       = 'INSERT INTO '.NV_PREFIXLANG.'_'.$module_data.'_users (full_name, email, phone, address, birth, gender, password, unit_id, meta)
         VALUES ( '.':full_name, :email, :phone, :address, :birth, :gender, :password, :unit_id ,:meta'.')';
    $data_insert               = [];
    $data_insert['full_name']  = $full_name;
    $data_insert['email']      = $email;
    $data_insert['phone']      = $phone;
    $data_insert['address']    = $address;
    $data_insert['birth']      = $birth;
    $data_insert['gender']     = $gender;
    $data_insert['unit_id']    = $school_id;
    $meta                      = [
        's_id'    => $department_id,
        'p_id'    => $department_children_id,
        't_id'    => $school_id,
        's_label' => $department_label,
        'p_label' => $department_children_label,
        't_label' => $school_label
    ];

    $data_insert['meta'] = json_encode($meta);

    if ($nv_Request->isset_request('id', 'get')) {
        $uid = $nv_Request->get_int('id', 'get', 0);

        $sqlUpdate = 'UPDATE '.NV_PREFIXLANG.'_'.$module_data.'_users SET full_name = :full_name, email = :email, phone = :phone,
         address = :address, birth = :birth, gender = :gender, password = :password, unit_id = :unit_id, meta= :meta WHERE id = '.$uid;
        if ($password == '') {
            $data_insert['password'] = $result['password'];
        } else {
            $data_insert['password'] = $crypt->hash_password($password, $hashprefix = '{SSHA}');
        }

        $stmt = $db->prepare($sqlUpdate);
        $stmt->bindParam(':full_name', $full_name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
        $stmt->bindParam(':address', $address, PDO::PARAM_STR);
        $stmt->bindParam(':birth', $birth, PDO::PARAM_STR);
        $stmt->bindParam(':gender', $gender, PDO::PARAM_INT);
        $stmt->bindParam(':password', $data_insert['password'], PDO::PARAM_STR);
        $stmt->bindParam(':unit_id', $school_id, PDO::PARAM_INT);
        $stmt->bindParam(':meta', $data_insert['meta'], PDO::PARAM_STR);
        $stmt->execute();
    } else {
        $password                = $crypt->hash_password($password, $hashprefix = '{SSHA}');
        $data_insert['password'] = $password;
        $id                      = $db->insert_id($sql, 'id', $data_insert);
        if ($id) {
            nv_redirect_location(NV_BASE_ADMINURL.'index.php?'.NV_LANG_VARIABLE.'='.NV_LANG_DATA.'&'.NV_NAME_VARIABLE.'='.$module_name.'&op=users');
        } else {
            $error = 'Lỗi thêm mới';
        }
    }
    //chuyen huong

} else {
}


if ($data['aid'] == $my_author_detail['id']) {
    $xtpl->parse('main.not_change_uid');
} else {
    if ( ! empty($data['uid'])) {
        $xtpl->parse('main.change_uid.uid');
    }
    $xtpl->parse('main.change_uid');
}

if ( ! empty($data['image'])) {
    $xtpl->parse('main.image');
}

if ( ! empty($data['aid'])) {
    $xtpl->parse('main.scroll');
}

//xoa user

if (isset($_GET['uid_delete'])) {
    $sqlDelete = 'Delete FROM '.NV_PREFIXLANG.'_'.$module_data.'_users WHERE id ='.$_GET['uid_delete'];
    $db->query($sqlDelete);
    nv_redirect_location(NV_BASE_ADMINURL.'index.php?'.NV_LANG_VARIABLE.'='.NV_LANG_DATA.'&'.NV_NAME_VARIABLE.'='.$module_name.'&op=users');
}


$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['author_manage'];

include NV_ROOTDIR.'/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR.'/includes/footer.php';
