<form action="<?= site_url('Qurbani/ChrumQuantity/Save')?>" method="POST">
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
        isset($quantity_data)?$hulqa_name = $quantity_data[0]->hulqa_name:$hulqa_name = '';
        isset($quantity_data)?$hulqa_id = $quantity_data[0]->qur_hulqy_id:$hulqa_id = '';

        isset($quantity_data)?$supervisor_name = $quantity_data[0]->supervisor_name:$supervisor_name = '';

        isset($quantity_data)?$dateG = $quantity_data[0]->dateG:$dateG = '';
        isset($quantity_data)?$dateH = $quantity_data[0]->dateH:$dateH = '';

        isset($quantity_data)?$chrum_type1 = $quantity_data[0]->chrum_type:$chrum_type1 = '';
        isset($quantity_data)?$fresh_quantity1 = $quantity_data[0]->fresh_quantity:$fresh_quantity1 = '';
        isset($quantity_data)?$old_quantity1 = $quantity_data[0]->old_quantity:$old_quantity1 = '';

        isset($quantity_data)?$chrum_type2 = $quantity_data[1]->chrum_type:$chrum_type2 = '';
        isset($quantity_data)?$fresh_quantity2 = $quantity_data[1]->fresh_quantity:$fresh_quantity2 = '';
        isset($quantity_data)?$old_quantity2 = $quantity_data[1]->old_quantity:$old_quantity2 = '';

        isset($quantity_data)?$chrum_type3 = $quantity_data[2]->chrum_type:$chrum_type3 = '';
        isset($quantity_data)?$fresh_quantity3 = $quantity_data[2]->fresh_quantity:$fresh_quantity3 = '';
        isset($quantity_data)?$old_quantity3 = $quantity_data[2]->old_quantity:$old_quantity3 = '';

        isset($quantity_data)?$chrum_type4 = $quantity_data[3]->chrum_type:$chrum_type4 = '';
        isset($quantity_data)?$fresh_quantity4 = $quantity_data[3]->fresh_quantity:$fresh_quantity4 = '';
        isset($quantity_data)?$old_quantity4 = $quantity_data[3]->old_quantity:$old_quantity4 = '';

        isset($quantity_data)?$chrum_type5 = $quantity_data[4]->chrum_type:$chrum_type5 = '';
        isset($quantity_data)?$fresh_quantity5 = $quantity_data[4]->fresh_quantity:$fresh_quantity5 = '';
        isset($quantity_data)?$old_quantity5 = $quantity_data[4]->old_quantity:$old_quantity5 = ''; ?>
        <div class="heading">
            <br>
            <h1 class="page-header" style="margin-top: 10px;">رسید برائے وصولی چرم</h1>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-4">
                    <div class="form-group" style="width:250px;">
                        <label class="control-label" for="inputSuccess">حلقہ</label>
                        <select class="form-control js-example-basic-single" id="hulqa_name" name="qur_hulqy_id" autofocus required>
                            <?php if (isset($quantity_data)){ ?>
                                <option value="<?= $hulqa_id?>" selected><?= $hulqa_name?></option>
                            <?php }?>
                            <option value="0"> منتخب کریں</option>
                            <?php foreach($Hulqy as $item): ?>
                                <option class="fontchange" value="<?= $item->id;?>"><?= $item->hulqa_name ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group" style="width:250px;">
                        <label class="control-label" for="inputSuccess">نگران</label>
                        <select class="form-control js-example-basic-single" id="s_name" name="" autofocus >
                            <?php if (isset($quantity_data)){ ?>
                                <option selected><?= $supervisor_name?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <div class="form-group" style="width:250px;">
                        <label>شمسی تاریخ</label>
                        <div class="form-group">
                            <?php if (isset($quantity_data)){ ?>
                                <input class="form-control englishDate" type="date" id="datepicker" name="dateG" value="<?= $dateG?>" required>
                                <input class="form-control englishDate" type="hidden" name="receipt_no" value="<?= $quantity_data[0]->receipt_no?>">
                            <?php }else{?>
                                <input class="form-control englishDate" type="date" id="datepicker" name="dateG" value="<?= date('Y-m-d'); ?>" required>
                            <?php }?>
                        </div>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">قمری تاریخ</label>
                        <?php if (isset($quantity_data)){ ?>
                            <input class="form-control islamicDate" id="EislamicDate" name="dateH" style="width: 250px;" value="<?= $dateH?>" type="date" readonly required>
                        <?php }else{?>
                            <input class="form-control islamicDate" id="EislamicDate" name="dateH" style="width: 250px;"  type="date" readonly required>
                        <?php }?>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">دن</label>
                        <div class="form-group">
                            <label class="radio-inline"><input type="radio" value="1" name="Receive_Day" required checked>۱۰ ذی الحج</label>
                            <label class="radio-inline"><input type="radio" value="2" name="Receive_Day" required>۱۱  ذی الحج</label>
                            <label class="radio-inline"><input type="radio" value="3" name="Receive_Day" required>۱۲ ذی الحج</label>
                        </div>
                    </div>
                </div>
            </div>
            <?php foreach ($Chrum_Amount as $key => $item) { isset($quantity_data)?$variable = $quantity_data[$key]:$variable = $item;?>
                <div class="row">
                    <div class="form-group">
                        <div class="col-xs-4">
                            <div class="form-group">
                                <?php if ($key == 0){?>
                                    <label class="control-label" for="inputSuccess">چرم کی قسم</label>
                                <?php }?>
                                <input class="form-control" id="" name="" style="width: 250px;" value="<?= $variable->chrum_type?>" type="text" required readonly>
                                <input type="hidden" name="chrum_type[]" value="<?= $variable->id?>">
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="form-group">
                                <?php if ($key == 0){?>
                                    <label class="control-label" for="inputSuccess">تعداد تازہ چرم</label>
                                <?php }?>
                                <input class="form-control" id="cow_latest" name="fresh_quantity[]" style="width: 250px;" value="<?= isset($quantity_data)?$quantity_data[$key]->fresh_quantity:''?>" type="number">
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="form-group">
                                <?php if ($key == 0){?>
                                    <label class="control-label" for="inputSuccess">تعداد باسی چرم</label>
                                <?php }?>
                                <input class="form-control" id="cow_old" name="old_quantity[]" style="width: 250px;" value="<?= isset($quantity_data)?$quantity_data[$key]->old_quantity:''?>" type="number">
                            </div>
                        </div>
                    </div>
                </div>
            <?php }
            if (isset($quantity_data)){ ?>
                <input type="hidden" name="edit" value="<?= $quantity_data[0]->Master_Id?>">
                <input type="hidden" name="CreatedBy" value="<?= $quantity_data[0]->CreatedBy?>">
                <input type="hidden" name="CreatedOn" value="<?= $quantity_data[0]->CreatedOn?>">
            <?php }?>
            <button type="submit" class="btn btn-primary ">محفوظ کریں</button>
        </div>
    </div>
</form>
<script src="<?= base_url()."assets/js/"; ?>jquery-1.12.4.js"></script>
<script type="text/javascript">

    $(function () {
        var date = $('.englishDate').val();
        $.ajax({
            type:"GET",
            url:'<?= site_url('Accounts/Books/getDate');?>'+'/'+date,
            success:function(response){
                var data = $.parseJSON(response);
                $('.islamicDate').val(data.date);
            }
        });
        $('.englishDate').on('change',function(){
            var date = $(this).val();
            $.ajax({
                type:"GET",
                url:'<?= site_url('Accounts/Books/getDate');?>'+'/'+date,
                success:function(response){
                    var data = $.parseJSON(response);
                    $('.islamicDate').val(data.date);
                }
            });
        });

        $('#hulqa_name').change(function () {
            $('#s_name').empty();
            var hulqa_id = $('#hulqa_name').val();
            $.ajax({
                type:'GET',
                url:'<?= site_url('Qurbani/ChrumQuantity/Getsupervisorname');?>/'+hulqa_id,
                success:function (response) {
                    var data = $.parseJSON(response);
                    $('#s_name').append($('<option/>', {
                        value: 0,
                        text : 'منتخب کریں'
                    }).attr('disabled',true));
                    $.each(data, function (index, value) {
                        $('#s_name').append($('<option/>', {
                            value: value['id'],
                            text : value['supervisor_name']
                        }));
                    });
                }
            });
        });
    });
</script>