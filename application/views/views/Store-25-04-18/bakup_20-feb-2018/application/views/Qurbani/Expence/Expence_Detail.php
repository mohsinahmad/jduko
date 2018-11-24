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
        <h1 class="page-header" style="margin-top: 4px;"> مصارف کی تفصیل </h1>
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
                     <div class="col-md-4" style="margin-top: -0.5%;width: 26%;" >
                        <a href="<?= site_url('Qurbani/ExpenceDetail/Add_Expence/')?>" ><button type="button"  class="btn btn-default">نیا اندراج</button></a>
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
                            <th>شمار نمبر</th>
                            <th>بنام</th>
                            <th>عیسوی تاریخ</th>
                            <th>قمری تاریخ</th>
                            <th>تفصیل</th>
                            <th>رقم</th>
                            <th>تدوین/پرنٹ</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($Expence_Detail as $key => $value) {?>
                            <tr>
                                <td><?= $value->Voucher_Number?></td>
                                <td><?= $value->Receiver_Name?></td>
                                <td><?= $value->DateG?></td>
                                <td><?= $value->DateH?></td>
                                <td><?= $value->Description?></td>
                                <td><?= number_format($value->SUM_Amount)?></td>
                                <td>
                                    <a href="<?= site_url('Qurbani/ExpenceDetail/Edit_Expence/').$value->MasterId?>"><button type="button" class="btn btn-success" style="background-color: #517751;border-color: #517751;"><i class="fa fa-pencil-square-o"></i></button></a>
                                    <a target="_blank" href="<?= site_url('Qurbani/ExpenceDetail/PrintExpenseVoucher/').$value->MasterId?>"> <button type="button" class="btn btn-success" style="background-color: #517751;border-color: #517751;"><i class="fa fa-print"></i></button></a>
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
<script src="<?= base_url()."assets/js/"; ?>jquery-1.12.4.js"></script>
<script type="text/javascript">

    $('#search').on('click',function(){
        var to = $('#to').val();
        var from = $('#from').val();
        if(to == "" && from){
            $.ajax({
                type:'GET',
                url:'<?= site_url('Qurbani/Receipt/index');?>',
                success:function(response){
                    $('.tr_tabel').html(response);
                }
            });

        }else{
            $.ajax({
                type:'GET',
                url:'<?= site_url('Qurbani/Receipt/index') ?>'+'/'+to+'/'+from,
                success:function(response){
                    $('.tr_tabel').html(response);
                }
            });
        }
    });
</script>