<style type="text/css">
    .button {
        padding: 5px 8px;
        text-align: center;
        font-size: 13px;
    }
</style>
<?php if (isset($_SESSION['comp_id'])){
    $Access_level = $_SESSION['comp_id'];
}elseif(isset($_SESSION['comp'])){
    $Access_level = $_SESSION['comp'];
}else{
    $Access_level = '';
} //print_r($_SESSION);?>
<form role ="from" action="<?php echo site_url('Accounts/BalanceSheet/GetBalanceSheet')?>" method="POST" target="_blank" id="UserInput">
    <br><h1 style="text-align: center;">ہفتہ واری رپورٹس</h1><br><br>
    <div style="border:1px solid #eee;

            box-shadow:0 0 10px rgba(0, 0, 0, .15);

            max-width:600px;

            margin:auto;

            padding:20px;">
        <div class="row">
            <div class="col-md-6">
                <label class="control-label" for="inputSuccess" style="margin-top: 10%; padding-bottom: 5%;" >تاریخ منتخب کریں</label>
                <input type="text" class="form-control" id="daterange" name="daterange2" required>
                <input type="hidden" id="to" name="to">
                <input type="hidden" id="from" name="from">
            </div>
            <div class="col-md-6">
                <div class="form-group" style="float: left;">
                    <label  style="margin-top: 10%; padding-bottom: 5%;">حوالہ نمبر</label>
                    <div class="form-group">
                        <input class="form-control" type="text" id="serial" name="serial">
                    </div>
                </div>
            </div>
            <input type="hidden" value="" id="print" name="print">
            <a href=""></a>
        </div>
        <div class="row">
            <div class="form-group col-md-10">
                <label class="radio-inline">
                    <input type="radio" name="transOf" value="p" checked>مستقل
                </label>
                <label class="radio-inline">
                    <input type="radio" name="transOf" value="all"> مستقل+عارضی
                </label>
                <label class="radio-inline">
                    <input type="checkbox" name="withOutZero" value="0">زیرو بیلنس کے بغیر
                </label>
            </div>
      
        </div>

        <br><br>
        <input type="button" name="get" id="get1" class="button btn btn-info" data-id="0" value="ہفتہ واری رپورٹ">
        <input type="button" name="get" id="get2" class="button btn btn-info" data-id="0" value="رپورٹ براےَ خود کفیل شعبہ جات">
        <input type="button" name="get" id="get3" class="button btn btn-info" data-id="0" value="کسٹم رپورٹ">
    </div>
</form>
<script src="<?php echo base_url()."assets/"; ?>js/jquery-1.12.4.js"></script>
<script>
    $('#get1').click(function(e) {
        var transOf =  $("input[name='transOf']:checked").val();
        var is_zero =  $("input[name='withOutZero']:checked").val();
        if(!is_zero){
            $is_zero = 0;
        }
        var to = $('#to').val();
        var from = $('#from').val();
        var serial_no = $('#serial').val();

        if ($('#serial').val() == ''){
            alert('حوالہ نمبر کا اندراج کریں') ;
        }else {
            e.preventDefault();
            window.open('<?= site_url("Accounts/WeeklyReport/index")?>'+'/'+to+'/'+from+'/'+transOf+'/'+serial_no+'/'+is_zero);
        }
    });
    $('#get2').click(function(e) {
        var transOf =  $("input[name='transOf']:checked").val();
        var is_zero =  $("input[name='withOutZero']:checked").val();
        if(!is_zero){
            $is_zero = 0;
        }
        var to = $('#to').val();
        var from = $('#from').val();
        var serial_no = $('#serial').val();

        if ($('#serial').val() == ''){
            alert('حوالہ نمبر کا اندراج کریں') ;
        }else {
            e.preventDefault();
            window.open('<?= site_url("Accounts/WeeklyReport/DepartmentReport")?>'+'/'+to+'/'+from+'/'+transOf+'/'+serial_no+'/'+is_zero);
        }
    });
    $('#get3').click(function(e) {
        var transOf =  $("input[name='transOf']:checked").val();
        var to = $('#to').val();
        var from = $('#from').val();
        var serial_no = $('#serial').val();

        if ($('#serial').val() == ''){
            alert('حوالہ نمبر کا اندراج کریں') ;
        }else {
            e.preventDefault();
            window.open('<?= site_url("Accounts/WeeklyReport/CustomReport")?>'+'/'+to+'/'+from+'/'+transOf+'/'+serial_no);
        }
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

        $('#to').val(min_date);
        $('#from').val(moment().format('YYYY-MM-DD'));

    });
</script>