<style type="text/css">
    .VoucherMOdal {
        border: 0;
        outline: 0;
        background: transparent;
        border-bottom: 1px solid black;
    }
    .available{
        background-color: #cfffdc;
    }
    .complete{
        background-color: white;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <br>
        <h1 class="page-header" style="margin-top: 4px;">کیش رسید</h1>
    </div>
</div>
<?php if($this->session->flashdata('success')) :?>
    <div class="alert alert-success alert-dismissable" id="dalert">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <?= $this->session->flashdata('success');?>
    </div>
<?php endif;
    if($this->session->flashdata('error')) :?>
    <div class="alert alert-danger alert-dismissable" id="dalert">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <?= $this->session->flashdata('error');?>
    </div>
<?php endif ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading col-md-12" style="width: 100%;">
                <div class="row">
                    <div class="input-group col-md-4" style="float: left; direction: ltr;">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                        <input type="text" class="form-control" id="daterange" name="daterange" />
                        <input type="hidden" id="to" name="to">
                        <input type="hidden" id="from" name="from">
                    </div>
                    <button class="btn btn-default" id="search" style="float: left; direction: ltr; margin-left:0%; ">تلاش کریں</button>
                    <div class="col-md-4" style="margin-top: -0.5%;width: 26%;" >
                        <a href="<?= site_url('Qurbani/CashReciept/AddReciept')?>" ><button type="button"  class="btn btn-default">نیا اندراج</button></a>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="table-responsive" style="width: 100%;">
                    <label style="float: left;">تلاش کریں
                        <input type="text" id="myInputTextField" style="float: left;"></label>
                    <table class="table table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th style="width:70px;">رسید نمبر</th>
                            <th>وینڈر</th>
                            <th>عیسوی تاریخ</th>
                            <th>ہجری تاریخ</th>
                            <th>وصول کردہ رقم</th>
                            <th>تفصیل</th>
                            <th>تدوین/پرنٹ</th>
                        </tr>
                        </thead>
                        <tbody class="tr_tabel">
                        <?php foreach ($Reciepts as $Reciept) {?>
                            <tr>
                                <th style="width:70px;"><?= $Reciept->Reciept_Number?></th>
                                <th><?= $Reciept->Name?></th>
                                <th><?= $Reciept->DateG?></th>
                                <th><?= $Reciept->DateH?></th>
                                <th><?= $Reciept->Amount?></th>
                                <th><?= $Reciept->Remarks?></th>
                                <th>
                                    <a href="<?= site_url('Qurbani/CashReciept/AddReciept/').$Reciept->Reciept_Id?>"> <button type="button" class="btn btn-success" style="background-color: #517751;border-color: #517751;"><i class="fa fa-pencil-square-o"></i></button></a>
                                </th>
                            </tr>
                        <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>