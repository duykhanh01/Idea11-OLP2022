<!-- BEGIN: main -->
{*<div class="table-responsive">*}
{*    <table class="table table-striped table-bordered table-hover">*}
{*        <colgroup>*}
{*            <col class="w50">*}
{*            <col class="w200">*}
{*            <col span="1"/>*}
{*            <col class="w150">*}
{*        </colgroup>*}
{*        <thead>*}
{*        <tr class="text-center">*}
{*            <th>ID</th>*}
{*            <th>Date</th>*}
{*        </tr>*}
{*        </thead>*}
{*        <tbody>*}
{*        <!-- BEGIN: loop -->*}
{*        <tr>*}
{*            <td class="text-center"> {DATA.id} </td>*}
{*            <td class="text-center"> {DATA.date} </td>*}

{*        </tr>*}
{*        <!-- END: loop -->*}
{*        </tbody>*}
{*    </table>*}
{*</div>*}
<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
    Launch demo modal
</button>

<div id="myModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">This is Modal</h4>
            </div>
            <div class="modal-body">
                <p>Modal body</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- END: main -->