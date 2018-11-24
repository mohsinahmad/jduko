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
        <h1 class="page-header" style="margin-top: 4px;">ادائیگی واؤچر</h1>
    </div>
</div>
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
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading col-md-12" style="width: 100%;">
                <div class="row">
                    <div class="col-md-4" style="margin-top: -0.5%;width: 26%;" >
                        <a href="<?= site_url('Qurbani/ExpenceDetail/ExpenceVoucher')?>" ><button type="button"  class="btn btn-default">نیا اندراج</button></a>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="table-responsive" style="width: 100%;">
                    <label style="float: left;">تلاش کریں
                        <input type="text" id="myInputTextField" style="float: left;"></label>
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th style="width:70px;text-align: center">رسید نمبر</th>
                            <th style="text-align: center">ادائیگی بنام</th>
                            <th style="text-align: center">عیسوی تاریخ</th>
                            <th style="text-align: center">ہجری تاریخ</th>
                            <th style="text-align: center">رقم</th>
                            <th style="text-align: center;width: 10%">بابت</th>
                            <th style="width: 11%;text-align: center"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($vouchers as $voucher) {?>
                            <tr>
                                <td style="text-align: center"><?= $voucher->VoucherNumber?></td>
                                <td style="text-align: center"><?= $voucher->Name?></td>
                                <td style="text-align: center"><?= $voucher->DateG?></td>
                                <td style="text-align: center"><?= $voucher->DateH?></td>
                                <td style="text-align: center"><?= $voucher->Amount?></td>
                                <td><textarea class="form-control"  rows="1" cols="15" readonly><?= $voucher->Description?></textarea></td>
                                <td style="text-align: center">
                                    <a href="<?= site_url('Qurbani/ExpenceDetail/ExpenceVoucher/').$voucher->Id?>"> <button type="button" class="btn btn-success" style="background-color: #517751;border-color: #517751;"><i class="fa fa-pencil-square-o"></i>
                                        </button></a>
                                    <a target="_blank" href="<?= site_url('Qurbani/ExpenceDetail/ViewExpence_Voucher/').$voucher->Id?>"> <button type="button" class="btn btn-success " style="background-color: #517751;border-color: #517751;"><i class="fa fa-print"></i></button></a>
                                </td>
                            </tr>
                        <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>