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
<form action="<?php echo site_url('Store/items/updateItems');?>" method="post">
    <input type="hidden" value="<?php echo $this->uri->segment(4);?>" name="id">
    <div class="row">
        <div>
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
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header" style="margin-top: 10px;">  آئٹم سیٹ اپ </h1>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">آئٹم کوڈ</label>
                        <input class="form-control" readonly id="ItemCode" name="ItemCode" style="width: 250px;" value="<?= $itemsToEdit[0]->code?>" type="text" placeholder="0000" maxlength="4" autofocus required>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">پیمائش کی اکائی</label>
<!--                        <input class="form-control UnitOfMeasure" id="UnitOfMeasure" name="UnitOfMeasure" style="width: 250px;" value="--><?php //$itemsToEdit[0]->unit_of_measure?><!--" type="text" placeholder="پیمائش کی اکائی" required>-->
                          <select class="form-control" id="unit_of_measure" style="height: 50px;" name="unit_of_measure" required>
                            <option selected readonly value = "<?= $itemsToEdit[0]->unit_id?>"><?= $itemsToEdit[0]->unit_of_measure_name?></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <div class="form-group" style="width: 250px; align-content: center;">
                        <label class="control-label" for="inputSuccess">کیٹیگری</label>
                        <select class="form-control" id="ItemCategory" style="height: 50px;" name="ItemCategory" required>
                        <option value = "<?php echo $itemsToEdit[0]->p_id?>"> <?php echo $itemsToEdit[0]->p_name?></option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group" style="width: 250px; align-content: center;">
                        <label class="control-label" for="inputSuccess">سب کیٹیگری</label>
                        <select readonly="" class="form-control" required id="ItemsubCategory" style="height: 50px;" name="ItemsubCategory">
                            <option value = "<?= $itemsToEdit[0]->s_id?>"><?= $itemsToEdit[0]->s_name?></option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">آئٹم کا نام</label>
                        <select class="form-control" id="ItemName" name="ItemName" style="width: 250px;" required>
                            <option disabled selected value="<?= $itemsToEdit[0]->a_id?>"><?= $itemsToEdit[0]->item_name?></option>
                        </select>
                    </div>
                </div>
            </div>
            <?php foreach ($itemsToEdit as $item){?>
                <div class="row checkdonation" id="checkdonation">
                    <div class="col-xs-3">
                        <label class="control-label" for="inputSuccess">تعاوّن کی قسم</label>
                        <select name="DonationType[]" class="form-control js-example-basic-single" id="DonationType" style="padding-bottom: 0px;padding-top: 0px; width: 250px;"  required >
                            <option readonly value="<?= $item->d_id?>" selected><?= $item->Donation_Type?></option>
                        </select>
                    </div>
                    <div class="col-xs-3" style="left: -8%">
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess">ابتدائ مقدار</label>
                            <input  class="form-control newOpeningQuanity" id="OpeningQuanity" name="OpeningQuanity[]" style="width: 250px;" value="<?= $item->opening_quantity?>" type="number" step="any" placeholder="ابتدائ مقدار" required>
                        </div>
                    </div>
                    <div class="col-xs-3" style="left: -14%">
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess">موجود مقدار</label>
                            <input  class="form-control newCurrentQuanity" id="CurrentQuanity" name="CurrentQuanity[]" style="width: 250px;" value="<?= $item->current_quantity?>" type="number" step="any" placeholder="موجود مقدار" readonly>
                        </div>
                    </div>
                    <div class="col-xs-3" style="width: 10%;float: left;margin-top: 0%;">
                        <div class="form-group">
                            <button type="button" class="btn btn-info btn-circle del" ><i class="fa fa-trash-o"></i></button>
                            <button type="button" class="btn btn-info btn-circle edit" id="toEdit" ><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                </div>
            <?php }?>
            <div class="row" style="padding: 10px;">
                <button type="submit"  class="btn btn-primary ">محفوظ کریں</button>
            </div>
        </div>
    </div>
