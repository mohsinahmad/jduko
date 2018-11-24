<?php if (isset($_SESSION['comp_id'])){
    $Access_level = $_SESSION['comp_id'];
}elseif (isset($_SESSION['comp'])){
    $Access_level = $_SESSION['comp'];
}else{
    $Access_level = '';
} $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,15,$Access_level);
if($_SESSION['user'][0]->id > 1 && $rights != array()){?>
    <input type="hidden" id="AccessRights" value="<?= $rights[0]->Rights[4]?>">
<?php }?>
<form role ="from" action="<?php echo site_url('Accounts/MoveAccount/checkBalances/').$Access_level?>" method="POST" target="_blank" id="UserInput">
    <br><h1 style="text-align: center;">اکاؤنٹ منتقل</h1><br><br>
    <div style="border:1px solid #eee;

            box-shadow:0 0 10px rgba(0, 0, 0, .15);

            max-width:600px;

            margin:auto;

            padding:20px;">
        <div class="row">
            <div class="col-md-6">
                <label class="control-label" for="inputSuccess" style="margin-top: 10%; padding-bottom: 5%;" >اوریجن اکاونٹ</label><br>
                <input type="text" id="AccountCode1" name="AccountCode1" value="50401">
            </div>
            <div class="col-md-6">
                <label class="control-label" for="inputSuccess" style="margin-top: 10%; padding-bottom: 5%;" >ڈیسٹینیشن اکاونٹ</label><br>
                <input type="text" id="AccountCode2" name="AccountCode2" value="50402">
            </div>
        </div>
        <div class="row AccountNameSec">
            <div class="col-md-6 AccountNameSec1">
                <label class="control-label" for="inputSuccess" style="margin-top: 10%; padding-bottom: 5%;" >اکاونٹ کا نام</label><br>
                <p id="AccountName1" name="AccountName1"></p>
            </div>
            <div class="col-md-6 AccountNameSec2">
                <label class="control-label" for="inputSuccess" style="margin-top: 10%; padding-bottom: 5%;" >اکاونٹ کا نام</label><br>
                <p id="AccountName2" name="AccountName2"></p>
            </div>
        </div>
        <div style="line-height:10%;" class="row">
            <div class="col-md-6">
                <label class="control-label" for="inputSuccess" style="margin-top: 10%; padding-bottom: 5%;" >تاریخ منتخب کریں</label>
                <input type="text" class="form-control" id="daterange" name="daterange1" required>
                <input type="hidden" id="to" name="to">
                <input type="hidden" id="from" name="from">
            </div>
            <div class="col-md-6" style="margin-top: 10%">
                <label class="radio-inline">
                    <input type="radio" name="ttype" id="ttype" value="p" checked>مستقل
                </label>
                <label class="radio-inline">
                    <input type="radio" name="ttype" id="ttype" value="t"> عارضی
                </label>
            </div>
        </div>
        <br><br>
        <input type="submit" class="button" value="بیلنس چیک کریں">
        <input type="hidden" id="comp" value="<?= $Access_level?>">
    </form>
        <div id="info">

        </div>
        <button type="button" id="Proceed" class="btn btn-info" style="margin-right: 40%; display: none">
            <span class="glyphicon glyphicon-chevron-right"></span> Proceed
        </button>
    </div>
<style type="text/css">
    .button {
        padding: 5px 8px;
        text-align: center;
        font-size: 13px;
    }
