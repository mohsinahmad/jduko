<link rel="stylesheet" href="<?= base_url()."assets/css/"; ?>jquery-ui.css">
<script src="<?= base_url()."assets/js/"; ?>jquery-1.12.4.js"></script>
<script src="<?= base_url()."assets/js/"; ?>jquery-ui.js"></script>
<style>
    .select2-container{

        width: 90% !important;
     }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #444;
        line-height: 28px;
        direction: rtl !important;
    }
    .select2-parent_category-container{

        direction: rtl !important;


    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #444;
        line-height: 28px;
        direction: rtl;
    }
    .modal-dialog{
        width: 75%;
    }
    #confrim > div{
        width: 30%;
    }
    #message > div{
        width: 20%;
    }
    canvas{
        margin-top: -70px;
        width: 100px;
        float: left;
    }
</style>
<div class="row">

    <div class="col-lg-12">
<!--        <button type="button" name="btn_save" data-toggle="modal" data-target="#unitModal" class="btn bnt-primary" id="add_unit">پیمائیش کی اکائی داخل کریں</button>-->
    </div>
</div>
<form action="<?= site_url('Store/items/SaveItem');?>" method="post">
    <div class="row">
        <div>
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
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header" style="margin-top: 10px;">آئٹم سیٹ اپ</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-3">
<!--                    <div class="form-group">-->
<!--                        <label class="control-label" for="inputSuccess">آئٹم کا نام</label>-->
<!--                        <select class="form-control" name="items" style="width: 250px;height: 48px; " type="text" id="items">-->
<!--                        </select>-->
<!--<!--                        <span class="add-item" style="color: steelblue;cursor: pointer" data-toggle="modal" data-target="#item-modal">نیا آئٹم<i class="fa fa-plus-square"></i></span>-->
<!--                    </div>-->
                    <div class="form-group" style="width: 250px; align-content: center;">
                        <label class="control-label" for="inputSuccess">کیٹیگری</label>
<!--                        <label class="control-label" for="inputSuccess">تعاوّن کی قسم</label>-->
                        <select class="form-control js-example-basic-single" required id="Category" style="height: 50px;" name="Category">
                     </select>
                    </div>
                </div>
                <div class="col-xs-3">
                    <!--<div class="form-group">
                        <label class="control-label" for="inputSuccess">پیمائش کی اکائی</label>
                        <input class="form-control UnitOfMeasure" id="UnitOfMeasure" name="UnitOfMeasure" style="width: 250px;" type="text" placeholder="پیمائش کی اکائی" required>
                    </div>-->
                    <label class="control-label" for="inputSuccess">سب کیٹیگری </label>
                    <select disabled class="form-control sub-category" required name="sub-category" style="width: 250px;height: 48px;" type="text" id="sub-category">
                        <option disabled selected value="<?php echo set_value('UnitOfMeasure'); ?>"> سب کیٹیگری منتخب کریں</option>
                    </select>
                </div>
                <div class="col-xs-3">
                    <div class="form-group" style="width: 250px; align-content: center;">
                        <label class="control-label" for="inputSuccess">آئٹم</label><br>
                        <select disabled class="form-control js-example-basic-single" required id="items" name="items" style="height: 50px;" name="items">
                            <option value = "" selected disabled> آئٹم منتخب کریں</option>
                        </select>
                    </div>
                </div>
