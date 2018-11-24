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
            <h1 class="page-header" style="margin-top: 4px;">بقیاء حصص کی تفصیل</h1>
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
            <div class="col-md-12" style="width: 100%;">
                <div class="row">
                    <!-- <div class="input-group col-md-4" style="float: left; direction: ltr;">
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
                    </div> -->
                </div>
            </div>
            <div class="panel-body">
                <div class="table-responsive" style="width: 100%;">
                    <label style="float: left;">تلاش کریں
                        <input type="text" id="myInputTextField" style="float: left;"></label>
                    <table class="table table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th style="width:80px;">نمبر شمار</th>
                            <th style="width:80px;">گاےُ نمبر</th>
                            <th>حصص</th>
                            <th>قربانی کا دن</th>
                            <th>قربانی کا وقت</th>
                        </thead>
                        <tbody class="">
                        <?php foreach($Remaining as $key => $value){?>
                            <tr>
                                <td><?php echo ++$key?></td>
                                <td><?php echo $value->CODE?></td>
                                <td><?php echo ( 7 - $value->Count)?></td>
                                <?php if($value->Day == 1){?>
                                    <td><?php echo "۱۰ ذی الحج "?></td>
                                <?php } elseif($value->Day == 2){?>
                                    <td><?php echo "۱۱ ذی الحج "?></td>
                                <?php } elseif($value->Day == 3){?>
                                    <td><?php echo "۱۲ ذی الحج "?></td>
                                <?php }?>
                                <td><?php echo $value->Time?></td>
                            </tr>
                        <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
