<style type="text/css">
    .VoucherMOdal {
        border: 0;
        outline: 0;
        background: transparent;
        border-bottom: 1px solid black;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <br>
        <h1 class="page-header" style="margin-top: 4px;"> رقوم کی منتقلی </h1>
    </div>
</div>
<?php if($this->session->flashdata('success')) :?>
    <div class="alert alert-success alert-dismissable" id="dalert">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <?php echo $this->session->flashdata('success');?>
    </div>
<?php endif ?>
<?php if($this->session->flashdata('error')) :?>
    <div class="alert alert-danger alert-dismissable" id="dalert">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <?php echo $this->session->flashdata('error');?>
    </div>
<?php endif ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading col-md-12" style="width: 100%;">
                <div class="row">
                    <div class="input-group col-md-3" style="float: left; direction: ltr;">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                        <input type="text" class="form-control" id="daterange" name="daterange" />
                    </div>
                    <div class="input-group col-md-3"  style="float: left; direction: ltr; margin-left: 1%;">
                        <label class="input-group-addon">اکاونٹ کوڈ</label>
                        <input class="form-control AccountCode" placeholder="تلاش اکاؤنٹ کوڈ" style="width: 100%; margin-right: 2%;"  type="text" >
                    </div>
                    <input type="hidden" id="to" name="to">
                    <input type="hidden" id="from" name="from">
                    <button class="btn btn-default search" style="float: left; direction: ltr; margin-left:0%; ">تلاش کریں</button>
                    
                        <div class="col-md-4" style="margin-top: -3.5%;width: 26%;" >
                            <a href="<?php echo site_url('Qurbani/Transfer/AddNew')?>" ><button type="button" style="margin-top: 15%" class="btn btn-default">نیا اندراج</button></a>

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
                            <th>سلپ نمبر #</th>
                            <th>تاریخ</th>
                            <th>جمع شدہ</th>
                            <th>منتقل شدہ رقم</th>
                            <th>بیلینس</th>
                            <th>تدوین</th>
                            <th>پرنٹ</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($transafer_data as $transafer_datum) {?>
                            <tr>
                                <td><?= $transafer_datum->Slip_Number?></td>
                                <td><?= $transafer_datum->DateG?></td>
                                <td><?= $transafer_datum->Total_Collection?></td>
                                <td><?= $transafer_datum->This_Transfer_Amount?></td>
                                <td><?= $transafer_datum->Cash_In_Hand_After_This?></td>
                                <td>
                                    <a href="<?= site_url('Qurbani/Transfer/AddNew').'/'.$transafer_datum->Id?>"> <button type="button"  class="btn btn-success" style="background-color: #517751;border-color: #517751;"><i class="fa fa-pencil-square-o"></i></button></a></td>
                                <td>
                                    <a target="_blank" href="<?= site_url('Qurbani/Transfer/Print_recpt').'/'.$transafer_datum->Id?>"> <button type="button"  class="btn btn-success " style="background-color: #517751;border-color: #517751;"><i class="fa fa-print"></i></button></a>
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