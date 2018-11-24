<script src="<?= base_url()."assets/"; ?>js/jquery-1.12.4.js"></script>
<div class="row">
    <div class="col-lg-12">
        <br>
        <h1 class="page-header" style="margin-top: 10px;">جاری شدہ اشیاء</h1>
</div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
<!--            <div class="panel-heading col-md-12" style="width: 100%;">-->
<!--                <div class="row">-->
<!--                    <div class="input-group col-md-3" style="float: left; direction: ltr;">-->
<!--                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>-->
<!--                        <input type="text" class="form-control" id="daterange" name="daterange" />-->
<!--                    </div>-->
<!--                    <div class="input-group col-md-3"  style="float: left; direction: ltr; margin-left: 1%;">-->
<!--                        <label class="input-group-addon">Account Code</label>-->
<!--                        <input class="form-control AccountCode" placeholder="تلاش اکاؤنٹ کوڈ" style="width: 100%; margin-right: 2%;"  type="text" >-->
<!--                    </div>-->
<!--                    <input type="hidden" id="to" name="to">-->
<!--                    <input type="hidden" id="from" name="from">-->
<!--                    <button class="btn btn-default search" style="float: left; direction: ltr; margin-left:0%; ">تلاش کریں</button>-->
<!--                   <div class="input-group col-md-2"  style="float: left; direction: ltr;">-->
<!--                        <label class="input-group-addon">CP</label>-->
<!--                        <input class="form-control voucherNo"  placeholder="تلاش واؤچر نمبر " style="width: 100%; margin-right: 1%;"  type="text" >-->
<!--                    </div> -->
<!--                    --><?php //$rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,1);
//                    if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[1] == '1')){?>
<!--                    <div class="col-md-4" style="margin-top: -3.5%;width: 26%;" >-->
<!--                -->
<!--                  <a href="--><?//= site_url('Store/DemandForm/AddForm')?><!--" ><button type="button"  class="btn btn-default">نیا اندراج</button></a> -->
<!--                  <a href="" ><button type="button"  class="btn btn-default copyTemp">کاپی</button></a> -->
<!--                  <a href="--><?//= site_url('Store/ItemReturn/ReturnItem')?><!--<!--" ><button style="margin-top: 15%" type="button"  class="btn btn-default">واپس کردہ اشیاء</button></a>-->
<!--                </div>-->
<!--                    --><?php //}?>
<!--                </div>-->
<!--             </div>-->
            <div class="panel-body">
                <div class="table-responsive" style="width: 100%;">
                <label style="float: left;">تلاش کریں
                <input type="text" id="myInputTextField" style="float: left;"></label>
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th style="text-align: center">فارم نمبر</th>
                            <th style="text-align: center">اجراء کنندہ</th>
                            <th style="text-align: center">عیسوی تاریخ</th>
                            <th style="text-align: center">ہجری تاریخ</th>
                            <th style="text-align: center">کیفیت</th>
                            <th style="width: 13%;text-align: center"></th>
                        </tr>
                        </thead>
                        <tbody class="cashbookTable">
                         <?php foreach ($issued as $value): ?>
                        <tr data-id='<?= $value->Id?>'>
                              <td style="text-align: center"><?= $value->Form_Number?></td>
                              <td style="text-align: center"><?= $value->UserName?></td>
                              <td style="text-align: center"><?= $value->Issued_DateG?></td>
                              <td style="text-align: center"><?= $value->Issued_DateH?></td>
                              <td style="text-align: center"><?php if($value->Status == 0){?>
                                        <span class="label label-primary">Pending</span>
                                    <?php }else{?>
                                        <span class="label label-success">Recived</span>
                                    <?php }?>
                              </td>
                              <td style="text-align: center"><?php if($value->Status == 0){?>
                                      <button type="button" class="btn btn-success ShowTime" data-toggle="modal" data-target="#gridSystemModal" data-id="<?= $value->Id;?>" style="font-size: 10px;background-color: #517751;border-color: #517751; ">Status</button>
                              <?php } else {?>
                              <a href="<?= site_url('Store/ItemReturn/ReturnForm').'/'.$value->Id?>" <button type="button"  data-id="<?= $value->Id; ?>" class="btn btn-success " style="background-color: #517751;border-color: #517751;"><i class="glyphicon glyphicon-share-alt"></i></button></a>
                              <?php }?>
                              <a target="_blank" href="<?= site_url('Store/ItemReturn/View_Voucher').'/'.$value->Id.'/'.$value->Receive_Slip_Id;?>" <button type="button" data-id="<?= $value->Id; ?>" class="btn btn-success" style="background-color: #517751;border-color: #517751;"><i class="fa fa-print"></i></button></a>
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
                <h4 class="modal-title" id="gridModalLabel">اجراء کنندہ: <?= $issued[0]->UserName?></h4>
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
                                    <th>منظور شدہ مقدا</th>
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
                <button type="button" class="btn btn-primary getid" data-id="<?= $value->Id;?>">موصول</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var id;
    $('.ShowTime').on('click',function(){
        var Issue_Form_id = $(this).data('id');
        $.ajax({
            type:'get', //get Issue Item To View only With Quantity b4 receiving
            url:'<?php echo site_url('Store/ItemIssue/GetIssuedItem');?>'+'/'+Issue_Form_id,
            success:function(response){
                $('.approveTable').html(response);
            }
        });
    });

    $('.getid').on('click',function(){
         var id = $(this).data('id');
         var post = new Object();
        $.ajax({
            type:'POST',
            url:'<?= site_url('Store/ItemReturn/StatusUpadteIssue');?>'+'/'+id,
            returnType: 'JSON',
            data: post,
            success:function(response){
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