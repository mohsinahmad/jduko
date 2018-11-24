<style type="text/css">
    #dataTables-example tr:hover  {
        cursor: pointer;
    }
    .demand tr:hover  {
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
        <h1 class="page-header" style="margin-top: 10px;">ڈیمانڈز کی منظوری</h1>
        <h3 class="page-header" style="margin-top: 10px;">ڈیمانڈز</h3>
    </div>
</div>
<div class="row">
    <div>
        <?php if($this->session->flashdata('success')) :?>
            <div class="alert alert-success alert-dismissable" id="dalert">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <?= $this->session->flashdata('success');?>
            </div>
            <?php endif ?>
            <?php if($this->session->flashdata('error')) :?>
                <div class="alert alert-danger alert-dismissable" id="dalert">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <?= $this->session->flashdata('error');?>
                </div>
            <?php endif ?>
    </div>
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="table-responsive" style="width: 100%;">
                    <label style="float: left;">تلاش کریں
                        <input type="text" id="myInputTextField" style="float: left;">
                    </label>
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th style="text-align: center">فارم نمبر</th>
                                <th style="text-align: center">شعبے کا نام</th>
                                <th style="text-align: center">ڈونیشن ٹائپ</th>
                                <th style="text-align: center">عیسوی تاریخ</th>
                                <th style="text-align: center">ہجری تاریخ</th>
                                <th style="width: 14%;text-align: center">درجہ</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($demands as $value): ?>
                            <tr class="DemandItems" data-id='<?= $value->d_id?>'>
                                <td class="FormNumber"><?= $value->Form_Number?></td>
                                <td><?= $value->LevelName?></td>
                                <td><?= $value->Donation_Type?></td>
                                <td><?= $value->Form_DateG?></td>
                                <td><?= $value->Form_DateH?></td>
                                <?php $status = $value->Status?>
                                <td><?php if($status == 1) {?>
                                        <span class="label label-warning" style="font-size: 0.9em;padding-top: 0px;padding-bottom: 0px;">جزوی منظوری</span>
                                    <?php }else if($status == 0){?>
                                        <span class="label label-danger" style="font-size: 0.9em;padding-top: 0px;padding-bottom: 0px;">منظوری زیر التواء</span>
                                    <?php }else if($status == 2){?>
                                        <span class="label label-success" style="font-size: 0.9em;padding-top: 0px;padding-bottom: 0px;">منظور شدہ</span>
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
<form action="<?= site_url('Store/ApproveDemand/SaveApprovedDemand'); ?>" method="POST">
<div class="row">
    <div class="col-lg-12">
        <br>
        <h3 class="page-header item-heading" style="margin-top: 10px;">اشیاء</h3>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="table-responsive" style="width: 100%;">
                    <table class="table table-striped table-bordered table-hover demand">
                        <thead>
                            <tr>
                                <th style="text-align: center">آئٹم کوڈ</th>
                                <th style="text-align: center"> آئٹم کا نام</th>
                                <th style="text-align: center">ڈیمانڈ مقدار</th>
                                <th style="text-align: center">عطیہ کی قسم</th>
                                <th style="width: 14%;text-align: center">منظور شدہ مقدار</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="DemandItemsData">

                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="6">
                                    <div class="row" style="margin-left:-10%; ">
                                        <div class="[ form-group ] col-xs-3" style="float:left; margin-top: 3%">
                                            <input type="checkbox" name="IsComplete" id="fancy-checkbox-success" autocomplete="off" value="1" />
                                            <div class="[ btn-group ]">
                                                <label for="fancy-checkbox-success" class="[ btn btn-success ]">
                                                    <span class="[ glyphicon glyphicon-ok ]"></span>
                                                    <span> </span>
                                                </label>
                                                <label for="fancy-checkbox-success" class="[ btn btn-default active ]">
                                                    ڈیمانڈ  کلوز
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <div class="form-group" style="width:250px;">
                                                <label>عیسوی تاریخ</label>
                                                <div class="form-group">
                                                    <input class="form-control CenglishDate englishDate" type="date" name="CenglishDate" value="<?= date('Y-m-d')?>" placeholder="انگریزی کی تاریخ منتخب کریں" >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <div class="form-group">
                                                <label class="control-label" for="inputSuccess">ہجری تاریخ</label>
                                                <input class="form-control islamicDate" id="CislamicDate" name="CislamicDate" style="width: 250px;"  type="text" readonly>
                                            </div>
                                        </div>
                                        <div class="col-xs-3" style="margin-top: 3%;">
                                            <button style="float: left;" type="submit" class="btn btn-default ApproveDemand" >منظور ڈیمانڈ</button>
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
</form>
<!-- <div id="copy" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
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
</div> -->
<div id="itemApprove" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="gridModalLabel"></h4>
                <h4 style="margin-right: 70%;margin-top: -22px;" class="quan"></h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid items">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary Approve">منظور کریں</button>
                </div>
            </div>
        </div>
    </div>
</div>