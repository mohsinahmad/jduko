<?php if (isset($_SESSION['comp_id'])){
    $Access_level = $_SESSION['comp_id'];
}elseif(isset($_SESSION['comp'])){
    $Access_level = $_SESSION['comp'];
}else{
    $Access_level = '';
} ?>
<form role ="from" action="<?php echo site_url('Accounts/IncomeReport/IncomeReport')?>" method="POST" target="_blank" id="UserInput">
    <br><h1 style="text-align: center;"> آمدنی رپورٹ</h1><br><br>
    <div style="border:1px solid #eee;

            box-shadow:0 0 10px rgba(0, 0, 0, .15);

            max-width:600px;

            margin:auto;

            padding:20px;">
        <div style="line-height:10%;" class="row">
            <div class="col-md-6">
                <label class="control-label" for="inputSuccess" style="margin-top: 10%; padding-bottom: 5%;" >شعبہ</label>
                <select name="depart" id="depart" class="form-control" style="padding-bottom: 0px;padding-top: 0px;" >
                    <option value="" selected>سب شبعے</option>
                <?php foreach($departs as $depart){ ?>
                    <option value="<?= $depart->Id?>" ><?= $depart->DepartmentName?></option>
                 <?php } ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="control-label" for="inputSuccess" style="margin-top: 10%; padding-bottom: 5%;" >تاریخ منتخب کریں</label>
                <input type="text" class="form-control" id="daterange" name="daterange1" required>
                <input type="hidden" id="to" name="to">
                <input type="hidden" id="from" name="from">
            </div>
            <input type="hidden" value="" id="print" name="print">
        </div>
        <br><br>
        <input type="submit" name="get" id="get" class="button v_p" data-id="0" value="آمدنی رپورٹ حاصل کریں">
        <?php $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,18,$Access_level);
        if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[5] == '1')){?>
        <input style="margin-right: 2%;" type="submit" name="get" id="get" class="button v_p" data-id="1" value="آمدنی رپورٹ پرنٹ کریں">
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
        $('#parent').attr('selected',true);
        $("#ledgerofp").prop("checked", true);
        $('#parent').change(function () {
            $('#levels').empty();
            var parent = $('#parent :selected').data('id');
            //var parent = $('#parent :selected').val();
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

        <?php if (isset($_SESSION['comp_id'])){
            $Access_level = $_SESSION['comp_id'];
        }elseif (isset($_SESSION['comp'])){
            $Access_level = $_SESSION['comp'];
        }else{
            $Access_level = '';
        }?>
        var level = '<?= $Access_level;?>';
        $.ajax({
            type:'GET',
            url:'<?php echo site_url('Accounts/CompanyStructures/getParent');?>'+'/'+level,
            success:function(response){
                var data = $.parseJSON(response);
                $('#parent').val(data.head_id).attr("selected",'true');
                //var data_id = $('#parent :selected').data('id');
                var parent = $('#parent :selected').data('id');
                //var parent = $('#parent :selected').val();
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
        //alert(level);
    });

        $("#levels").change(function(){
             $('#depart').empty();
            var levelss = $('#levels :selected').val();          
            $.ajax({
                type:'Get',
                url: '<?php echo site_url('Accounts/Books/GetDepart');?>/'+levelss,
                success:function(response) {
                    var data = $.parseJSON(response);
                    $('#depart').append($('<option/>', {
                        value: 0,
                        text : 'منتخب کریں'
                    }).attr('disabled',true).attr('selected',true));
                    $.each(data, function(index, value){
                        $('#depart').append($('<option/>',{
                            value: value['Id'],
                            text:  value['DepartmentName']
                        }));
                    });               
                }
            });
        });
</script>