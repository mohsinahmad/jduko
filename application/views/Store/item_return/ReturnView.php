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
                                   <?php if($value->Status == 0){?>
                                    <button type="button" class="btn btn-success getid" data-toggle="modal" data-target="#gridSystemModal" data-id="<?= $value->r_ID;?>" style="font-size: 10px;background-color: #517751;border-color: #517751; ">Status</button>
                                   <?php }?>
                                    <?php $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,36);
                                    if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[4] == '1') ){?>
                                        <a target="_blank" href="<?= site_url('Store/ItemReturn/ViewVoucher').'/'.$value->r_ID;?>" <button type="button"  data-id="<?php echo $value->r_ID; ?>" class="btn btn-success " style="background-color: #517751;border-color: #517751;"><i class="fa fa-print"></i>
                                        </button></a>
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

<div id="gridSystemModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width:80%">
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
                                    <th>تعاون کی قسم</th>
                                    <th>واپس کردہ لاڈ</th>
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
        $.ajax({
            url:'<?php echo base_url()?>Store/items/get_donation',
            success:function (response) {
                var donation = "<option value='' disabled selected required>منتخب کریں</option>";
                var result = JSON.parse(response);
                $.each(result,function (index) {
                    donation += "<option value='"+result[index].Id+"'>"+result[index].Donation_Type+"</option>";
                });
                $('.donation').html(donation);
            }
        });
    });

//    $('.approveTable').load(function () {
//        alert();
//    });
//

//    $('.donation').on('change',function () {
//        alert();
//    });
    $('#gridSystemModal').on('click','.approveTable tr',function (ref) {
        var donation = $(this).children('td').eq(3).find('.donation');
        var detail = $(this).children('td').eq(4).find('.detail');
        var item = $(this).children('td').eq(6).html();
        var post = new Object();
        $(donation).change(function () {
            post.donation = $(this).val();
            post.item_id = item;
//            console.log(post);
            $.ajax({
                url:'<?php echo base_url()?>Store/ItemReturn/get_details_return',
                data:post,
                type:'post',
               success:function (response) {
                   var result = JSON.parse(response);
                   var details = '<option value="" required selected ></option>';
                   $.each(result,function (index) {
                       details += "<option value='"+result[index].detail_id+"'>"+result[index].NameU+'/'+result[index].Buyer_name+'/'+result[index].Item_recieve_dateG+'/'+result[index].quantity+"</option>";
                   });
                   $(detail).html(details);
                   $(detail).show();
                }
            });
        });
     });
    var post = new Object();
    post.donation = new Array();
    post.quantity = new Array();
    post.item_id = new Array();
    post.detail_id = new Array();
    $('.update_status').on('click',function(){
        $('.approveTable tr').each(function () {
            var donation = $(this).children('td').eq(3).find('.donation').val();
            var quantity = $(this).children('td').eq(2).html();
            var items = $(this).children('td').eq(6).html();
            var detail_id = $(this).children('td').eq(4).find('.detail').val();
            //alert(detail_id);
            post.donation.push(donation);
            post.quantity.push(quantity);
            post.item_id.push(items);
            post.detail_id.push(detail_id);
        });
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