</form>
<?php //$this->load->view('Store/item_setup/itemsetup_table',$items);?>
<!--<div class="col-lg-12">-->
<!--    <div class="panel panel-default">-->
<!--        <div class="panel-heading">-->
<!--            <h1>اشیاء</h1>-->
<!--        </div>-->
<!--        <div class="panel-body">-->
<!--            <div class="table-responsive">-->
<!--                <label style="float: left; margin-left: 3%;">تلاش کریں-->
<!--                    <input type="text" id="myInputTextField" style="float: left; height: 1%;"></label>-->
<!--                <table class="table table-striped table-bordered table-hover" id="dataTables-example">-->
<!--                    <thead>-->
<!--                    <tr>-->
<!--                        <th style="width: 15%;">آئٹم کوڈ</th>-->
<!--                        <th style="width: 20%;"> آئٹم کا نام</th>-->
<!--                        <th style="width: 15%;"> پیمائش کی اکائی</th>-->
<!--                        <th style="width: 15%;"> کیٹیگری </th>-->
<!--                        <th style="width: 15%;"> ڈونیشن  کی قسم </th>-->
<!--                        <th style="width: 15%;"> ابتدائ مقدار </th>-->
<!--                        <th style="width: 15%;"> موجودہ مقدار </th>-->
<!--                        <th>Actions</th>-->
<!--                    </tr>-->
<!--                    </thead>-->
<!--                    <tbody>-->
<!--                    --><?php //foreach($items as $item):?>
<!--                        <tr class="odd gradeX">-->
<!--                            <td>--><?php //echo $item->code; ?><!--</td>-->
<!--                            <td>--><?php //echo $item->name; ?><!--</td>-->
<!--                            <td>--><?php //echo $item->unit_of_measure; ?><!--</td>-->
<!--                            <td>--><?php //echo isset($item->s_name) ? $item->p_name.' - '. $item->s_name : $item->p_name; ?><!--</td>-->
<!--                            <td>--><?php //echo $item->Donation_Type; ?><!--</td>-->
<!--                            <td>--><?php //echo $item->opening_quantity; ?><!--</td>-->
<!--                            <td>--><?php //echo $item->current_quantity; ?><!--</td>-->
<!--                            <td style="width: 18%;">-->
<!--                                <div class="btn-group dropup">-->
<!--                                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
<!--                                        Action <span class="caret"></span>-->
<!--                                    </button>-->
<!--                                    <ul class="dropdown-menu">-->
<!--                                        <li><a class="getid" href="#" data-toggle="" data-target="" data-id="--><?php //echo $item->code;?><!--">تصیح کریں</a></li>-->
<!--                                        <li><a class="delete_item" href="" data-id="--><?php //echo $item->a_id;?><!--">حذف کریں</a></li>-->
<!--                                    </ul>-->
<!--                                </div>-->
<!--                            </td>-->
<!--                        </tr>-->
<!--                    --><?php //endforeach?>
<!--                    </tbody>-->
<!--                </table>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->

