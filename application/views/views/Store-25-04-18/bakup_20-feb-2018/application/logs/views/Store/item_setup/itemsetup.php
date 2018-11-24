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
<form action="<?php echo site_url('Store/items/SaveItem');?>" method="post">
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
<!--                <div class="col-xs-4">-->
<!--                    <div class="form-group">-->
<!--                        <label class="control-label" for="inputSuccess">آئٹم کوڈ</label>-->
<!--                        <input class="form-control" id="ItemCode" name="ItemCode" style="width: 250px;"  type="text" placeholder="0000" maxlength="4" autofocus required>-->
<!--                    </div>-->
<!--                </div>-->
                <div class="col-xs-4">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">آئٹم کا نام</label>
                        <input  class="form-control" id="ItemName" name="ItemName" style="width: 250px;"  type="text" placeholder="آئٹم کا نام" required>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">پیمائش کی اکائی</label>
                        <input class="form-control UnitOfMeasure" id="UnitOfMeasure" name="UnitOfMeasure" style="width: 250px;" type="text" placeholder="پیمائش کی اکائی" required>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group" style="width: 250px; align-content: center;">
                        <label class="control-label" for="inputSuccess">کیٹیگری</label>
                        <select class="form-control js-example-basic-single" id="" style="height: 50px;" name="ItemsubCategory">
                            <option value = "0" selected disabled> منتخب کریں</option>
                            <?php foreach($categories as $category):?>
                                <option value = "<?php echo $category->Id?>"> <?php echo $category->Name?></option>
                            <?php endforeach?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- <div class="col-xs-4">
                    <div class="form-group" style="width: 250px; align-content: center;">
                        <label class="control-label" for="inputSuccess">کیٹیگری</label>
                        <select class="form-control " id="ItemCategory" style="height: 50px;" name="ItemCategory" required>
                            <option value = "0"> منتخب کریں</option>
                            <?php foreach($categories as $category):?>
                                <option value = "<?php echo $category->Id?>"> <?php echo $category->Name?></option>
                            <?php endforeach?>
                        </select>
                    </div>
                </div> -->

            </div>
            <!--             <div class="row">
                            <div class="col-xs-6">
                                <div class="checkbox">
                                    <input name="quantity_check" type="checkbox" id="quantity_check" value="">مقدار کو برقرار رکھا جائے
                                </div>
                             </div>
                        </div> -->
            <div class="row checkdonation" id="checkdonation">
                <!--                 <div class="col-xs-3">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">Donation کی قسم</label>
                        <input  class="form-control" id="DonationType" name="DonationTypeName" value="<?php echo $donation->Donation_Type;?>" style="width: 250px;" type="text">

                        <input  class="form-control" id="" name="DonationType[]" value="<?php echo $donation->Id;?>" style="width: 250px;" type="hidden">
                    </div>
                </div> -->
                <div class="col-xs-3">
                    <label class="control-label" for="inputSuccess">ڈونیشن کی قسم</label>
                    <select name="DonationType[]" class="form-control js-example-basic-single" id="DonationType" style="padding-bottom: 0px;padding-top: 0px; width: 250px;"  required >
                        <option value="" disabled selected>منتخب کریں</option>
                        <?php foreach ($donations as $donation){?>
                            <option value="<?= $donation->Id?>">
                                <?= $donation->Donation_Type; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-xs-3" style="left: -8%">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">ابتدائ مقدار</label>
                        <input  class="form-control newOpeningQuanity" id="OpeningQuanity" name="OpeningQuanity[]" style="width: 250px;"  type="number" placeholder="ابتدائ مقدار" required>
                    </div>
                </div>
                <div class="col-xs-3" style="left: -14%">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">موجود مقدار</label>
                        <input  class="form-control newCurrentQuanity" id="CurrentQuanity" name="CurrentQuanity[]" style="width: 250px;"  type="number" placeholder="موجود مقدار" readonly>
                    </div>
                </div>
                <div class="col-xs-3" style="width: 10%;float: left;margin-top: 0%;">
                    <div class="form-group">
                        <button type="button" class="btn btn-info btn-circle del" ><i class="fa fa-trash-o"></i></button>
                        <button type="button" class="btn btn-info btn-circle edit" id="toEdit" ><i class="fa fa-plus"></i></button>
                    </div>
                </div>
            </div>
            <div class="row" style="padding: 10px;">
                <button type="submit"  class="btn btn-primary ">محفوظ کریں</button>
            </div>
        </div>
    </div>
</form>
<?php $this->load->view('Store/item_setup/itemsetup_table',$items)?>