<!--                <div class="col-xs-3">-->
<!--                    <div class="form-group" style="width: 250px; align-content: center;">-->
<!--                        <!--                        <label class="control-label" for="inputSuccess">پیمائش کی اکائی</label>-->
<!--                        <select class="form-control js-example-basic-single" required id="" style="height: 50px;" name="unit">-->
<!--                            <option value = "--><?php //echo set_value('unit'); ?><!--" selected disabled>پیمائش کی اکائی منتخب کریں</option>-->
<!--                        </select>-->
<!--                    </div>-->
<!--                </div>-->
            </div>
            <div class="row checkdonation" id="checkdonation">
                <div class="col-xs-3">
                    <label class="control-label" for="inputSuccess">تعاوّن کی قسم</label>
                    <select name="DonationType[]" class="form-control js-example-basic-single donation" id="DonationType" style="padding-bottom: 0px;padding-top: 0px; width: 250px;"  required >
                        <option value="<?php echo set_value('DonationType[]'); ?>" disabled selected>منتخب کریں</option>
                        <?php foreach ($donations as $donation){?>
                            <option value="<?= $donation->Id?>">
                                <?= $donation->Donation_Type; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-xs-3">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">ابتدائ مقدار</label>
                        <input min="0"  class="form-control newOpeningQuanity" value="<?php echo set_value('OpeningQuanity[]'); ?>" id="OpeningQuanity" name="OpeningQuanity[]" type="number" step="any" placeholder="ابتدائ مقدار" required>
                    </div>
                </div>
                <div class="col-xs-3">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">موجود مقدار</label>
                        <input  class="form-control newCurrentQuanity" value="<?php echo set_value('CurrentQuanity[]'); ?>" id="CurrentQuanity" name="CurrentQuanity[]" type="number" step="any" placeholder="موجود مقدار" readonly>
                    </div>
                </div>
                <div class="col-xs-3">
                    <div class="form-group">
                        <button type="button" class="btn btn-info btn-circle del"><i class="fa fa-trash-o"></i></button>
                        <button type="button" class="btn btn-info btn-circle edit" id="toEdit" ><i class="fa fa-plus"></i></button>
                    </div>
                </div>
            </div>
            <div class="row" style="padding: 10px;">
                <button type="submit" id="add" class="btn btn-primary ">محفوظ کریں</button>
            </div>
            <input type="hidden" name="itemcode" id="code">
        </div>
    </div>
</form>
<div class="modal fade" id="unitModal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">پیمائش کی اکائی کا اندراج</h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <input min="0"  class="form-control" id="unit_name"  style="width: 250px;"  type="text" step="any" placeholder="پیمائش کی اکائی" required>
                        <span id="error" class="text-danger"></span>
                    </div>
                    <div class="row" style="padding: 10px;">
                        <button type="button" id="save" class="btn btn-primary ">محفوظ کریں</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="item-modal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">آئٹم کی تفصیل</h4>
            </div>
            <div class="modal-body">
                <form action="<?php echo site_url('Store/items/updateItems');?>" method="post">
                    <input type="hidden" value="<?php echo $this->uri->segment(4);?>" name="id">
                    <div class="row">
                        <table class="table table-bordered table-hover" id="item-edit">
                            <thead>
                            <tr>
                                <th style="width: 20%;text-align: center"> آئٹم کا نام</th>
                                <th style="width: 15%;text-align: center"> پیمائش کی اکائی</th>
                                <th style="width: 15%;text-align: center">تعاوّن کی قسم </th>
                                <th style="width: 15%;text-align: center"> ابتدائ مقدار </th>
                                <th style="width: 15%;text-align: center"> موجودہ مقدار </th>
                                <th style="text-align: center"></th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
<!--                <button type="button" class="btn btn-default" data-dismiss="modal">بند کریں</button>-->
            </div>
        </div>
    </div>
</div>
<!--editing model of item setup-->
<div class="modal fade" id="edit-item-modal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">آئٹم کی تصیح</h4>
            </div>
            <div class="modal-body">
                <form action="<?= site_url('Store/items/SaveItem');?>" method="post">
                    <div class="row">
                        <div>
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
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h1 class="page-header" style="margin-top: 10px;">آئٹم سیٹ اپ</h1>
                                </div>
                            </div>
                            <div class="row">
                                     <div class="col-xs-3">
                                    <div class="form-group" style="width: 250px; align-content: center;">
                                        <select class="form-control js-example-basic-single" required id="edit-Category" style="height: 50px;" name="Category">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-3">
                                    <select disabled class="form-control sub-category" required name="sub-category" style="width: 250px;height: 48px;" type="text" id="edit-sub-category">
                                        <option disabled selected value="<?php echo set_value('UnitOfMeasure'); ?>"> سب کیٹیگری منتخب کریں</option>
                                    </select>
                                </div>
                                <div class="col-xs-3">
                                    <div class="form-group" style="width: 250px; align-content: center;">
                                        <!--                        <label class="control-label" for="inputSuccess">آئٹم</label>-->
                                        <select disabled class="form-control js-example-basic-single" required id="edit-items" name="items" style="height: 50px;" name="items">
                                            <option value = "" selected disabled> آئٹم منتخب کریں</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row checkdonation1" id="checkdonation1">
                                <div class="col-xs-3">
                                    <label class="control-label" for="inputSuccess">تعاوّن کی قسم</label>
                                    <select name="DonationType[]" class="form-control js-example-basic-single" id="edit-DonationType" style="padding-bottom: 0px;padding-top: 0px; width: 250px;"  required >
                                        <option value="<?php echo set_value('DonationType[]'); ?>" disabled selected>منتخب کریں</option>
                                        <?php foreach ($donations as $donation){?>
                                            <option value="<?= $donation->Id?>">
                                                <?= $donation->Donation_Type; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label class="control-label" for="inputSuccess">ابتدائ مقدار</label>
                                        <input min="0"  class="form-control newOpeningQuanity" value="<?php echo set_value('OpeningQuanity[]'); ?>" id="edit-OpeningQuanity" name="OpeningQuanity[]" type="number" step="any" placeholder="ابتدائ مقدار" required>
                                    </div>
                                </div>
                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label class="control-label" for="inputSuccess">موجود مقدار</label>
                                        <input  class="form-control newCurrentQuanity" value="<?php echo set_value('CurrentQuanity[]'); ?>" id="edit-CurrentQuanity" name="CurrentQuanity[]" type="number" step="any" placeholder="موجود مقدار" readonly>
                                    </div>
                                </div>
                                <div class="col-xs-3">
                                    <div class="form-group">
<!--                                        <button type="button" class="btn btn-info btn-circle del"><i class="fa fa-trash-o"></i></button>-->
<!--                                        <button type="button" class="btn btn-info btn-circle edit" id="toEdit" ><i class="fa fa-plus"></i></button>-->
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="padding: 10px;">
                                <button type="button" id="btn-edit" class="btn btn-primary ">محفوظ کریں</button>
                            </div>
                            <input type="hidden" name="itemcode" id="code">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <!--                <button type="button" class="btn btn-default" data-dismiss="modal">بند کریں</button>-->
           <input type="hidden" id="item-id">
            </div>
        </div>
    </div>
</div>


<!--Message POPUP-->


<div class="modal fade" id="confrim" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <!--            <button type="button" class="close" data-dismiss="modal">&times;</button>-->
                <!--            <h4 class="modal-title">Modal Header</h4>-->
            </div>
            <div class="modal-body">
                <p class="text-danger">کیا آپ واقع حذف کرنا چاہتے ہیں؟</p>
                <input type="hidden" id="delete-id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-danger" id="delete">ہاں</button>
                <button type="button" class="btn btn-default btn-info" data-dismiss="modal">نہیں</button>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="message" role="dialog">
<div class="modal-dialog">
 <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
<!--            <button type="button" class="close" data-dismiss="modal">&times;</button>-->
<!--            <h4 class="modal-title">Modal Header</h4>-->
        </div>
        <div class="modal-body">
            <p class="text-success" style="font-size: 20px" id="msg-p"></p>
        </div>
        <canvas height="200"></canvas>
        <div class="modal-footer">
<!--            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
        </div>
    </div>

</div>
</div>
<?php $this->load->view('Store/item_setup/itemsetup_table',$items)?>
<script>
    $(document).ready(function () {
        $('#add_unit').click(function () {
            $('#unit_name').val("");
            $('#error').html("");
        });
        $('#save').click(function () {
            if ($('#unit_name').val() == "") {
                $('#error').html('نام ڈالنا لازم ہے');
            }
            else{
                $.ajax({
                    url: '<?php echo base_url()?>Store/Items/add_unit',
                    type: 'post',
                    data: {p_name: $('#unit_name').val()},
                    success: function (response) {
                        $('#error').css('color','green');
                        $('#error').html('پیمائش شامل ہو گئی');
                        setTimeout(function(){
                            $('#unitModal').modal('hide')
                        }, 2000);
                        location.reload();
                    },
                    error: function () {
                        $('#error').html('پیمائش پہلے سے موجود ہے');
                    }
                });
            }
        });
        $('#item-save').click(function () {
            if ($('#item-name').val() == "") {
                $('#item-error').html('نام ڈالنا لازم ہے');
            }
            else{
                $.ajax({
                    url: '<?php echo base_url()?>Store/Items/add_item',
                    type: 'post',
                    data: {i_name: $('#item-name').val()},
                    success: function (response) {
                        $('#item-error').css('color','green');
                        $('#item-error').html('آئم شامل ہو گیا');
                        setTimeout(function(){
                            $('#unitModal').modal('hide')
                        }, 2000);
                        if(response == 'duplicate'){
                            $('#item-error').css('color','maroon');
                            $('#item-error').html('آئٹم پہلے سے موجود ہے');
                        }
                        else {
                            location.reload();
                        }
                    },
                    error: function () {
                    }
                });
            }
        });
        $.ajax({
            url:'<?php echo base_url()?>Store/items/get_all_items',
            success:function (response) {
                var items = "<option disabled selected> منتخب کریں </option>";
                var result = JSON.parse(response);
                $.each(result,function (index) {
                  items += "<option value='"+result[index].id+"'>"+result[index].name+"</option>";
                });
                $('#items').html(items);
                $('#items').select2();
                $('#UnitOfMeasure').select2();
            }
        });
            $('#items').change(function () {
                $.ajax({
                    url:'<?php echo base_url()?>Store/items/get_code_items',
                    type:'post',
                    data: {id: $('#items').val()},
                    success: function (response) {
                        var result = JSON.parse(response);
                        //alert(result[0].code);
                        $('#code').val(result[0].code);
                    }
                });
            });
            var category;
        $.ajax({
            url:'<?php echo base_url()?>Store/items/get_parent_category',
            success:function (response) {

                 category = "<option value='' disabled selected> کیٹیگری منتخب کریں </option>";
                var result = JSON.parse(response);
                $.each(result,function (index) {
                    category += "<option value='"+result[index].Id+"'>"+result[index].Name+"</option>";
                });
                $('#Category,#edit-Category').html(category);
            }
        });
        var sub_category;
        $('#Category,#edit-Category').change(function () {
            var cat_id = $(this).val();
            $.ajax({
                url:'<?php echo base_url()?>Store/items/get_sub_category/'+cat_id,
                success:function (response) {
                    console.log(response);
                    sub_category = "<option value='' disabled selected>  سب کیٹیگری منتخب کریں </option>";
                    var result = JSON.parse(response);
                    $.each(result,function (index) {
                        sub_category += "<option value='"+result[index].Id+"'>"+result[index].Name+"</option>";
                    });
                    $('#sub-category,#edit-sub-category').html(sub_category);
                    $('#sub-category,#edit-sub-category').attr('disabled',false);
                }
            });
        });
        var items;
        $('#sub-category,#edit-sub-category').change(function () {
            var item_id = $(this).val();
            $.ajax({
                url:'<?php echo base_url()?>Store/items/get_items/'+item_id,
                success:function (response) {
                    console.log(response);
                    items = "<option value='' disabled selected>  آئٹم منتخب کریں </option>";
                    var result = JSON.parse(response);
                    $.each(result,function (index) {
                        items += "<option value='"+result[index].Id+"'>"+result[index].name+"</option>";
                    });
                    $('#items,#edit-items').html(items);
                    $('#items,#edit-items').attr('disabled',false);
                }
            });
        });
            $.ajax({
                url:'<?php echo base_url()?>Store/items/get_unit',
                success:function (response) {
                    var items = "<option disabled selected>  پیمائش کی اکائی </option>";
                    var result = JSON.parse(response);
                    $.each(result,function (index) {
                        items += "<option value='"+result[index].id+"'>"+result[index].name+"</option>";
                    });
                    $('select[name=unit]').html(items);
                }
            });
        $.ajax({
            url:'<?php echo base_url()?>Store/items/get_donation',
            success:function (response) {
                var donation = "";
                var result = JSON.parse(response);
                $.each(result,function (index) {
                    donation += "<option value='"+result[index].Id+"'>"+result[index].Donation_Type+"</option>";
                });
                $('#edit-DonationType').html(donation);
              }
        });
        $('select').select2();
        $('#item-modal').on('click','.edit-item',function () {
            var id = $(this).data('id');
            $.ajax({
                url:'<?php echo base_url()?>Store/items/get_edit_items/'+id,
                success:function (response) {
                    var result = JSON.parse(response);
                    if($(category+':contains("selected")')){
                        category =   category.replace('selected', "");
                    }
                    var  edit_category = "<option selected value='"+result[0].parent_id+"'>"+result[0].parent_name+"</option>";
                    var  edit_sub_category = "<option selected value='"+result[0].sub_parent_id+"'>"+result[0].sub_parent_name+"</option>";
                    var  edit_items = "<option selected value='"+result[0].item_id+"'>"+result[0].item_name+"</option>";
                    var donation = "<option selected value='"+result[0].donation_id+"'>"+result[0].Donation_Type+"</option>";
                    edit_category += category;
                    edit_items += items;
                    $('#edit-Category').html(edit_category);
                    $('#edit-sub-category').html(edit_sub_category);
                    $('#edit-items').html(edit_items);
                    $('#edit-DonationType').prepend(donation);
                    $('#edit-OpeningQuanity').val(result[0].opening_quantity);
                    $('#edit-CurrentQuanity').val(result[0].current_quantity);
                    $('#item-id').val(result[0].item_detail_id);
                    $('#edit-item-modal').modal('show');
                }
            });
        });
        $('#btn-edit').click(function () {
            var post = new Object();
            post.category = $('#edit-Category').val();
            post.sub_category = $('#edit-sub-category').val();
            post.item = $('#edit-items').val();
            post.donation = $('#edit-DonationType').val();
            post.opening_quantity = $('#edit-OpeningQuanity').val();
            post.current_quantity = $('#edit-CurrentQuanity').val();
            post.item_id = $('#item-id').val();
            $.ajax({
                url:'<?php echo base_url()?>Store/items/updateItems',
                type:'post',
                data:post,
                success:function (response) {
                    if(response == 'succes'){
                        $('#msg-p').text('تدوین کامیاب');
                        $('#message').modal('show');
                        animate_dign();
                        setInterval(function() {
                            location.reload();
                        },2000);
                    }
                }
            });
        });
        $('#item-modal').on('click','.delete-item',function () {
            var id = $(this).data('id');
            $('#confrim').modal('show');
            $('#delete-id').val(id);

        });
        $('#delete').click(function () {
            var id = $('#delete-id').val();
                        $.ajax({
                url:'<?php echo base_url()?>Store/items/DeleteItem/'+id,
                success:function (response) {
                    if(response == 'succes'){
                        $('#msg-p').text('حذف کامیاب');
                        $('#message').modal('show');
                        animate_dign();
                        setInterval(function() {
                            location.reload();
                        },2000);
                    }
                }
            });
        });

    });
    function animate_dign(){
//      debugger;
        var start = 100;
        var mid = 145;
        var end = 250;
        var width = 12;
        var leftX = start;
        var leftY = start;
        var rightX = mid - (width / 2.7);
        var rightY = mid + (width / 2.7);
        var animationSpeed = 10;
        var ctx = document.getElementsByTagName('canvas')[0].getContext('2d');
        ctx.lineWidth = width;
        ctx.strokeStyle = 'rgba(0, 150, 0, 1)';

        for (i = start; i < mid; i++) {
            var drawLeft = window.setTimeout(function () {
                ctx.beginPath();
                ctx.moveTo(start, start);
                ctx.lineTo(leftX, leftY);
                ctx.stroke();
                leftX++;
                leftY++;
            }, 1 + (i * animationSpeed) / 3);
        }
        for (i = mid; i < end; i++) {
            var drawRight = window.setTimeout(function () {
                ctx.beginPath();
                ctx.moveTo(leftX, leftY);
                ctx.lineTo(rightX, rightY);
                ctx.stroke();
                rightX++;
                rightY--;
            }, 1 + (i * animationSpeed) / 3);
        }
    }

    </script>