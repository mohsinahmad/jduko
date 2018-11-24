<link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>css/jquery-ui.css">
<script src="<?php echo base_url()."assets/"; ?>js/jquery-1.12.4.js"></script>
<script src="<?php echo base_url()."assets/"; ?>js/jquery-ui.js"></script>
<style>
    div.dataTables_info{
        display: none;
    }
    div.dataTables_paginate{
        display: none;
    }div.dataTables_length label{
         display: none;
     }
</style>
<form action="<?php echo site_url('Store/DemandForm/FormUpdate/');?>" method="post">
    <div class="row">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header" style="margin-top: 10px;">  ڈیمانڈ فارم </h1>

                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group" style="width:250px;">
                        <label>عیسوی تاریخ</label>
                        <div class="form-group">
                            <input class="form-control englishDate" type="text" id="datepicker" name="Form_DateG" value="<?php echo date('Y-m-d'); ?>" placeholder="انگرزیی کی تاریخ منتخب کریں">
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">ہجری تاریخ</label>
                        <input class="form-control islamicDate DemandFormIslamicDate" id="islamicDate" name="Form_DateH" style="width: 250px;"   type="text" readonly>
                    </div>
                </div>
                <?php if (isset($_SESSION['comp_id'])){
                    $comp_id = $_SESSION['comp_id'];
                }elseif (isset($_SESSION['comp'])){
                    $comp_id = $_SESSION['comp'];
                }else{
                    $comp_id = '';
                } $comp_name = $this->BookModel->get_company_name($comp_id); $row = 0;?>
                <input type="hidden" name="level_id" value="<?php echo $comp_id; ?>">
                <input type="hidden" name="Form_Number" value="<?= $form_data[0]->Form_Number?>">
                <input type="hidden" name="CreatedOn" value="<?= $form_data[0]->CreatedOn?>">
                <input type="hidden" name="CreatedBy" value="<?= $form_data[0]->CreatedBy?>">
            </div>
            <div class="row" style="margin-top: 10px; margin-bottom: 10px;">
                <div class="col-xs-6">
                    <div class="form-group" style="width:250px;">
                        <label>شعبہ</label>
                        <div class="form-group">
                            <input class="form-control Department" type="text" name="" value="<?php echo $comp_name[0]->LevelName; ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6" id="Markaz">
                    <div class="form-group" style="width:250px;">
                        <label>شعبہ</label>
                        <select class="form-control" style="height: 50px;" id="departId" name="Department" autofocus>
                            <option value ="<?= $form_data[0]->Department_Id ?>" selected="selected" disabled=""><?php echo $form_data[0]->DepartmentName ?></option>
                            <?php foreach($departments as $department): ?>
                                <option value="<?php echo $department->Id;?>"><?php echo $department->DepartmentName; ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                  <!--  <div class="form-group" style="width:250px;">
                        <label>ڈونیشن ٹائپ</label>
                        <select class="form-control Donation_Type" style="height: 50px;" name="Donation_type" autofocus>

                            <?php //foreach($donation_types as $key => $types): ?>
                                <option value="<?php //echo $types->Id;?>" <?php //if($donation_types[$key]->Id == $form_data[0]->Donation_Type_Id) echo 'selected="selected"'; ?> ><?php //echo $types->Donation_Type; ?></option>
                            <?php //endforeach ?>
                        </select>
                    </div>-->
                </div>
            </div>
            <div class="row" style="padding: 10px;">
                <button type="submit"  class="btn btn-primary data-save">محفوظ کریں</button>
                <button type="button" class="btn btn-primary  addAcc"><i class="fa fa-plus"></i>اشیاء شامل کریں</button>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body" style="padding: 0px;">
                <div class="container-fluid" >
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="data-table">
                            <thead>
                            <tr>
                                <th style="width: 150px;">نام اشیاء</th>
                                <th style="width: 150px;">مقدار</th>
                                <th style="width: 150px;">تفصیل</th>
                                <th style="width: 60px;"></th>
                            </tr>
                            </thead>
                            <tbody class="toEdit">
                            <?php foreach ($form_data as $items){ ?>
                                <tr>
                                    <th><input class="form-control ItemName" name="ItemName[]" type="text" readonly value="<?= $items->name?>"></th>
                                    <th style="display: none"><input type="hidden" class="ItemId" name="Item_Id[]" value="<?= $items->Item_Id?>"></th>
                                    <th><input class="form-control Quantity" name="Item_Quantity[]"  type="text" readonly  value="<?= $items->Item_Quantity?>"></th>
                                    <th><textarea class="form-control Details"   rows="1" name="Detail[]"  readonly ><?= $items->Item_remarks?></textarea></th>
                                    <th><button type="button" class="btn btn-info btn-circle edit" id="toEdit<?= $row?>" ><i class="fa fa-pencil-square-o"></i></button><button type="button" class="btn btn-info btn-circle del" ><i class="fa fa-minus"></i></button></th>
                                </tr>
                            <?php $row++; }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<div id="gridSystemModal" class="modal fade" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="gridModalLabel">مطلوب اشیاء</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid bd-example-row">
                    <div class="row">
                        <div class="col-xs-4">
                            <!--                    <div class="form-group">-->
                            <!--                        <label class="control-label" for="inputSuccess">آئٹم کا نام</label>-->
                            <!--                        <select class="form-control" name="items" style="width: 250px;height: 48px; " type="text" id="items">-->
                            <!--                        </select>-->
                            <!--<!--                        <span class="add-item" style="color: steelblue;cursor: pointer" data-toggle="modal" data-target="#item-modal">نیا آئٹم<i class="fa fa-plus-square"></i></span>-->
                            <!--                    </div>-->
                            <div class="form-group" style="align-content: center;">
                                <label class="control-label" for="inputSuccess">کیٹیگری</label>
                                <!--                        <label class="control-label" for="inputSuccess">تعاوّن کی قسم</label>-->
                                <select class="form-control js-example-basic-single" required id="Category" style="height: 50px;" name="Category">
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <!--<div class="form-group">
                                <label class="control-label" for="inputSuccess">پیمائش کی اکائی</label>
                                <input class="form-control UnitOfMeasure" id="UnitOfMeasure" name="UnitOfMeasure" style="width: 250px;" type="text" placeholder="پیمائش کی اکائی" required>
                            </div>-->
                            <label class="control-label" for="inputSuccess">سب کیٹیگری </label>
                            <select disabled class="form-control sub-category" name="sub-category" id="sub-category">
                                <option disabled selected value="<?php echo set_value('UnitOfMeasure'); ?>"> سب کیٹیگری منتخب کریں</option>
                            </select>
                        </div>

                        <div class="col-xs-4">
                            <div class="form-group" style="align-content: center;">
                                <label class="control-label" for="inputSuccess">آئٹم</label><br>
                                <select disabled class="form-control js-example-basic-single" required id="ItemName" name="items" style="height: 50px;" name="items">
                                    <option value = "" selected disabled> آئٹم منتخب کریں</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess" >پیمائش کی اکائی</label>
                                <input class="form-control" readonly id="unit" type="text" style="width: 100%">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess" >مقدار</label>
                                <input class="form-control" min="1" id="Quantity" type="number" style="width: 100%">
                                <!--Sufyan work-->   <span class="text-danger quantity_error"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess" >تفصیل</label>
                            <textarea class="form-control" rows="3" id="Details"> تفصیل  نہیں ہے</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="dataInsert" class="btn btn-primary dataInsert">محفوظ کریں</button>
                <button type="button" id="dataEdit" class="btn btn-primary dataEdit">محفوظ کریں</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function (){
   $('#ItemName').change(function(){
       var id = $(this).val();
      //alert(id);
       $.ajax({
           url:'<?php echo base_url()?>index.php/Store/DemandForm/get_unit_by_item/'+id,
           success:function(response){

            //alert(response);
               $('#unit').val(response);
           }
       });
   });
        $('select').select2();
        $.ajax({
            url:'<?php echo base_url()?>Store/items/get_parent_category',
            success:function (response) {

                category = "<option disabled selected> کیٹیگری منتخب کریں </option>";
                var result = JSON.parse(response);
                $.each(result,function (index) {
                    category += "<option value='"+result[index].Id+"'>"+result[index].Name+"</option>";
                });
                $('#Category').html(category);
            }
        });
        var sub_category;
        $('#Category').change(function () {
            var cat_id = $(this).val();
            $.ajax({
                url:'<?php echo base_url()?>Store/items/get_sub_category/'+cat_id,
                success:function (response) {
                    console.log(response);
                    sub_category = "<option disabled selected>  سب کیٹیگری منتخب کریں </option>";
                    var result = JSON.parse(response);
                    $.each(result,function (index) {
                        sub_category += "<option value='"+result[index].Id+"'>"+result[index].Name+"</option>";
                    });
                    $('#sub-category').html(sub_category);
                    $('#sub-category').attr('disabled',false);
                }
            });
        });
        var items;
        $('#sub-category').change(function () {
            var item_id = $(this).val();
            $.ajax({
                url:'<?php echo base_url()?>Store/items/get_items/'+item_id,
                success:function (response) {
                    console.log(response);
                    items = "<option disabled selected>  آئٹم منتخب کریں </option>";
                    var result = JSON.parse(response);
                    $.each(result,function (index) {
                        items += "<option value='"+result[index].Id+"'>"+result[index].name+"</option>";
                    });
                    $('#ItemName').html(items);
                    $('#ItemName').attr('disabled',false);
                }
            });
        });
    });
    var cat_type = localStorage.getItem('cat_type');
    if(cat_type == 1)
    {
        $('#1').prop("checked", true);
    }
    else if(cat_type == 0){
        $('#0').prop("checked", true);
    }
    //sufyan work
    $('#Quantity').keyup(function () {
        if($(this).val() == 0){
            $('.quantity_error').html('مقدار کم سے کم ایک ہوناضروری ہے');
        }
        else{
            $('.quantity_error').html('مقدار کا اندراج ٹھیک ہے');
            $('.quantity_error').css('color','green');
        }
    });
    $('.data-save').click(function(e){
        var tr = $('#data-table tbody tr').length;
        if(tr == 0){
            alert('برائے مہر بانی کم سے کم ایک اشیاء شامل کریں');
            e.preventDefault();
        }
    });
    // sufyan work
    $( function() {
        if (<?= $_SESSION['parent_id']?> == 101){
            $('#Markaz').show();
        }else{
            $('#Markaz').hide();
        }
        $(".addAcc").click(function () {
            var c_type = localStorage.getItem('cat_type');
            var shoba = $('#departId').val();
            var atia = $("select[name='Donation_type']").val();
            //debugger;
            if(c_type == ''){
                error = 1;
                new PNotify({
                    title: 'توجہ فرمائیں۔۔!!',
                    text: "قسم منتخب کریں!",
                    type: 'error',
                    delay: 3000,
                    hide: true
                });
                $(this).removeAttr("data-dismiss","modal");
            }
//sufyan work start here
            else if(!shoba){
                error = 1;
                new PNotify({
                    title: 'توجہ فرمائیں۔۔!!',
                    text: "شعبہ منتخب کریں!",
                    type: 'error',
                    delay: 3000,
                    hide: true
                });
                $(this).removeAttr("data-dismiss","modal");
            }
//            else if(!atia){
//                error = 1;
//                new PNotify({
//                    title: 'توجہ فرمائیں۔۔!!',
//                    text: "عطئیے کی اقسام منتخب کریں!",
//                    type: 'error',
//                    delay: 3000,
//                    hide: true
//                });
//                $(this).removeAttr("data-dismiss","modal");
//            }
//end sufyan work here
            else{
                $("#gridSystemModal").modal('show');
                //$('#gridModalLabel').html('مطلوب اشیاء - '+$('.Donation_Type option:selected').html());
                $('#dataInsert').show();
                $('#dataEdit').hide();
            }
        });
        $('.addAcc').on('click',function(){
            var cat_type = localStorage.getItem('cat_type');
            //alert(cat_type);
            $.ajax({
                type:'GET',
                url:'<?php echo site_url('Store/items/GetCategoryViseItem');?>'+'/'+cat_type,
                success:function(result){
                    var data = $.parseJSON(result);
                    console.log(data);
                    $('#ItemName').empty();
                    $('#ItemName').append($('<option/>', {
                        value: 0,
                        text : 'منتخب کریں'
                    }).attr('disabled',true).attr('selected',true));
                    $.each(data, function (index, value) {
                        $('#ItemName').append($('<option/>', {
                            value: value['id'],
                            text : value['name']
                        }));
                    });
                }
            })
        });
        $('#gridSystemModal').on('hidden.bs.modal', function (e) {
            $(this)
                .find("input,textarea")
                .val('')
                .end()
                .find("input[type=number]")
                .prop("disabled", false)
                .end();
            var dsum = 0;
            var csum = 0;
            var credit = 0;
            var debit = 0;
            $('tr').each(function () {
                $(this).find('.debit').each(function () {
                    var debit = $(this).val();
                    if (!isNaN(debit) && debit.length !== 0) {
                        dsum += parseFloat(debit);
                    }
                });
                $('#totald').text(dsum);

                $(this).find('.credit').each(function () {
                    var credit = $(this).val();
                    if (!isNaN(credit) && credit.length !== 0) {
                        csum += parseFloat(credit);
                    }
                });
                $('#totalc').text(csum);
            });
        });
    });
    $('.dataInsert').on('click',function(){
        var post = new Object();
        var error = "";
        var quant = $('#Quantity').val();
        var id = $('#ItemName').val();
        var detail = $('#Details').val();
        if(!quant || quant < 0.25){
            error = 1;
            new PNotify({
                title: 'توجہ فرمائیں۔۔!!',
                text: "مقدار انٹر کریں",
                type: 'error',
                delay: 3000,
                hide: true
            });
            $(this).removeAttr("data-dismiss","modal");
        }
        if(!id){
            error = 1;
            new PNotify({
                title: 'توجہ فرمائیں۔۔!!',
                text: " براہ مہربانی  اشیاء  منتخب کریں",
                type: 'error',
                delay: 3000,
                hide: true
            });
            $(this).removeAttr("data-dismiss","modal");
        }
        if(!detail){
            error = 1;
            new PNotify({
                title: 'توجہ فرمائیں۔۔!!',
                text: " براہ مہربانی  تفصیل کا اندراج کریں",
                type: 'error',
                delay: 3000,
                hide: true
            });
            $(this).removeAttr("data-dismiss","modal");
        }
        if(!error){
            post.name = 'Item';
            post.details = $('#Details').val();
            post.name = $('#ItemName  option:selected').text();
            post.Item_id = $('#ItemName option:selected').val();
            post.Quantity = $('#Quantity').val();
            var rows = $('#data-table tbody tr').length;
            $('tbody').append('<tr class="addnew"><td><input class="form-control ItemName" name="ItemName[]" type="text"  style="width: 100%" readonly value="'+post.name+'"  ></td><td><input class="form-control Quantity" name="Item_Quantity[]"  type="text" readonly style="width: 100%" value='+post.Quantity+' ></td><td class="center"><textarea class="form-control Details"   rows="1" name="Detail[]"  readonly >'+post.details+'</textarea></td><td style="display: none"><input type="hidden" class="ItemId" name="Item_Id[]" value='+post.Item_id+'></td><td class="center"><button type="button" class="btn btn-info btn-circle edit" id="toEdit'+(rows + 1)+'" ><i class="fa fa-plus"></i></button><button type="button" class="btn btn-info btn-circle del" ><i class="fa fa-minus"></i></button></td></tr>');
            $('#gridSystemModal').modal('toggle');
        }
    });
    $( "#data-table" ).on( "click", ".del", function(e) {
        e.preventDefault();
        var tr = $(this).parents('tr');
        $( this ).parents( "tr" ).remove();
    });
    var idToEdit='';
    $( "#data-table" ).on( "click", ".edit", function() {
        idToEdit = $(this).attr('id');
        var ItemId =  $(this).parents('tr').find('.ItemId').val();
        var details = $(this).parents('tr').find('.Details').val();
        var Quantity = $(this).parents('tr').find('.Quantity').val();
        $('#ItemName').val(ItemId).trigger('change');
        $('#Details').val(details);
        $('#Quantity').val(Quantity);
        $('#dataInsert').hide();
        $('#dataEdit').show();
        $("#gridSystemModal").modal('show');
    });
    $('.dataEdit').on('click',function(){
        var post = new Object();
        var error = "";
        var quant = $('#Quantity').val();
        var details = $('textarea#Details').val(); //$('#Details').text();
        var id = $('#ItemName option:selected').val();
        var name = $('#ItemName  option:selected').text();
        if(!quant){
            error = 1;
            new PNotify({
                title: 'توجہ فرمائیں۔۔!!',
                text: "مقدار کا اندراج کریں",
                type: 'error',
                delay: 3000,
                hide: true
            });
            $(this).removeAttr("data-dismiss","modal");
        }
        if(!id){
            error = 1;
            new PNotify({
                title: 'توجہ فرمائیں۔۔!!',
                text: " براہ مہربانی اشیاء کا انتیخاب کریں",
                type: 'error',
                delay: 3000,
                hide: true
            });
            $(this).removeAttr("data-dismiss","modal");
        }
        if(!error){
            $('tbody.toEdit').find('#'+idToEdit).parents('tr').find('.ItemName').val(name);
            $('tbody.toEdit').find('#'+idToEdit).parents('tr').find('textarea.Details').val(details);
            $('tbody.toEdit').find('#'+idToEdit).parents('tr').find('.Quantity').val(quant);
            $('tbody.toEdit').find('#'+idToEdit).parents('tr').find('.ItemId').val(id);

            $('#gridSystemModal').modal('toggle');
        }
    });
    // $('.Donation_Type').on('change',function(){
    //     var donation = $(this).val();
    //     $.ajax({
    //         type:'GET',
    //         url:'<?php echo site_url('Store/items/getDonationViseItems');?>'+'/'+donation,
    //         success:function(result){
    //             var data = $.parseJSON(result);
    //             $('#ItemName').empty();
    //             $('#ItemName').append($('<option/>', {
    //                 value: 0,
    //                 text : 'منتخب کریں'
    //             }).attr('disabled',true).attr('selected',true));
    //             $.each(data, function (index, value) {
    //                 $('#ItemName').append($('<option/>', {
    //                     value: value['Id'],
    //                     text : value['name']
    //                 }));
    //             });
    //         }
    //     })
    // });
</script>