</style>
<script src="<?php echo base_url()."assets/"; ?>js/jquery-1.12.4.js"></script>
<script>
    $(document).ready(function() {
        $('.AccountNameSec1').hide();
        $('.AccountNameSec2').hide();
        var AccessRights = $('#AccessRights').val();
        if (AccessRights == 1 || <?=$_SESSION['user'][0]->id?> == 1){
            $('#UserInput').show();
        }else {
            $('#UserInput').hide();
        }

        $('#AccountCode1').on('blur keyup',function(){
            var AccountCode = $(this).val();
            var level = $('#comp').val();
            if(!AccountCode){
                $('.AccountNameSec1').hide();
                $('#get').removeAttr("disabled");
            }else{

            $.ajax({
                type:'GET',
                url:'<?php echo site_url('Accounts/MoveAccount/getAccountName');?>/'+AccountCode + '/'+ level,
                success:function (response) {
                    var data = $.parseJSON(response);
                    if(data == false){
                        $('.AccountNameSec1').show();
                        $('#AccountName1').text("-----");
                        $('#get').attr("disabled",true);
                    }else{
                        $('.AccountNameSec1').show();
                        $('#AccountName1').text(data.name);
                        $('#get').removeAttr("disabled");
                    }
                }
            });
        }
    });
        $('#AccountCode2').on('blur keyup',function(){
            var AccountCode = $(this).val();
            var level = $('#comp').val();
            if(!AccountCode){
                $('.AccountNameSec2').hide();
                $('#get').removeAttr("disabled");
            }else{
            $.ajax({
                type:'GET',
                url:'<?php echo site_url('Accounts/MoveAccount/getAccountName');?>/'+AccountCode  + '/'+ level,
                success:function (response) {
                    var data = $.parseJSON(response);
                    if(data == false){
                        $('.AccountNameSec2').show();
                        $('#AccountName2').text("-----");
                        $('#get').attr("disabled",true);
                    }else{
                        $('.AccountNameSec2').show();
                        $('#AccountName2').text(data.name);
                        $('#get').removeAttr("disabled");
                    }
                }
            });
        }
    });
        var max_date = function () {
            var tmp = null;
            $.ajax({
                'async': false,
                type:'GET',
                url:'<?php echo site_url('Accounts/Calendar/getMaxDate');?>',
                success:function(response){
                    var data = $.parseJSON(response);
                    tmp = data.date;
                }
            });
            return tmp;
        }();

        var min_date = function () {
            var temp = null;
            $.ajax({
                'async': false,
                type:'GET',
                url:'<?php echo site_url('Accounts/Calendar/getMinDate');?>',
                success:function(response){
                    var data = $.parseJSON(response);
                    temp = data.date;
                }
            });
            return temp;
        }();

        $('input[name="daterange1"]').daterangepicker({
            "minDate": new Date(min_date),
            "maxDate": new Date(max_date),
            "startDate": new Date(min_date),
            "endDate": moment()
        }, function(start, end) {
            $('#to').val(start.format('YYYY-MM-DD'));
            $('#from').val(end.format('YYYY-MM-DD'));
        });
        $('#to').val(min_date);
        $('#from').val(moment().format('YYYY-MM-DD'));

        $("#ledgerofp").prop("checked", true);
        $("#inc_0").prop("checked", true);
        $('#parent').change(function () {
            $('#levels').empty();
            var parent = $('#parent :selected').data('id');
            $.ajax({
                type:'GET',
                url:'<?php echo site_url('Accounts/Ledger/getlevels');?>/'+parent,
                success:function (response) {
                    var data = $.parseJSON(response);
                    $('#levels').append($('<option/>', {
                        value: 0,
                        text : 'منتخب کریں'
                    }).attr('disabled',true).attr('selected',true));
                    $.each(data, function (index, value) {
                        $('#levels').append($('<option/>', {
                            value: value['id'],
                            text : value['LevelName']
                        }));
                    });
                }
            });
        });
        var level = $('#comp').val();
        $.ajax({
            type:'GET',
            url:'<?php echo site_url('Accounts/CompanyStructures/getParent');?>'+'/'+level,
            success:function(response){
                var data = $.parseJSON(response);
                $('#parent').val(data.head_id).attr("selected",'true');
                var parent = $('#parent :selected').data('id');
                $.ajax({
                    type:'GET',
                    url:'<?php echo site_url('Accounts/Ledger/getlevels');?>/'+parent,
                    success:function (response) {
                        var data = $.parseJSON(response);
                        $.each(data, function (index, value) {
                            $('#levels').append($('<option/>', {
                                value: value['id'],
                                text : value['LevelName']
                            }));
                        });
                        $('#levels').val(level).attr("selected",'true');
                    }
                });
            }
        });
    });

    $('#get').on('click',function(e){
        e.preventDefault();
        var post = new Object();
        post.originAccount = $('#AccountCode1').val();
        post.destAccount = $('#AccountCode2').val();
        post.to = $('#to').val();
        post.from = $('#from').val();
        var level = $('#comp').val();

        if(post.originAccount == post.destAccount){
            new PNotify({
                title: 'انتباہ',
                text: 'اوریجن اکاونٹ اور ڈیسٹینیشن اکاونٹ ایک جیسے نہیں ہوسکتے۔',
                type: 'error',
                hide: true
            });
        }else if(!post.originAccount || !post.destAccount || !post.to || !post.from){
            if(!post.originAccount && !post.destAccount){
                new PNotify({
                        title: 'انتباہ',
                        text: 'اوریجن اکاونٹ کا اندراج ضروری ہے۔',
                        type: 'error',
                        hide: true
                });
            }else if(post.originAccount == post.destAccount){
                new PNotify({
                    title: 'انتباہ',
                    text: 'اسدسد',
                    type: 'error',
                    hide: true
                });
            }else if(!post.to && !post.from){
                new PNotify({
                        title: 'انتباہ',
                        text: 'برائے مہربانی تاریخ منتخب کریں۔',
                        type: 'error',
                        hide: true
                });
            }else if(!post.originAccount){
                new PNotify({
                    title: 'انتباہ',
                    text: 'اوریجن اکاونٹ کا اندراج ضروری ہے۔',
                    type: 'error',
                    hide: true
                });
            }else if(!post.destAccount){
                new PNotify({
                    title: 'انتباہ',
                    text: 'ڈیسٹینیشن اکاونٹ کا اندراج ضروری ہے۔',
                    type: 'error',
                    hide: true
                });
            }
        }
    });

    $('#Proceed').on('click',function(){
        var post = new Object();
        post.originAccount = $('#OAID').val();
        post.destAccount = $('#DAID').val();
        post.OACB = $('#OACB').val();
        post.DACB = $('#DACB').val();
        var level = $('#comp').val();

        $.ajax({
            type:'post',
            url:'<?php echo site_url('Accounts/MoveAccount/updateMoveAccount');?>'+'/'+level,
            data:post,
            success:function(response){
                var data = $.parseJSON(response);
                if(data['success']){
                    new PNotify({
                        title: 'کامیابی',
                        text: 'اکاونٹ کامیابی سے منتقل ہوگئے',
                        type: 'success',
                        hide: true
                    });
                }else{
                    new PNotify({
                        title: 'انتباہ',
                        text: 'اکاونٹ منتقل نہیں ہوپائے',
                        type: 'error',
                        hide: true
                    });
                }
            }
        });
    });
</script>