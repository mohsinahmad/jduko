<form role ="from" action="<?php echo site_url('Accounts/RamdanReport/GetValue')?>" method="POST" target="_blank" id="UserInput">
    <br><h1 style="text-align: center;">رمضان رپورٹ</h1><br><br>
    <div style="border:1px solid #eee;
            box-shadow:0 0 10px rgba(0, 0, 0, .15);
            max-width:600px;
            margin:auto;
            padding:20px;">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>تاریخ</label>
                    <div class="form-group">
                        <input class="form-control englishDate" type="date" id="datepicker" name="from" value="<?php echo date('Y-m-d'); ?>" placeholder="انگرزیی کی تاریخ منتخب کریں">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>تاریخ تا</label>
                    <div class="form-group">
                        <input class="form-control englishDate" type="date" id="datepicker2" name="to" value="2018-05-17" placeholder="انگرزیی کی تاریخ منتخب کریں">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group" style="float: left">
                    <label>حوالہ نمبر</label>
                    <div class="form-group">
                        <input class="form-control" type="text" name="serial" value="" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <label>تفصیل</label>
                <textarea class="form-control" rows="3" id="details" name="note"></textarea>
            </div>
        </div><br>
        <div class="row">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th style="text-align: center;width:25%;"></th>
                    <th style="text-align: center;">کورنگی</th>
                    <th style="text-align: center;">گلشن</th>
                    <th style="text-align: center;">نانکواڑہ</th>

                </tr>
                </thead>
                <tbody style="text-align: center;">
                <tr>
                    <th style="text-align: center;">گزشتہ سال آج</th>
                    <th style="text-align: center;"><input class="form-control" type="text" id="" name="today0" value=""></th>
                    <th style="text-align: center;"><input class="form-control" type="text" id="" name="today1" value=""></th>
                    <th style="text-align: center;"><input class="form-control" type="text" id="" name="today2" value=""></th>

                </tr>
                <tr>
                    <th style="text-align: center;">گزشتہ سال آج تک</th>
                    <th style="text-align: center;"><input class="form-control" type="text" id="" name="tilltoday0" value=""></th>
                    <th style="text-align: center;"><input class="form-control" type="text" id="" name="tilltoday1" value=""></th>
                    <th style="text-align: center;"><input class="form-control" type="text" id="" name="tilltoday2" value=""></th>
                </tr>
                </tbody>
            </table>
        </div>

        <input type="checkbox" id="multyday" value="1" name="multyday"> ایک سے زائید ایّام کی رپورٹ
        <div class="message2">
            <br><br>
            <span class="message"></span>
        </div>
        <br><br>
        <?php if (isset($_SESSION['comp_id'])){
            $Access_level = $_SESSION['comp_id'];
        }elseif (isset($_SESSION['comp'])){
            $Access_level = $_SESSION['comp'];
        }else{
            $Access_level = '';
        } $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,31,$Access_level);
        if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[5] == '1')){?>
            <input type="submit" name="get" id="get" class="button" data-id="0" value="رپورٹ حاصل کریں">
        <?php }?>
    </div>
</form>
<style type="text/css">
    .button {
        padding: 5px 8px;
        text-align: center;
        font-size: 13px;
    }
</style>
<script src="<?php echo base_url()."assets/"; ?>js/jquery-1.12.4.js"></script>
<script>
    $(document).ready(function () {
        $('#details').val(localStorage.getItem('detail'));
        $('#get').click(function () {
            if($('#details').val() != ''){
                localStorage.setItem('detail',$('#details').val());
           }
        });
    });
    $('.message2').hide();
    $('#multyday').on('click',function(){
        var date = $('.englishDate').val();
        var day = date.substring(8, 10);
        var month = date.substring(5, 7);
        var year = date.substring(0, 8);
        var newDay = parseInt(day)-1;
        if (newDay.toString().length == 1){
            newDay = '0'+newDay;
        }
        var message = 'رپورٹ بتاریخ '+newDay+' اور '+date;

        $('.message2').show();
        $('.message').append(message);
//        /alert(message);
    });
</script>