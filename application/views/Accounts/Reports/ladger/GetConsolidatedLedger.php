<style type="text/css">
    .button {
        padding: 5px 8px;
        text-align: center;
        font-size: 13px;
    }
</style>
<?php if (isset($_SESSION['comp_id'])){
    $Access_level = $_SESSION['comp_id'];
}elseif (isset($_SESSION['comp'])){
    $Access_level = $_SESSION['comp'];
}else{ $Access_level = ''; }?>
<form role ="from" action="<?= site_url('Accounts/Ledger/GetValue/').'1';?>" method="POST" target="_blank" id="UserInput">
    <br><h1 style="text-align: center;">مشترکہ لیجر</h1><br><br>
    <div style="border:1px solid #eee;

            box-shadow:0 0 10px rgba(0, 0, 0, .15);

            max-width:600px;

            margin:auto;

            padding:20px;">
        <div style="line-height:10%;" class="row">
            <div class="col-md-6">
                <label class="control-label" for="inputSuccess" style="margin-top: 10%; padding-bottom: 5%;" >پیرنٹ</label><br>
                <input type="text" name="parent" value="<?= $parents[0]->LevelName?>" readonly>
                <input type="hidden" name="p_id" value="<?= $parents[0]->id?>">
                <input type="hidden" name="p_code" value="<?= $parents[0]->LevelCode?>">
            </div>
            <div class="col-md-6">
                <label class="control-label" for="inputSuccess" style="margin-top: 10%; padding-bottom: 5%;" >لیول</label><br>
                <select name="level" class="form-control" id="levels" style="padding-bottom: 0px;padding-top: 0px;" required>
                    <option value="" disabled selected>منتخب کریں</option>
                    <?php foreach($levelname as $levelss):?>
                        <option value="<?= $levelss->id?>"><?= $levelss->LevelName?></option>
                    <?php endforeach?>
                </select>
            </div>
        </div>
        <div  class="row">
            <div class="col-md-6">
                <label class="control-label" for="inputSuccess" style="margin-top: 10%; padding-bottom: 5%;" >اکاونٹ کا نام</label>
                <select name="account[]" class="js-example-basic-multiple js-states form-control"  id="account_name" multiple="multiple" required>
                </select>
                <div id="div"></div>
            </div>
            <div style="" class="col-md-6">
                <label class="control-label" for="inputSuccess" style="margin-top: 10%; padding-bottom: 5%;" >تاریخ منتخب کریں</label>
                <input type="text" class="form-control" id="daterange" name="daterange2" required>
                <input type="hidden" id="to" name="to">
                <input type="hidden" id="from" name="from">
            </div>
            <div class="form-group col-md-6" style="margin-top: 8px;">
                <label class="radio-inline">
                    <input type="radio" name="ledgerof" id="ledgerofp" value="p" checked>مستقل
                </label>
                <label class="radio-inline">
                    <input type="radio" name="ledgerof" id="ledgerofall" value="all"> مستقل+عارضی
                </label>
            </div>
            <div class="row">
                <div class="form-group col-md-6" style="margin-top: 8px;">
                    <label class="radio-inline">
                        <input type="radio" name="voucher" value="CP">CP
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="voucher" value="CR">CR
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="voucher" value="BP">BP
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="voucher" value="BR">BR
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="voucher" value="JV">JV
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="voucher" value="IC">IC
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="voucher" value="alll" checked>سب
                    </label>
                </div>
            </div>
            <input type="hidden" value="" id="print" name="print">
        </div>
        <br><br>
        <input type="submit" name="get" id="get" class="button v_p" data-id="0" value="لیجر حاصل کریں">
        <?php $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,34,$Access_level);
        if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[5] == '1')){?>
            <input type="submit" name="get" id="get" class="button v_p" data-id="1" value="لیجر پرنٹ کریں">
        <?php }?>
        <input type="hidden" id="comp" value="<?= $Access_level?>">
    </div>
</form>
<script src="<?php echo base_url()."assets/"; ?>js/jquery-1.12.4.js"></script>
<script>
    $('.v_p').click(function () {
        $('#print').val($(this).data('id'));
    });
    $(document).ready(function() {
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
        $('input[name="daterange2"]').daterangepicker({
            "minDate": new Date(min_date),
            "maxDate": new Date(max_date),
            "startDate": new Date(min_date),
            "endDate": moment()
        }, function(start, end) {
            $('#to').val(start.format('YYYY-MM-DD'));
            $('#from').val(end.format('YYYY-MM-DD'));
        });
        // $.ajax({
        //         type:'GET',
        //         url:'<?php echo site_url('Accounts/Calendar/getMinDate');?>',
        //         success:function(response){
        //             var data = $.parseJSON(response);
        //             $('#to').val(data.date);
        //         }
        //     });
        $('#to').val(min_date);
        $('#from').val(moment().format('YYYY-MM-DD'));
        $('#parent').attr('selected',true);
        $("#ledgerofp").prop("checked", true);

        $(function(){
            $('#account_name').empty();
            var comp_id =  $(this).val();
            $.ajax({
                type: 'GET',
                url:"<?php echo site_url('Accounts/Ledger/getAccountName_Cons');?>",
                success:function (response) {
                    var data = $.parseJSON(response);
                    $.each(data, function (index, value) {
                        $('#account_name').append($('<option></option>', {
                            value: value.a_id,
                            text : value.parentName +'--'+ value.AccountName
                        }));
//                        $('#div').append('<input type="hidden" name="all_acc[]" value="'+value.a_id+'">');
                    });
                }
            });
        });
        $.ajax({
            type:'GET',
            url:'<?php echo site_url('Accounts/Ledger/getlevels');?>/'+'1',
            success:function (response) {
                var data = $.parseJSON(response);
                $('#levels').empty();
                $.each(data, function (index, value) {
                    $('#levels').append($('<option/>', {
                        value: value['id'],
                        text : value['LevelName']
                    }));
                });
                var yeah_level;
                if('<?= $_SESSION["parent_id"]?>' == '101'){
                    yeah_level = 3;
                }else{
                    yeah_level = 37;
                }

                $('#levels').val(yeah_level).attr("selected",'true');
                var Linkedcomp =  $('#levels').val();
                $.ajax({
                    type: 'GET',
                    url:'<?php echo site_url('Accounts/Ledger/getAccountName');?>/'+Linkedcomp,
                    success:function (response) {
                        var data = $.parseJSON(response);
                        $.each(data, function (index, value) {
                            $('#account_name').append($('<option></option>', {
                                value: value.a_id,
                                text : value.parentName +'--'+ value.AccountName
                            }));
                            $('#div').append('<input type="hidden" name="all_acc[]" value="'+value.a_id+'">');
                        });
                    }
                });
            }
        });
    });
</script>