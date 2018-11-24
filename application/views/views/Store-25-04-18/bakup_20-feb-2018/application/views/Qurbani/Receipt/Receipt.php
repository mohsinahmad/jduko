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
            <h1 class="page-header" style="margin-top: 4px;"> قربانی رسید </h1>
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
                    <div class="input-group col-md-4" style="float: left; direction: ltr;">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                        <input type="text" class="form-control" id="daterange" name="daterange" />
                        <input type="hidden" id="to" name="to">
                        <input type="hidden" id="from" name="from">
                    </div>
                    <button class="btn btn-default" id="search" style="float: left; direction: ltr; margin-left:0%; ">تلاش کریں</button>

                    <div class="col-md-4" style="margin-top: -0.5%;width: 26%;" >
                        <a href="<?= site_url('Qurbani/Receipt/NewReceipt/')?>" ><button type="button"  class="btn btn-default">نیا اندراج</button></a>
                    </div>
                    <div class="col-md-4" style="width: 19%;margin-right: 13%;">
                        <input type="text" class="form-control cow_number" placeholder="گائے نمبر سے تلاش کیجیئے">
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
                            <th style="width:70px;">گاےُ نمبر#</th>
                            <th>رسید نمبر #</th>
                            <th>نام</th>
                            <th>عیسوی تاریخ</th>
                            <th>قربانی کا دن</th>
                            <th>قربانی کا وقت</th>
                            <th>حصص</th>
                            <th>کل رقم</th>
                            <th>تدوین</th>
                            <th>پرنٹ</th>
                        </tr>
                        </thead>
                        <tbody class="tr_tabel">
                            <?php $this->load->view('Qurbani/Receipt/ReceiptTable')?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url()."assets/"; ?>js/jquery-1.12.4.js"></script>
<script type="text/javascript">

    $('#search').on('click',function(){
        var to = $('#to').val();
        var from = $('#from').val();
        if(to == "" && from){
            $.ajax({
                type:'GET',
                url:'<?= site_url('Qurbani/Receipt/index');?>',
                success:function(response){
                    $('.tr_tabel').html('');
                    $('.tr_tabel').html(response);
                }
            });

        }else{
            $.ajax({
                type:'GET',
                url:'<?= site_url('Qurbani/Receipt/index/') ?>'+to+'/'+from,
                success:function(response){
                    $('.tr_tabel').html('');
                    $('.tr_tabel').html(response);
                }
            });
        }
    });

    $(document).on('keyup','.cow_number',function(){
        var cow_number = $(this).val();
        if (cow_number != ''){
            $.ajax({
                type:'GET',
                url:'<?= site_url('Qurbani/Receipt/get_receipt_By_CowNumber/') ?>'+cow_number,
                success:function(response){
                    $('.tr_tabel').html('');
                    $('.tr_tabel').html(response);
                }
            });
        }else{
            location.reload();
        }
    });
</script>