<!--<div id="gridSystemModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">-->
<!--    <div class="modal-dialog" role="document">-->
<!--        <div class="modal-content">-->
<!--            <div class="modal-header">-->
<!--                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
<!--                <h4 class="modal-title" id="gridModalLabel">تدوین اشیا</h4>-->
<!--            </div>-->
<!--            <div class="modal-body">-->
<!--                <div class="container-fluid bd-example-row">-->
<!--                    <div class="row">-->
<!--                        <div class="col-xs-6">-->
<!--                            <div class="form-group ">-->
<!--                                <label class="control-label" for="inputSuccess">  آئٹم کوڈ</label>-->
<!--                                <input type="text" class="form-control" id="Code" name="Code" required>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <div class="col-xs-6">-->
<!--                            <div class="form-group ">-->
<!--                                <label class="control-label" for="inputSuccess"> آئٹم کا نام</label>-->
<!--                                <input type="text" class="form-control" id="Name" name="Name" required>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="row">-->
<!--                        <div class="col-xs-6">-->
<!--                            <div class="form-group ">-->
<!--                                <label class="control-label" for="inputSuccess">پیمائش کی اکائی</label>-->
<!--                                <input type="text" class="form-control unit_of_measure" required>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <div class="col-xs-6">-->
<!--                            <div class="form-group"  style="width: 250px; align-content: center;">-->
<!--                                <label class="control-label" for="inputSuccess">کیٹیگری</label>-->
<!--                                <select class="form-control ItemCategory" style="height: 50px;"  name="ItemCategory" required>-->
<!--                                    <option value = "0"> منتخب کریں</option>-->
<!--                                    --><?php //foreach($categories as $category):?>
<!--                                        <option value = "--><?php //echo $category->Id?><!--"> --><?php //echo $category->Name?><!--</option>-->
<!--                                    --><?php //endforeach?>
<!--                                </select>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="row">-->
<!--                        <div class="col-xs-6">-->
<!--                            <div class="form-group" style="width: 250px; align-content: center;">-->
<!--                                <label class="control-label" for="inputSuccess">سب کیٹیگری</label>-->
<!--                                <select class="form-control ItemsubCategory" style="height: 50px;" name="ItemsubCategory">-->
<!--                                </select>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="row">-->
<!--                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">-->
<!--                            <thead>-->
<!--                                <tr>-->
<!--                                    <th style="width: 1%;"> ڈونیشن  کی قسم </th>-->
<!--                                    <th style="width: 15%;"> ابتدائ مقدار </th>-->
<!--                                    <th style="width: 15%;"> موجودہ مقدار </th>-->
<!--                                    <th style="width: 6%;"></th>-->
<!--                                </tr>-->
<!--                            </thead>-->
<!--                            <tbody class="donationtypes">-->
<!---->
<!--                            </tbody>-->
<!--                        </table>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="modal-footer">-->
<!--                <button type="button" class="btn btn-secondary" id="close" data-dismiss="modal">بند کریں</button>-->
<!--                <button type="button" class="btn btn-primary update_item">محفوظ کریں</button>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
<script>
   $(document).ready(function () {
       $.ajax({
           url:'<?php echo base_url()?>Store/items/get_parent_category',
           success:function (response) {
               var items = "";
               var result = JSON.parse(response);
               $.each(result,function (index) {
                   items += "<option value='"+result[index].Id+"'>"+result[index].Name+"</option>";
               });
               $('#ItemCategory').append(items);
           }
       });
       $('select').select2();
       $('#ItemCategory').change(function () {
           var cat_id = $(this).val();
           $.ajax({
               url:'<?php echo base_url()?>Store/items/get_sub_category/'+cat_id,
               success:function (response) {
                   console.log(response);
                   var items = "<option disabled selected>  سب کیٹیگری منتخب کریں </option>";
                   var result = JSON.parse(response);
                   $.each(result,function (index) {
                       items += "<option value='"+result[index].Id+"'>"+result[index].Name+"</option>";
                   });
                   $('#ItemsubCategory').html(items);
               }
           });
       });
       $('#ItemsubCategory').change(function () {
           var item_id = $(this).val();
           $.ajax({
               url:'<?php echo base_url()?>Store/items/get_items/'+item_id,
               success:function (response) {
                   console.log(response);
                   var items = "<option disabled selected>  آئٹم منتخب کریں </option>";
                   var result = JSON.parse(response);
                   $.each(result,function (index) {
                       items += "<option value='"+result[index].Id+"'>"+result[index].name+"</option>";
                   });
                   $('#ItemName').html(items);
               }
           });
       });
       $.ajax({
           url:'<?php echo base_url()?>Store/items/get_donation',
           success:function (response) {
               console.log(response);
               var items = "";
               var result = JSON.parse(response);
               $.each(result,function (index) {
                   items += "<option value='"+result[index].Id+"'>"+result[index].Donation_Type+"</option>";
               });
               $('#DonationType').append(items);
           }
       });
       $.ajax({
           url:'<?php echo base_url()?>Store/items/get_unit',
           success:function (response) {
               console.log(response);
               var items = "";
               var result = JSON.parse(response);
               $.each(result,function (index) {
                   items += "<option value='"+result[index].id+"'>"+result[index].name+"</option>";
               });
               $('#unit_of_measure').append(items);
           }
       });

   });
</script>