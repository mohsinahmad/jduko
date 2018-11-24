<?php if (isset($_SESSION['comp_id'])){
    $Access_level = $_SESSION['comp_id'];
}elseif (isset($_SESSION['comp'])){
    $Access_level = $_SESSION['comp'];
}else{ $Access_level = ''; }?>
<form role ="from" action="<?php echo site_url('Accounts/CashHolding/s_CheckVocher')?>" method="POST" target="_blank" id="UserInput">
    <br><h1 style="text-align: center;">نقد ہولڈنگ رپورٹ</h1><br><br>
    <div style="border:1px solid #eee;

            box-shadow:0 0 10px rgba(0, 0, 0, .15);

            max-width:600px;

            margin:auto;

            padding:20px;">
        <div  class="row">
            <div style="" class="col-md-6">
                <label class="control-label" for="inputSuccess" style="margin-top: 10%; padding-bottom: 5%;" >تاریخ منتخب کریں</label>
                <input type="text" class="form-control" id="daterange" name="daterange2" required>
                <input type="hidden" id="to" name="to">
                <input type="hidden" id="from" name="from">
            </div>
            <div class="col-md-6">
                <label class="control-label"  for="inputSuccess" style="margin-top: 10%; padding-bottom: 5%;">Report Date</label>
                <input type="date" class="form-control" disabled id="datepicker" value="<?php echo date("Y-m-d");?>" name="reportdate">
            </div>
            <input type="hidden" value="" id="print" name="print">
        </div>
        <br><br>
        <input type="submit" name="get" id="get" class="button v_p" data-id="0" value=" ڈیٹا سیٹ کریں">
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

        $('#levels').change(function(){
            $('#account_name').empty();
            var comp_id =  $(this).val();
            $.ajax({
                type: 'GET',
                url:'<?php echo site_url('Accounts/Ledger/getAccountName');?>/'+comp_id,
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
        });
    });
    //    $('#get').on('click',function(){
    //        if($('#to').empty() && $('#from').empty()){
    //                    new PNotify({
    //                        title: 'انتباہ',
    //                        text: "تاریخ منتخب کرنا ضروری ہے",
    //                        type: 'error',
    //                        delay: 3000,
    //                        hide: true
    //                    });
    //                    event.preventDefault();
    //                }
    //         });
</script>