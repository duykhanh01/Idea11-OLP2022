<!-- BEGIN: main -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/i18n/{NV_LANG_INTERFACE}.js"></script>
<!-- BEGIN: authorlist -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th>ID</th>
				<th>Họ và tên</th>
				<th class="text-center">Ngày sinh</th>
                <th class="text-center">Giới tính</th>
                <th class="text-center">Số điện thoại</th>
                <th class="text-center">Email</th>
                <th class="text-center">Địa chỉ</th>
                <th class="text-center">Đơn vị</th>
			</tr>
		</thead>
		<!-- BEGIN: generate_page -->
		<tfoot>
			<tr>
				<td colspan="6">{GENERATE_PAGE}</td>
			</tr>
		</tfoot>
		<!-- END: generate_page -->
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
                <td class="text-center">{ROW.id}</td>
                <td class="text-center">{ROW.full_name}</td>
                <td class="text-center">{ROW.birth}</td>
                <td class="text-center">{ROW.gender}</td>
                <td class="text-center">{ROW.phone}</td>
                <td class="text-center">{ROW.email}</td>
                <td class="text-center">{ROW.address}</td>
                <td class="text-center">{ROW.unit}</td>

			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: authorlist -->
<a id="editAuthor"></a>
<form action="{NV_BASE_ADMINURL}index.php" method="post" onsubmit="formSubmit(event, this)">
    <input type="hidden" name ="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
    <input type="hidden" name ="{NV_OP_VARIABLE}" value="{OP}" />
    <input type="hidden" name ="aid" id ="aid" value="{DATA.aid}" />
    <input type="hidden" name="save" value="1" />
    <div class="table-responsive">
        <table id="edit" class="table table-striped table-bordered table-hover">
            <caption><em class="fa fa-file-text-o">&nbsp;</em>{DATA.title}</caption>
            <tfoot>
                <tr>
                    <td class="text-center" colspan="2"><input class="btn btn-primary frm-item" name="submit" type="submit" value="{LANG.save}" /></td>
                </tr>
            </tfoot>
            <tbody>
                <tr>
                    <td class="text-right w200"><strong>{LANG.author_pseudonym}: </strong> <sup class="required">(*)</sup></td>
                    <td><div class="form-group mb-0"><input class="form-control frm-item w500" name="full_name" id="pseudonym" type="text" value="{DATA.full_name}" maxlength="100" data-mess="Yêu cầu nhập" /></div></td>
                </tr>
                <tr>
                    <td class="text-right"><strong>Chọn sở: </strong> <sup class="required">(*)</sup></td>
				    <td><div class="form-group mb-0">
						<select class="form-control w300" name="unit_id" id="unit" data-mess="Không được bỏ trống">
							<option value="0">Chọn sở</option>
						</select>
                    </div></td>
				</tr>
                <tr>
                    <td class="text-right"><strong>Chọn phòng: </strong> <sup class="required">(*)</sup></td>
                    <td><div class="form-group mb-0">
                            <select class="form-control w300" name="unit_children_id" id="unit_children" data-mess="Không được bỏ trống">
                                <option value="0">Chọn phòng</option>
                            </select>
                        </div></td>
                </tr>
                <tr>
                    <td class="text-right"><strong>Chọn trường: </strong> <sup class="required">(*)</sup></td>
                    <td><div class="form-group mb-0">
                            <select class="form-control w300" name="select_school" id="select-school" data-mess="Không được bỏ trống">
                                <option value="0">Chọn trường</option>
                            </select>
                        </div></td>
                </tr>
                <!-- BEGIN: not_change_uid -->
                <input type="hidden" name="uid" value="{DATA.uid}" />
                <!-- END: not_change_uid -->
                <tr>
                    <td class="text-right"><strong>{LANG.author_image}: </strong></td>
                    <td>
                        <input class="form-control frm-item w500 pull-left" style="margin-right: 5px" type="text" name="image" id="image" value="{DATA.image}"/>
                        <input type="button" value="{GLANG.browse_image}" name="selectimg" id="selectimg" class="btn btn-info frm-item" />
                        <!-- BEGIN: image -->
                        <div><img src="{DATA.image}" width="100" class="thumbnail mb-0"/></div>
                        <!-- END: image -->
                    </td>
                </tr>
                <tr>
                    <td class="text-right"><strong>{LANG.author_description}: </strong></td>
                    <td><textarea class="w500 frm-item form-control" id="description" name="description" cols="100" rows="5">{DATA.description}</textarea></td>
                </tr>
            </tbody>
        </table>
    </div>
