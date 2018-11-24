<script src="<?php echo base_url()."assets/"; ?>js/jquery-1.12.4.js"></script>
<div class="row">
    <div class="col-lg-12">
        <br>
        <h1 class="page-header" style="margin-top: 10px;">واپس کردہ اشیاء</h1>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <!--             <div class="panel-heading col-md-12" style="width: 100%;">
                <div class="row">
                    <div class="input-group col-md-3" style="float: left; direction: ltr;">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                        <input type="text" class="form-control" id="daterange" name="daterange" />
                    </div>
                    <div class="input-group col-md-3"  style="float: left; direction: ltr; margin-left: 1%;">
                        <label class="input-group-addon">Account Code</label>
                        <input class="form-control AccountCode" placeholder="تلاش اکاؤنٹ کوڈ" style="width: 100%; margin-right: 2%;"  type="text" >
                    </div>
                    <input type="hidden" id="to" name="to">
                    <input type="hidden" id="from" name="from">
                    <button class="btn btn-default search" style="float: left; direction: ltr; margin-left:0%; ">تلاش کریں</button>
                    <div class="input-group col-md-2"  style="float: left; direction: ltr;">
                        <label class="input-group-addon">CP</label>
                        <input class="form-control voucherNo"  placeholder="تلاش واؤچر نمبر " style="width: 100%; margin-right: 1%;"  type="text" >
                    </div>
                    <?php $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,1);
            if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[1] == '1')){?>
                    <div class="col-md-4" style="margin-top: -3.5%;width: 26%;" >

                  <a href="<?php echo site_url('Store/DemandForm/AddForm')?>" ><button type="button"  class="btn btn-default">نیا اندراج</button></a>
                  <a href="" ><button type="button"  class="btn btn-default copyTemp">کاپی</button></a>
                  <a href="<?php echo site_url('')?>" ><button type="button"  class="btn btn-default">مستقل واؤچر</button></a>

                </div>
                    <?php }?>
            </div>
             </div> -->
            <div class="panel-body">
                <div class="table-responsive" style="width: 100%;">
                    <label style="float: left;">تلاش کریں
                        <input type="text" id="myInputTextField" style="float: left;"></label>
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th>فارم نمبر#</th>
                            <th>واپس کنندہ</th>
                            <th>عیسوی تاریخ</th>
                            <th>ہجری تاریخ</th>
                            <th>Status</th>
                            <th style="width: 13%">پرنٹ</th>
                        </tr>
                        </thead>
                        <tbody class="cashbookTable">
                        <?php foreach ($Returned as $value): ?>
                            <tr data-id='<?php echo $value->r_ID?>'>
                                <td><?php echo $value->Item_Issue_Form_Id?></td>
                                <td><?php echo $value->LevelName?></td>
                                <td><?php echo $value->return_dateG?></td>
                                <td><?php echo $value->return_dateH?></td>
                                <td class="Status"><?php if($value->Status == 0){?>
                                        <span class="label label-primary">Pending</span>
                                    <?php }else{?>
                                        <span class="label label-success">Recived</span>
                                    <?php }?>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-success getid" data-toggle="modal" data-target="#gridSystemModal" data-id="<?= $value->r_ID;?>" style="font-size: 10px;background-color: #517751;border-color: #517751; ">Status</button>
                                    <a target="_blank" href="<?= site_url('Store/ItemReturn/ViewVoucher').'/'.$value->r_ID;?>" <button type="button"  data-id="<?php echo $value->r_ID; ?>" class="btn btn-success " style="background-color: #517751;border-color: #517751;"><i class="fa fa-print"></i>
                                    </button></a>
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

<div id="gridSystemModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="gridModalLabel"><?= $Returned[0]->LevelName ?></h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid bd-example-row">
                    <div class="panel-body">
                        <div class="table-responsive" style="width: 100%;">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                <tr>
                                    <th>نمبرشمار#</th>
                                    <th>نام اشیاء</th>
                                    <th>قابل واپسی</th>
                                    <th>کیفیت</th>
                                </tr>
                                </thead>
                                <tbody class="approveTable">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="close" data-dismiss="modal">بند کریں</button>
                <button type="button" class="btn btn-primary update_status">موصول</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var id;
    $('.getid').on('click',function(){
        id = $(this).data('id');
        $.ajax({
            type:'get',
            url:'<?php echo site_url('Store/ItemReturn/RecivedItems');?>'+'/'+id,
            success:function(response){
                $('.approveTable').html(response);
            }
        });
    });

    $('.update_status').on('click',function(){
        var post = new Object();
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url('Store/ItemReturn/StatusUpadte'); ?>'+'/'+id,
            returnType: 'JSON',
            data: post,
            success: function (response) {
                if (response['success']) {
                    new PNotify({
                        title: 'کامیابی',
                        text: 'موصول ہو گیا ہے',
                        type: 'success',
                        hide: true
                    });
                }
                setTimeout(function() {
                    window.location.reload();
                }, 1000);
            }
        });
    });
</script>