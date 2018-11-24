<style type="text/css">
    .button {
        padding: 5px 8px;
        text-align: center;
        font-size: 13px;
    }
     @-moz-document url-prefix() {
                #tem{
                        margin-top: 7%;
                }
                #per{
                    margin-top: 6%;
                }
                #temper{
                    margin-top: -4%;
                }
            }
</style>
<?php if (isset($_SESSION['comp_id'])){
    $Access_level = $_SESSION['comp_id'];
}elseif (isset($_SESSION['comp'])){
    $Access_level = $_SESSION['comp'];
}else{
    $Access_level = '';
} $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,14,$Access_level);
if($_SESSION['user'][0]->IsAdmin != 1 && $rights != array()){?>
    <input type="hidden" id="AccessRights" value="<?= $rights[0]->Rights[4]?>">
<?php }?>
<form role ="from" action="<?php echo site_url('Accounts/AuditTrial/getAuditTrial')?>" method="POST" target="_blank" id="UserInput">
    <br><h1 style="text-align: center;">آڈٹ ٹریل</h1><br><br>
    <div style="border:1px solid #eee;

            box-shadow:0 0 10px rgba(0, 0, 0, .15);

            max-width:600px;

            margin:auto;

            padding:20px;">
        <div style="line-height:10%;" class="row">
            <div class="col-md-6">
                <label class="control-label" for="inputSuccess" style="margin-top: 10%; padding-bottom: 5%;" >تاریخ منتخب کریں</label>
                <input type="text" class="form-control" id="daterange" name="daterange2" required>
                <input type="hidden" id="to" name="to">
                <input type="hidden" id="from" name="from">
            </div>
            <div class="col-md-6">
                <label class="control-label" for="inputSuccess" style="margin-top: 10%; padding-bottom: 5%;" >واؤچر اقسام</label><br>
                <select name="voucher_type" class="form-control" id="" style="padding-bottom: 0px;padding-top: 0px;" >
                    <option value="all" selected>تمام</option>
                    <option value="cp" >CP</option>
                    <option value="cr" >CR</option>
                    <option value="bp" >BP</option>
                    <option value="br" >BR</option>
                    <option value="jv" >JV</option>
                    <option value="IC" >IC</option>
                </select>
            </div>
        </div>
        <div style="line-height:10%;" class="row">
            <div class="form-group col-md-6" style="margin-top: 8px;">
                <label class="radio-inline" id="tem">
                    <input type="radio" name="auditof" id="auditof" value="p" checked>مستقل
                </label>
                <label class="radio-inline" id="per">
                    <input type="radio" name="auditof" id="auditof" value="t"> عارضی
                </label>
                <label class="radio-inline" id="temper" style="float: left;right: 28px;">
                    <input type="radio" name="auditof" id="auditof" value="all">  مستقل+عارضی
                </label>
            </div>
        </div>
        <input type="hidden" value="" id="print" name="print">
        <br><br>
        <input type="submit" name="get" id="get" class="button v_p" data-id="0" value="آڈٹ ٹریل حاصل کریں">
        <?php $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,19,$Access_level);
        if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[5] == '1')){?>
            <input type="submit" name="get" id="get" class="button v_p" data-id="1" value="آڈٹ ٹریل پرنٹ کریں">
        <?php }?>
    </div>
</form>

<script src="<?php echo base_url()."assets/"; ?>js/jquery-1.12.4.js"></script>
<script>
    $('.v_p').click(function () {
        $('#print').val($(this).data('id'));
    });
    $(document).ready(function() {
        var AccessRights = $('#AccessRights').val();
        if (AccessRights == 1 || <?= $_SESSION['user'][0]->IsAdmin?> == 1){
            $('#UserInput').show();
        }else {
            $('#UserInput').hide();
        }

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
            "endDate": moment(),
        }, function(start, end) {
            $('#to').val(start.format('YYYY-MM-DD'));
            $('#from').val(end.format('YYYY-MM-DD'));
        });

        $('#to').val(min_date);
        $('#from').val(moment().format('YYYY-MM-DD'));
    });
</script>