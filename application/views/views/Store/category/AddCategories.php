<style type="text/css">
    .error{
        color: red;
    }
   /*form  .panel-body{*/
       /*background: #4682b4ad;*/
       /*border-radius: 0px 0px 10px 10px;*/
       /*box-shadow: 0px 1px 27px -3px;*/
    /*}*/
    /*.heading{*/
        /*background: #dbdbdb59;*/
        /*color: #4f6eaf;*/
        /*padding: 0px 0px 0px 0px;*/
        /*border-bottom: 5px solid white;*/
        /*border: 1px solid;*/
        /*border-radius: 10px 10px 0px 0px;*/
        /*text-align: center;*/
        /*border-bottom: 2px solid white;*/
    /*}*/
    /*input[type=text]{*/
        /*width: 250px;*/
        /*padding: 0px;*/
        /*border-color: #81aacc;*/
        /*background-color: #f2f2f2;*/
    /*}*/
    input[name=unit].select2-container--default{
        width: 90% !important;
    }
</style>
<form class="saveCategory" action="<?php echo base_url();?>Store/Category/saveCategory" method="POST" >
    <div class="row">
        <div>
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
        </div>
        <div class="col-xs-4">
            <div class="heading">
                <br><h1 class="page-header" style="margin-top: 10px;">آئٹم کیٹیگری</h1>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess">کیٹیگری کی قسم</label>
                            <select style="width: 200%;" required name="cat-type" id="cat-type">
                                <option disabled value="">منتخب کریں</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess">کیٹیگری کا نام</label>
                            <input class="form-control" autocomplete="off" id="parent_cat" name="Name" style="width: 250px;padding: 0px" value="<?= set_value('Name'); ?>" type="text" placeholder="کیٹیگری کا نام" required>
                            <?= form_error('Name','<div class="error">', '</div>'); ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group" style="display: none">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary ">محفوظ کریں</button>
            </div>
        </div>
    </form>
    <div class="col-xs-4" >
        <form action="<?php echo base_url();?>Store/Category/saveSubCategory" method="POST">
            <div class="heading">
                <br><h1 class="page-header" style="margin-top: 10px;">آئٹم سب کیٹیگری</h1>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-xs-6">
                            <label class="control-label" for="inputSuccess">پیرنٹ کیٹیگری</label><br>
                            <select name="Parent_id" class="form-control parent_category" id="parent_category" style="padding-bottom: 0px;padding-top: 0px; width: 250px;"  required >
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess">سب کیٹیگری کا نام</label>
                                <input class="form-control" autocomplete="off" id="sub_cat" name="SubName" style="width: 250px;padding: 0px"
                                value="<?= set_value('SubName'); ?>" type="text" placeholder="سب کیٹیگری کا نام" required>
                                <?= form_error('SubName','<div class="error">', '</div>'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary ">محفوظ کریں</button>
            </div>
        </form>
    </div>
<!-- item section added here -->
<div class="col-xs-4" style="">
    <form action="<?php echo base_url()?>Store/Items/add_item" method="POST">
        <div class="heading">
            <br><h1 class="page-header" style="margin-top: 10px;">آئٹم</h1>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="form-group">
                    <div class="col-xs-6">
<!--                        <label class="control-label" for="inputSuccess">پیرنٹ کیٹیگری</label><br>-->
<!--                        <select name="Parent_id" class="form-control parent_category" id="parent_category" style="padding-bottom: 0px;padding-top: 0px; width: 250px;"  required >-->
<!--                        </select>-->
                        <label class="control-label" for="inputSuccess">سب کیٹیگری </label><br>
                        <select name="item-parent" class="form-control" id="item-parent" style="padding-bottom: 0px;padding-top: 0px; width: 250px;"  required >
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess">آئٹم کا نام</label>
                            <input class="form-control" autocomplete="off" id="item-name" name="item-name" style="width: 250px;padding: 0px"
                                   value="<?= set_value('SubName'); ?>" type="text" placeholder="سب کیٹیگری کا نام" required>
                            <?= form_error('SubName','<div class="error">', '</div>'); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="form-group">
                    <div class="col-xs-11">
                            <!--                        <label class="control-label" for="inputSuccess">پیمائش کی اکائی</label>-->
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess">پیمائش کی اکائی منتخب کریں</label>
                            <select class="form-control js-example-basic-single" required id="" style="height: 50px;" name="unit">
                                <option value = "" selected disabled>پیمائش کی اکائی منتخب کریں</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary ">محفوظ کریں</button>
        </div>
    </form>
</div>
</div>
<br><br><br>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
            <h1>کیٹیگریز</h1>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                 <label style="float: left; margin-left: 3%;">تلاش کریں
                <input type="text" id="myInputTextField" style="float: left; height: 1%;"></label>
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th style="width: 20%;text-align: center">نمبر شمار</th>
                            <th style="width: 20%;text-align: center">کیٹیگری</th>
                            <th style="width: 20%;text-align: center">سب کیٹیگری</th>
                            <th style="width: 20%;text-align: center">آئٹم</th>
                            <th style="text-align: center"></th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php foreach($allCategories as $key => $Category):?>
                            <tr class="odd gradeX">
                                <td style="text-align: center"><?= $key+1; ?></td>

                                <td style="text-align: center"><?= $Category->Parent_name; ?></td>
                                <td style="text-align: center"><?= $Category->category; ?></td>
                                <td style="text-align: center"><?= $Category->Name; ?></td>
                                <td style="width: 18%;">
                                     <button type="button" class="btn btn-danger delete_category" data-id="<?= $Category->Id;?>" style="font-size: 10px; ">حذف کریں
                                    </button>
                                    <button type="button" class="btn btn-success getid" data-toggle="modal" data-target="#gridSystemModal" data-id="<?= $Category->Id;?>" style="font-size: 10px;background-color: #517751;border-color: #517751; ">تصیح کریں
                                    </button>
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
                <h4 class="modal-title" id="gridModalLabel">تدوین کیٹیگری</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid bd-example-row">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group ">
                                <label class="control-label" for="inputSuccess"> کیٹیگری</label>
                                <input type="text" class="form-control" id="Parent" style="padding: 0px;" readonly>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group ">
                                <label class="control-label" for="inputSuccess">آئٹم</label>
                                <input type="text" class="form-control" id="Category" name="Name">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group ">
<!--                                <label class="control-label" for="inputSuccess">پیرنٹ</label>-->
<!--                                <input type="text" class="form-control" id="Parent" style="padding: 0px;" readonly>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="close" data-dismiss="modal">بند کریں</button>
                <button type="button" class="btn btn-primary update_category">محفوظ کریں</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {

        var cat_type = localStorage.getItem('cat_type');
        if(cat_type == 1)
        {
            $('#1').prop("checked", true);
        }
        else if(cat_type == 0){
            $('#0').prop("checked", true);
        }
        $.ajax({
            type:'get',
            url:'<?php echo base_url()?>Store/Category/bind_cat_ddl/'+cat_type+'',
            success:function(response){
               // $('#parent_category').empty();
                var result = JSON.parse(response);
                var data;
               // console.log(result);
                $.each(result,function (index,value) {
                    //console.log('sufyan');
                    result[index].id = result[index].Id;
                    delete result[index].Id;
                    result[index].text = result[index].Name;
                    delete result[index].Name;
                    var newOption = new Option(result[index].text, result[index].id, false, false);
                    $('.parent_category').append(newOption).trigger('change');
                });
                $('.parent_category').prepend('<option value="" disabled selected>منتخب کریں</option>');
                //console.log(result);
            }
    });
        $.ajax({
            type:'get',
            url:'<?php echo base_url()?>Store/Category/getsubCategories',
            success:function(response){
                // $('#parent_category').empty();
                var result = JSON.parse(response);
                var data;
                // console.log(result);
                $.each(result,function (index,value) {
                    //console.log('sufyan');
                    result[index].id = result[index].Id;
                    delete result[index].Id;
                    result[index].text = result[index].Name;
                    delete result[index].Name;
                    var newOption = new Option(result[index].text, result[index].id, false, false);
                    $('#item-parent').append(newOption).trigger('change');
                });
                $('#item-parent').prepend('<option value="" disabled selected>منتخب کریں</option>');
//                console.log(result);
            }
        });
        $('select').select2();
        $.ajax({
            url:'<?php echo base_url()?>index.php/Users/get_type',
            success:function (response) {
                var result = JSON.parse(response);
                var data;
                // console.log(result);
                $.each(result,function (index,value) {

                    var items = "<option disabled selected> منتخب کریں </option>";
                    var result = JSON.parse(response);
                    $.each(result,function (index) {
                        items += "<option value='"+result[index].id+"'>"+result[index].name+"</option>";
                    });
                    $('#cat-type').html(items);
                });
                 $('#cat-type').select2('destroy');
            }
        });
        $.ajax({
            url:'<?php echo base_url()?>Store/items/get_unit',
            success:function (response) {
                // console.log(response);
                var items = "<option value='' disabled selected>  پیمائش کی اکائی منتخب کریں </option>";
                var result = JSON.parse(response);
                $.each(result,function (index) {
                    items += "<option value='"+result[index].id+"'>"+result[index].name+"</option>";
                });
                $('select[name=unit]').html(items);
//                    $('#items').attr('disabled',false);
            }
        });
});
    window.onload = myOnload;
    function myOnload(evt){
        $('.sub_Category_box').hide();
        $("#dataTables-example_filter input[class='form-control input-sm']").attr("id", "depart_name");
        $('.subCat').on('click',function(){
            $('.sub_Category_box').show();
            $('#parent_cat').attr("disabled",'true');
        });
    }
</script>