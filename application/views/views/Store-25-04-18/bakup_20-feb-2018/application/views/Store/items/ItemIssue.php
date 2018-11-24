<style type="text/css">
    #dataTables-example tr:hover  {
        cursor: pointer;
    }
.form-group input[type="checkbox"] {
    display: none;
}

.form-group input[type="checkbox"] + .btn-group > label span {
    width: 20px;
}

.form-group input[type="checkbox"] + .btn-group > label span:first-child {
    display: none;
}
.form-group input[type="checkbox"] + .btn-group > label span:last-child {
    display: inline-block;   
}

.form-group input[type="checkbox"]:checked + .btn-group > label span:first-child {
    display: inline-block;
}
.form-group input[type="checkbox"]:checked + .btn-group > label span:last-child {
    display: none;   
}
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    /* display: none; <- Crashes Chrome on hover */
    -webkit-appearance: none;
    margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
}
</style>
<div class="row">
    <div class="col-lg-12">
        <br>
    <h1 class="page-header" style="margin-top: 10px;">اشیاء کا اجراء</h1>
    <h3 class="page-header" style="margin-top: 10px;">ڈیمانڈ</h3>
</div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
<!--             <div class="panel-heading col-md-12" style="width: 100%;">

             </div> -->
            <div class="panel-body">
                <div class="table-responsive" style="width: 100%;">
                <label style="float: left;">تلاش کریں
                <input type="text" id="myInputTextField" style="float: left;"></label>
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th style="text-align: center">فارم نمبر</th>
                            <th style="text-align: center">شعبے کا نام</th>
                            <th style="text-align: center">ڈونیشن ٹائپ</th>
                            <th style="text-align: center">عیسوی تاریخ</th>
                            <th style="text-align: center">ہجری تاریخ</th>
                            <th style="width: 10%;text-align: center">درجہ</th>
                        </tr>
                        </thead>
                        <tbody class="cashbookTable">
                        <?php foreach ($demands as $value): ?>
                            <tr class="DemandItems" data-id='<?= $value->d_id?>'>
                                <td style="text-align: center"><?= $value->Form_Number?></td>
                                <td style="text-align: center"><?= $value->LevelName?></td>
                                <td style="text-align: center"><?= $value->Donation_Type?></td>
                                <td style="text-align: center"><?= $value->Form_DateG?></td>
                                <td style="text-align: center"><?= $value->Form_DateH?></td>
                                <?php $status = $value->Status?>
                                <td style="text-align: center"><?php if($status == 1){?>
                                        <span class="label label-info" style="font-size: 0.9em;padding-top: 0px;padding-bottom: 0px;">جزوی منظوری</span>
                                    <?php }else if($status == 2){?>
                                        <span class="label label-success" style="font-size: 0.9em;padding-top: 0px;padding-bottom: 0px;">منظورشدہ</span>
                                    <?php }else if($status == 3){?>
                                        <span class="label label-primary" style="font-size: 0.9em;padding-top: 0px;padding-bottom: 0px;">اجراء زیر التواء</span>
                                    <?php }?>
                                </td>
                            </tr>
                    <?php endforeach?>
                        </tbody>
                    </table>
                </div>
            </div>
      </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <br>
        <h3 class="page-header" style="margin-top: 10px;">آئٹم</h3>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
<!--             <div class="panel-heading col-md-12" style="width: 100%;">

             </div> -->
            <div class="panel-body">
                <div class="table-responsive" style="width: 100%;">
                    <table class="table table-striped table-bordered table-hover demand">
                        <thead>
                        <tr>
                            <th style="text-align: center">آئٹم کوڈ</th>
                            <th style="text-align: center"> آئٹم کا نام</th>
                            <th style="text-align: center">منظور شدہ مقدار</th>
                        </tr>
                        </thead>
                        <tbody class="DemandItemsData">

                        </tbody>
                        <tfoot>
                            <tr>
                            <td colspan="6">
                                <div class="row" style="float: left; direction: ltr; margin-left:-10%; ">
                                    <div class="col-xs-4">
                                        <button class="btn btn-default Issue" >اجراء اشیاء</button>
                                    </div>
                                </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
      </div>
    </div>
</div>
<div id="copy" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                Issue Details
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form>
                        <div class="row">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="form-group" style="width:250px;">
                                            <label>عیسوی تاریخ</label>
                                            <div class="form-group">
                                                <input class="form-control CenglishDate englishDate" type="date" name="CenglishDate" value="<?= date('Y-m-d')?>" placeholder="انگریزی کی تاریخ منتخب کریں" autofocus>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label class="control-label" for="inputSuccess">ہجری تاریخ</label>
                                            <input class="form-control islamicDate" id="CislamicDate" name="CislamicDate" style="width: 250px;"  type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="inputSuccess" >تفصیل</label>
                                        <textarea class="form-control" rows="3" id="Details"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary Save">اجراء</button>
                </div>
            </div>
        </div>
    </div>
</div>