</form>
<script>
function del_author(id) {
    if (confirm(nv_is_del_confirm[0])) {
        $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=authors&nocache=' + new Date().getTime(), 'authordel=1&aid=' + id, function(res) {
            var r_split = res.split('_');
            if (r_split[0] == 'OK') {
                window.location.href = script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=authors';
            } else {
                alert(nv_is_del_confirm[2]);
            }
        })
    }
    return false;
}
function changeStatus(sel,id) {
    $(sel).prop("disabled", true);
    $.ajax({
        type: 'POST',
  		cache: !1,
  		url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=authors',
  		data: 'changeStatus=1&aid=' + id,
  		success: function(b) {
  		    setTimeout(function() {
				$(sel).prop("disabled", false)
			}, 1000)
  		}
   })
}
function formSubmit(event, form) {
    event.preventDefault();
    $(".has-error", form).removeClass("has-error");
    var pseudonym = $("[name=pseudonym]", form).val(),
        uid = $("[name=uid]", form).val();
    pseudonym = strip_tags(trim(pseudonym));
    $("[name=pseudonym]", form).val(pseudonym);
    
    if ("" == pseudonym) {
        alert($("[name=pseudonym]", form).data("mess"));
        $("[name=pseudonym]", form).parent().addClass('has-error');
        $("[name=pseudonym]", form).focus()
    } else if (!uid) {
        alert($("[name=uid]", form).data("mess"));
        $("[name=uid]", form).parent().addClass('has-error');
        $("#uid").select2('open')
    } else {
        var data = $(form).serialize();
        $(".frm-item", form).prop("disabled", true);
        $.ajax({
    		type: $(form).prop("method"),
    		cache: !1,
    		url: $(form).prop("action"),
    		data: data,
    		dataType: "json",
    		success: function(b) {
    			if ("error" == b.status) {
    			 $(".frm-item", form).prop("disabled", false);
    			 alert(b.mess);
                 if ("" != b.input) {
                    if ("uid" == b.input) {
                        $("#uid").select2('open')
                    } else {
                        $("[name=" + b.input + "]", form).parent().addClass('has-error');
                        $("[name=" + b.input + "]", form).focus()
                    }
                 }
    			} else {
    				window.location.href = b.mess
    			}
    		}
    	})
    }
}
$(document).ready(function() {
    $("#uid").select2({
        language: nv_lang_interface,
        ajax: {
        url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=authors&get_account_json=1',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                  return {
                      q: params.term,
                      page: params.page
                  };
              },
            processResults: function (data, params) {
                params.page = params.page || 1;
                return {
                    results: data.results,
                    pagination: {
                        more: (params.page * 30) < data.total_count
                    }
                };
            },
        cache: true
        },
        escapeMarkup: function (markup) {
            return markup
        },
        minimumInputLength: 3,
        placeholder: "{LANG.author_select_account}",
        templateResult: function (repo) {
            if (repo.loading) return repo.text;
            return repo.title
        },
        templateSelection: function (repo) {
            return repo.title || repo.text
        }
    });
    
    // Topics
    $("#selectimg").click(function() {
        nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=image&path={NV_UPLOADS_DIR}/{MODULE_UPLOAD}/authors&type=image&currentpath={NV_UPLOADS_DIR}/{MODULE_UPLOAD}/authors", "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
        return false;
    });
});
<!-- BEGIN: scroll -->
$(window).on('load', function() {
    $('html, body').animate({
        scrollTop: $("#editAuthor").offset().top
    }, 200);
});
<!-- END: scroll -->
</script>
<!-- END: main -->