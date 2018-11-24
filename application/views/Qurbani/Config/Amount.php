<form action="<?= site_url('Qurbani/Config/Share_Amount_Save')?>" method="POST">
    <div class="row">
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
        <?php endif;
        isset($Share[0]->Amount)?$amount = $Share[0]->Amount:$amount='';
        isset($Share[0]->Independent_Expance)?$indp_exp = $Share[0]->Independent_Expance:$indp_exp='';
        isset($Share[0]->Self_Cow_No)?$self = $Share[0]->Self_Cow_No:$self='';
        isset($Share[0]->CreatedBy)?$CreatedBy = $Share[0]->CreatedBy:$CreatedBy='';
        isset($Share[0]->CreatedOn)?$CreatedOn = $Share[0]->CreatedOn:$CreatedOn='';
        isset($Share[0]->Loc_id)?$LocationId = $Share[0]->Loc_id:$LocationId='';
        isset($Share[0]->Name)?$LocationName = $Share[0]->Name:$LocationName='';
        isset($Share[0]->Common_Package)?$Common_Package = $Share[0]->Common_Package:$Common_Package=''; 
        isset($Share[0]->Common_Package_Type)?$Common_Package_Type = $Share[0]->Common_Package_Type:$Common_Package_Type=''; 
        isset($Share[0]->Common_Package_Amount)?$Common_Package_Amount = $Share[0]->Common_Package_Amount:$Common_Package_Amount=''; ?>
        <div class="heading">
            <br>
            <h1 class="page-header" style="margin-top: 10px;">رقوم حصص / خدمت </h1>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="form-group">
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess">قیمت فی حصہ</label>
                            <input class="form-control" type="number" name="Amount" style="width: 250px;" value="<?= $amount?>" autofocus required>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess">خود خرید کردہ جانور کی خدمت کی رقم </label>
                            <input class="form-control" type="number" name="Independent_Expance" style="width: 250px;" value="<?= $indp_exp?>" required>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess">مقام</label>
                            <select class="form-control" name="location" style="padding-top: 0px;padding-bottom: 0px;">
                                <option value="<?= $LocationId?>" selected><?= $LocationName?></option>
                                <?php foreach ($Locations as $location) {?>
                                    <option value="<?= $location->Id?>"><?= $location->Name?></option>
                                <?php }?>
                            </select>
                            <input class="form-control" type="hidden" name="Self_Cow_No" style="width: 250px;" value="0" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">انعامی رقم سب کے لیئے
                        <?php if($Common_Package == 0){?>
                        <input type="radio" name="Common_Package" style="width: 15px;" value="0" autofocus required checked> یکساں ہونگے یا</label>
                        <label class="control-label" for="inputSuccess">
                        <input type="radio" name="Common_Package" style="width: 15px;" value="1" autofocus required>انفرادی</label>
                        <?php }elseif($Common_Package == 1){?>
                        <input type="radio" name="Common_Package" style="width: 15px;" value="0" autofocus required checked> یکساں ہونگے یا</label>
                        <label class="control-label" for="inputSuccess">
                        <input type="radio" name="Common_Package" style="width: 15px;" value="1" autofocus required checked>انفرادی</label>
                        <?php }?>
                    </div>
                </div>
                <div class="col-xs-4 ">
                    <div class="form-group package_type">
                    <?php if($Common_Package_Type == 1){?>
                        <label class="control-label" for="inputSuccess">
                        <input type="radio" name="Common_Package_Type" style="width: 15px;" value="1" autofocus checked> فیصد</label>
                        <label class="control-label" for="inputSuccess">
                        <input type="radio" name="Common_Package_Type" style="width: 15px;" value="0" autofocus>رقم</label>
                        <?php } elseif($Common_Package_Type == 0){?>
                        <label class="control-label" for="inputSuccess">
                        <input type="radio" name="Common_Package_Type" style="width: 15px;" value="1" autofocus> فیصد</label>
                        <label class="control-label" for="inputSuccess">
                        <input type="radio" name="Common_Package_Type" style="width: 15px;" value="0" autofocus checked>رقم</label>
                        <?php }?>
                    </div>
                </div>
                <div class="col-xs-4 ">
                    <div class="form-group package_amount">
                        <label class="control-label" for="inputSuccess">انعامی رقم</label>
                        <input class="form-control" type="text" name="Common_Package_Amount" style="width: 250px;" value="<?=$Common_Package_Amount?>">
                    </div>
                </div>
            </div>
        </div>
        <?php if ($amount != ''){?>
            <input class="form-control" name="edit" value="1" type="hidden">
            <input class="form-control" name="CreatedBy" value="<?= $CreatedBy?>" type="hidden">
            <input class="form-control" name="CreatedOn" value="<?= $CreatedOn?>" type="hidden">
        <?php }?>
        <button type="submit" class="btn btn-primary">محفوظ کریں</button>
    </div>
</form>
<script src="<?php echo base_url()."assets/"; ?>js/jquery-1.12.4.js"></script>
<script type="text/javascript">
    $( document ).ready(function() {
        $('.package_type').hide()
        $('.package_amount').hide()
    });
    $('input[name=Common_Package]').on('click',function(){
        var package = $('input[name=Common_Package]:checked').val();
        if (package == 0) {
            $('.package_type').show();
            $('.package_amount').show()
        }else{
            $('.package_type').hide();
            $('.package_amount').hide()
        }
    });
</script>