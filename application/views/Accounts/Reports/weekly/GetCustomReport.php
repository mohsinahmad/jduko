<?php if (isset($_SESSION['comp_id'])){
    $Access_level = $_SESSION['comp_id'];
}elseif(isset($_SESSION['comp'])){
    $Access_level = $_SESSION['comp'];
}else{
    $Access_level = '';
} ?>
<form role ="from" action="<?php echo site_url('Accounts/WeeklyReport/SetupTransaction')?>" method="POST"  id="UserInput">
    <br><h1 style="text-align: center;">کسٹم رپورٹ</h1><br><br>
    <div style="border:1px solid #eee;

            box-shadow:0 0 10px rgba(0, 0, 0, .15);

            max-width:600px;

            margin:auto;

            padding:20px;">
        <div style="line-height:10%;" class="row">
            <div class="col-md-6">
                <label class="control-label" for="inputSuccess" style="margin-top: 10%; padding-bottom: 5%;" >رپورٹ</label><br>
                <select name="report_id" class="form-control" id="parent" style="padding-bottom: 0px;padding-top: 0px;" >
                    <option selected disabled>رپورٹ کا انتخاب کریں</option>
                    <?php foreach($report as $value):?>
                        <option value="<?php echo $value->Id?>"><?php echo $value->ReportName?></option>
                    <?php endforeach?>
                </select>
            </div>
<!--             <div style="" class="col-md-6">
                <label class="control-label" for="inputSuccess" style="margin-top: 10%; padding-bottom: 5%;" >تاریخ منتخب کریں</label>
                <input type="text" class="form-control" id="daterange" name="daterange2" required>
                <input type="hidden" id="to" name="to">
                <input type="hidden" id="from" name="from">
            </div> -->
            <input type="hidden" id="to" name="to" value="<?= $to?>">
            <input type="hidden" id="from" name="from" value="<?= $from?>">
            <input type="hidden" id="tran_of" name="tran_of" value="<?= $tran_of?>">
            <input type="hidden" id="serial" name="serial" value="<?= $serial?>">
        </div>
        <br><br>
        <input style="margin-right: 2%;" type="submit" name="get" id="get" class="button v_p" data-id="1" value="ٹرانزیکشن سیٹ کریں">
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

        $('#to').val(min_date);
        $('#from').val(moment().format('YYYY-MM-DD'));

        <?php if (isset($_SESSION['comp_id'])){
            $Access_level = $_SESSION['comp_id'];
        }elseif (isset($_SESSION['comp'])){
            $Access_level = $_SESSION['comp'];
        }else{
            $Access_level = '';
        }?>
        var level = '<?= $Access_level;?>';
       
    });
</script>