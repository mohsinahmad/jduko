<form action="<?= site_url('Qurbani/Config/QurbaniTime_Save')?>" method="POST">
    <div class="row">
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
        <?php endif;
        $Createdby1 = $Createdby2 = $Createdby3 = $CreatedOn1 = $CreatedOn2 = $CreatedOn3 = 0;
        $Star_time1 = $Star_time2 = $Star_time3 = 0; $quantity1 = $quantity2 = $quantity3 = 0;
        $End_time1 = $End_time2 = $End_time3 = 0; $Total_Quantity1 = $Total_Quantity2 = $Total_Quantity3 = 0;

        isset($timings[0]->Start_Time)?$Star_time1 = $timings[0]->Start_Time:$Star_time1='';
        isset($timings[1]->Start_Time)?$Star_time2 = $timings[1]->Start_Time:$Star_time2='';
        isset($timings[2]->Start_Time)?$Star_time3 = $timings[2]->Start_Time:$Star_time3='';

        isset($timings[0]->End_Time)?$End_time1 = $timings[0]->End_Time:$End_time1='';
        isset($timings[1]->End_Time)?$End_time2 = $timings[1]->End_Time:$End_time2='';
        isset($timings[2]->End_Time)?$End_time3 = $timings[2]->End_Time:$End_time3='';

        isset($timings[0]->Per_Hour_Quantity)?$quantity1 = $timings[0]->Per_Hour_Quantity:$quantity1='';
        isset($timings[1]->Per_Hour_Quantity)?$quantity2 = $timings[1]->Per_Hour_Quantity:$quantity2='';
        isset($timings[2]->Per_Hour_Quantity)?$quantity3 = $timings[2]->Per_Hour_Quantity:$quantity3='';

        isset($timings[0]->Per_Day_Quantity)?$Total_Quantity1 = $timings[0]->Per_Day_Quantity:$Total_Quantity1='';
        isset($timings[1]->Per_Day_Quantity)?$Total_Quantity2 = $timings[1]->Per_Day_Quantity:$Total_Quantity2='';
        isset($timings[2]->Per_Day_Quantity)?$Total_Quantity3 = $timings[2]->Per_Day_Quantity:$Total_Quantity3='';

        isset($timings[0]->CreatedBy)?$CreatedBy1 = $timings[0]->CreatedBy:$CreatedBy1='';
        isset($timings[1]->CreatedBy)?$CreatedBy2 = $timings[1]->CreatedBy:$CreatedBy2='';
        isset($timings[2]->CreatedBy)?$CreatedBy3 = $timings[2]->CreatedBy:$CreatedBy3='';

        isset($timings[0]->CreatedOn)?$CreatedOn1 = $timings[0]->CreatedOn:$CreatedOn1='';
        isset($timings[1]->CreatedOn)?$CreatedOn2 = $timings[2]->CreatedOn:$CreatedOn2='';
        isset($timings[2]->CreatedOn)?$CreatedOn3 = $timings[2]->CreatedOn:$CreatedOn3=''; ?>
        <div class="heading">
            <br>
            <h1 class="page-header" style="margin-top: 10px;"> اوقات برائے قربانی </h1>
        </div>
        <div class="panel-body">
            <div class="row">
                <label class="control-label" for="inputSuccess">۱۰ ذی الحج </label>
                <div class="form-group">
                    <div class="col-xs-2">
                        <div class="form-group">
                            <!--                            <label class="control-label" for="inputSuccess">۱۰ ذی الحج </label>-->
                            <label class="control-label" for="inputSuccess">ابتدائی وقت</label>
                            <input class="form-control starttime" id="starttime1" name="starttime[]" style="width: 155px;" value="<?= $Star_time1?>" type="time" autofocus required>
                        </div>
                    </div>
                    <div class="col-xs-2" style="margin-right: 3%;">
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess">انتہائی وقت</label>
                            <input class="form-control endtime" id="endtime1" name="endtime[]" style="width: 155px;" value="<?= $End_time1?>" type="time" required>
                        </div>
                    </div>
                    <div class="col-xs-2" style="margin-right: 3%;">
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess">تعداد فی گھنٹہ</label>
                            <input class="form-control quantity" id="quantity1" name="quantity[]" style="width: 155px;" value="<?= $quantity1?>" type="number" required>
                        </div>
                    </div>
                    <div class="col-xs-2" style="margin-right: 3%;">
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess">تعداد فی یوم</label>
                            <input class="form-control" id="total_quantity1" name="total_quantity[]" style="width: 155px;" value="<?= $Total_Quantity1?>" type="number" readonly>
                        </div>
                    </div>
                    <div class="col-xs-2" style="margin-right: 3%;">
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess">وقت کا فرق</label>
                            <input class="form-control" id="Time_difference" name="Time_difference[]" style="width: 155px;" value="0" type="number">
                        </div>
                    </div>
                    <input class="form-control" name="CreatedBy[]" value="<?= $Createdby1?>" type="hidden">
                    <input class="form-control" name="CreatedOn[]" value="<?= $CreatedOn1?>" type="hidden">
                </div>
            </div>
            <div class="row">
                <label class="control-label" for="inputSuccess">۱۱ ذی الحج </label>
                <div class="form-group">
                    <div class="col-xs-2">
                        <div class="form-group">
                            <input class="form-control starttime" id="starttime2" name="starttime[]" style="width: 155px;" value="<?= $Star_time2?>" type="time" autofocus required>
                        </div>
                    </div>
                    <div class="col-xs-2" style="margin-right: 3%;">
                        <div class="form-group">
                            <input class="form-control endtime" id="endtime2" name="endtime[]" style="width: 155px;" value="<?= $End_time2?>" type="time" required>
                        </div>
                    </div>
                    <div class="col-xs-2" style="margin-right: 3%;">
                        <div class="form-group">
                            <input class="form-control quantity" id="quantity2" name="quantity[]" style="width: 155px;" value="<?= $quantity2?>" type="number" required>
                        </div>
                    </div>
                    <div class="col-xs-2" style="margin-right: 3%;">
                        <div class="form-group">
                            <input class="form-control" id="total_quantity2" name="total_quantity[]" style="width: 155px;" value="<?= $Total_Quantity2?>" type="number" readonly>
                        </div>
                    </div><div class="col-xs-2" style="margin-right: 3%;">
                        <div class="form-group">
                            <input class="form-control" id="Time_difference" name="Time_difference[]" style="width: 155px;" value="0" type="number">
                        </div>
                    </div>
                    <input class="form-control" name="CreatedBy[]" value="<?= $Createdby2?>" type="hidden">
                    <input class="form-control" name="CreatedOn[]" value="<?= $CreatedOn2?>" type="hidden">
                </div>
            </div>
            <div class="row">
                <label class="control-label" for="inputSuccess">۱۲ ذی الحج </label>
                <div class="form-group">
                    <div class="col-xs-2">
                        <div class="form-group">
                            <input class="form-control starttime" id="starttime3" name="starttime[]" style="width: 155px;" value="<?= $Star_time3?>" type="time" autofocus required>
                        </div>
                    </div>
                    <div class="col-xs-2" style="margin-right: 3%;">
                        <div class="form-group">
                            <input class="form-control endtime" id="endtime3" name="endtime[]" style="width: 155px;" value="<?= $End_time3?>" type="time" required>
                        </div>
                    </div>
                    <div class="col-xs-2" style="margin-right: 3%;">
                        <div class="form-group">
                            <input class="form-control quantity" id="quantity3" name="quantity[]" style="width: 155px;" value="<?= $quantity3?>" type="number" required>
                        </div>
                    </div>
                    <div class="col-xs-2" style="margin-right: 3%;">
                        <div class="form-group">
                            <input class="form-control" id="total_quantity3" name="total_quantity[]" style="width: 155px;" value="<?= $Total_Quantity3?>" type="number" readonly>
                        </div>
                    </div>
                    <div class="col-xs-2" style="margin-right: 3%;">
                        <div class="form-group">
                            <input class="form-control" id="Time_difference" name="Time_difference[]" style="width: 155px;" value="0" type="number">
                        </div>
                    </div>
                    <input class="form-control" name="CreatedBy[]" value="<?= $Createdby3?>" type="hidden">
                    <input class="form-control" name="CreatedOn[]" value="<?= $CreatedOn3?>" type="hidden">
                </div>
            </div>
        </div>
    </div>
    <?php if ($Star_time1 != ''){?>
        <input class="form-control" name="edit" value="1" type="hidden">
    <?php }?>
    <button type="submit" class="btn btn-primary ">محفوظ کریں</button>
    </div>
