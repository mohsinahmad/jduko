<br><br><br>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="input-group col-md-3"  style="float: left; direction: ltr; ">
                    <label style=" margin-top: 10%;">Account Code<input type="text" class="form-control accountcode" placeholder="Search By Account Code" style="float: left;"></label>
                </div>
                <h1> روابط </h1>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="table-responsive" style="width: 100%;">
                                <label style="float: left; margin-left: 2%;">تلاش کریں
                                    <input type="text" id="myInputTextField" placeholder="Search By Account Name" style="float: left; height: 1%;"></label>
                                <table style=" width: 100%;" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                    <tr>
                                        <th>کمپنی کا نام</th>
                                        <th>اکاونٹ کا نام</th>
                                        <th>کوڈ</th>
                                        <th>ابتدائ بیلنس</th>
                                        <th>موجودہ بلنس</th>
                                        <th>کیفیت</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody class="linktable">
                                    <?php $this->load->view('Accounts/link/linkTable');?>
                                    </tbody>
                                    <input type="hidden" id="account_id">
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="gridSystemModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true" onload="myOnload(); ">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="gridModalLabel">روابط کی تصیح</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid bd-example-row">
                    <div class="row">
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label>اکاونٹ کا نام</label>
                                            <textarea class="form-control" rows="1" id="coa" name="chart" style="width: 200px;resize: none;height: 35px;" disabled></textarea>
                                            <textarea class="form-control" rows="2" id="chart1" name="chart1"  style="width: 200px; display: none;"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <label>لیول کا نام</label>
                                        <textarea class="form-control" rows="1" id="comp" name="comm"  style="width: 200px;resize: none;height: 35px;" disabled ></textarea>
                                        <textarea class="form-control" rows="2" id="comm1" name="comm1" style="width: 200px; display: none;"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label class="control-label" for="inputSuccess">افتتاحی بیلنس</label>
                                        <input type="number" step="any" name="OBalance" class="form-control" style="width: 200px;" id="OBalance" placeholder="00000000.00">
                                    </div>
                                </div>
                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <input name="active" class="" type="hidden" id="IsActive" value="">
                                        <div id="zero">
                                            <input name="active" class="messageCheckbox" type="checkbox" id="active_zero" value="0">غیر فعال
                                        </div>
                                        <div id="one">
                                            <input name="active" class="messageCheckbox" type="checkbox" id="active_one" value="1"> فعال
                                        </div>
                                        <br>
                                        <div id="one">
                                            <input name="closing_save" class="messageCheckbox" type="checkbox" id="closing_save" value="1">آرکایئو
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <input name="Series" class="" type="hidden" id="Series" value="">
                                        <div id="Same_Series">
                                            <input name="Series" class="SeriesCheckbox" type="checkbox" id="active_zero" value="0">اجتماعی سیریز
                                        </div>
                                        <div id="Seprate_Series">
                                            <input name="Series" class="SeriesCheckbox" type="checkbox" id="sep_series" value="">انفرادی سیریز</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary linkupdate">محفوظ کریں</button>
                </div>
            </div>
        </div>
    </div>
</div>