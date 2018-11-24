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
        <h1 class="page-header" style="margin-top: 4px;">سیل سلپ چرم قربانی</h1>
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
                        <a href="<?= site_url('Qurbani/SaleSlip/AddSlip')?>" ><button type="button"  class="btn btn-default">نیا اندراج</button></a>
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
                            <th style="width:70px;">سلپ نمبر</th>
                            <th>وینڈر</th>
                            <th>عیسوی تاریخ</th>
                            <th>ہجری تاریخ</th>
                            <th>کل چرم(تازہ)</th>
                            <th>کل چرم(باسی)</th>
                            <th>کل رقم</th>
                            <th>تدوین/پرنٹ</th>
                        </tr>
                        </thead>
                        <tbody class="tr_tabel">
                        <?php foreach ($Slips as $slip) {?>
                            <tr>
                                <th style="width:70px;"><?= $slip->Slip_Number?></th>
                                <th><?= $slip->Name?></th>
                                <th><?= $slip->Slip_DateG?></th>
                                <th><?= $slip->Slip_DateH?></th>
                                <?php $total_khaal_Fresh = $slip->Cow_Fresh + $slip->Goat_Fresh + $slip->Sheep_Fresh + $slip->Camel_Fresh + $slip->Buffelo_Fresh;?>
                                <?php $total_khaal_Old = $slip->Cow_Old + $slip->Goat_Old + $slip->Sheep_Old + $slip->Camel_Old + $slip->Buffelo_Old;?>
                                <th><?= $total_khaal_Fresh?></th>
                                <th><?= $total_khaal_Old?></th>
                                <th><?= number_format($slip->Total_Amount)?></th>
                                <th>
                                    <a href="<?= site_url('Qurbani/SaleSlip/AddSlip/').$slip->S_ID?>"><button type="button" data-id="<?$slip->S_ID?>" class="btn btn-success" style="background-color: #517751;border-color: #517751;"><i class="fa fa-edit"></i></button></a>
                                    <a target="_blank" href="<?= site_url('Qurbani/SaleSlip/GatePass/').$slip->S_ID?>"><button type="button" data-id="<?$slip->S_ID?>" class="btn btn-success" style="background-color: #517751;border-color: #517751;"><i class="fa fa-print"> تعداد</i></button></a>
                                    <a target="_blank" href="<?= site_url('Qurbani/SaleSlip/GatePass/').$slip->S_ID.'/'.'1'?>"><button type="button" data-id="<?$slip->S_ID?>" class="btn btn-success" style="background-color: #517751;border-color: #517751;"><i class="fa fa-print"> قیمت</i></button></a>
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