</form>
<script src="<?= base_url()."assets/js/"; ?>jquery-1.12.4.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#quantity1").keyup(function(){
            var quantity1 = $(this).val();
            var start_Time1 = $('#starttime1').val();
            var end_Time1 = $('#endtime1').val();
            var time_Duration1 = ((new Date("2017-8-17 " + end_Time1) - new Date("2017-8-17 " + start_Time1) ) / 1000 / 60 / 60);
            time_Duration1 = parseInt(time_Duration1) + parseInt(1);
            var total_Cow_PerDay1 = quantity1 * time_Duration1;
            $('#total_quantity1').val('');
            $('#total_quantity1').val(total_Cow_PerDay1);
        });
        $("#quantity2").keyup(function(){
            var quantity2 = $(this).val();
            var start_Time2 = $('#starttime2').val();
            var end_Time2 = $('#endtime2').val();
            var time_Duration2 = ( new Date("2017-8-17 " + end_Time2) - new Date("2017-8-17 " + start_Time2) ) / 1000 / 60 / 60;
            time_Duration2 = parseInt(time_Duration2) + parseInt(1);
            var total_Cow_PerDay2 = quantity2 * time_Duration2;
            $('#total_quantity2').val('');
            $('#total_quantity2').val(total_Cow_PerDay2);
        });
        $("#quantity3").keyup(function(){
            var quantity3 = $(this).val();
            var start_Time3 = $('#starttime3').val();
            var end_Time3 = $('#endtime3').val();
            var time_Duration3 = ( new Date("2017-8-17 " + end_Time3) - new Date("2017-8-17 " + start_Time3) ) / 1000 / 60 / 60;
            time_Duration3 = parseInt(time_Duration3) + parseInt(1);
            var total_Cow_PerDay3 = quantity3 * time_Duration3;
            $('#total_quantity3').val('');
            $('#total_quantity3').val(total_Cow_PerDay3);
        });
    });
</script>