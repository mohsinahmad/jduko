<?php if (isset($_SESSION['comp_id'])){
    $Access_level = $_SESSION['comp_id'];
}elseif (isset($_SESSION['comp'])){
    $Access_level = $_SESSION['comp'];
}else{ $Access_level = ''; }?>
<form role ="from" action="<?php echo site_url('Store/ItemStock/GetItemStock')?>" method="POST" target="_blank" id="UserInput">
    <br><h1 style="text-align: center;">اسٹاک رپورٹ</h1><br><br>
    <div style="border:1px solid #eee;

            box-shadow:0 0 10px rgba(0, 0, 0, .15);

            max-width:600px;

            margin:auto;

            padding:20px;">
        <div style="line-height:10%;" class="row">
            <div class="col-md-6">
                <label class="control-label" for="inputSuccess" style="margin-top: 10%; padding-bottom: 5%;" >آئٹم کیٹیگری</label><br>
                <select name="category" class=" js-example-basic-single1 form-control category" id="category" style="padding-bottom: 0px;padding-top: 0px;" >
                    <option value="" disabled selected>منتخب کریں</option>
                    <?php foreach ($category as $cat): ?>
                        <option value="<?php echo $cat->Id?>" data-id="<?php echo $cat->Code?>">
                            <?php echo  $cat->p_name .'-'. $cat->s_name ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="control-label" for="inputSuccess" style="margin-top: 10%; padding-bottom: 5%;" >عطیہ کی اقسام</label><br>
                <select name="donation" class="js-example-basic-single1 form-control  donation" id="donation" style="padding-bottom: 0px;padding-top: 0px;" >
                    <option value="" disabled selected>منتخب کریں</option>
                    <?php foreach ($donation as $value): ?>
                        <option value="<?php echo $value->Id?>" data-id="<?php echo $value->Id?>"><?php echo $value->Donation_Type?></option>
                    <?php endforeach ?>
                </select>
            </div>
        </div>
        <div  class="row">
            <div class="col-md-6">
                <label class="control-label" for="inputSuccess" style="margin-top: 10%; padding-bottom: 5%;" >آئٹم کی فہرست</label>
                <select name="item" class="js-example-basic-multiple js-states form-control"  id="itemlist" > <!-- multiple="multiple" -->
                    <option value="all" selected>تمام</option>
                </select>
                <div id="div"></div>
            </div>
            <div style="" class="col-md-6">
                <label class="control-label" for="inputSuccess" style="margin-top: 10%; padding-bottom: 5%;" >تاریخ منتخب کریں</label>
                <input type="text" class="form-control" id="daterange" name="daterange2" required>
                <input type="hidden" id="to" name="to">
                <input type="hidden" id="from" name="from">
            </div>

            <!-- <input type="hidden" value="" id="print" name="print"> -->
        </div>
        <br><br>

        <?php $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,35,$Access_level);
        if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[5] == '1')){?>
            <input type="submit" style="line-height: 210%" name="get" id="get" class="" data-id="1" value="اسٹاک رپورٹ حاصل کریں">
        <?php }?>
        <input type="hidden" id="comp" value="<?= $Access_level?>">
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
<script src="<?php echo base_url()."assets/"; ?>js/select2.min.js"></script>
<script type="text/javascript">
    $(".js-example-basic-single1").select2({
        dir: "rtl"
    });
</script>
<script>
    $('.v_p').click(function () {
        $('#print').val($(this).data('id'));
    });
    $(document).ready(function(){
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

    $('.category').on('change',function () {
        $('#itemlist').empty();
        var category = $('.category').val();
        var donation = $('.donation').val();
        if(donation){
            $.ajax({
                type:'GET',
                url:'<?php echo site_url('Store/ItemStock/Get_Item_by_Category_And_donation');?>/'+category+'/'+donation,
                success:function (response) {
                    var data = $.parseJSON(response);
                    $('#itemlist').append($('<option/>', {
                        value: 'all',
                        text : 'تمام'
                    }).attr('selected',true));
                    $.each(data, function (index, value) {
                        $('#itemlist').append($('<option/>', {
                            value: value['code'],
                            text : value['name']
                        }));
                    });
                }
            });
        }else{
            $.ajax({
                type:'GET',
                url:'<?php echo site_url('Store/ItemStock/Get_Item_by_Category');?>/'+category,
                success:function (response) {
                    var data = $.parseJSON(response);
                    $('#itemlist').append($('<option/>', {
                        value: 'all',
                        text : 'تمام'
                    }).attr('selected',true));
                    $.each(data, function (index, value) {
                        $('#itemlist').append($('<option/>', {
                            value: value['code'],
                            text : value['name']
                        }));
                    });
                }
            });
        }
    });

    $('.donation').on('change',function () {
        $('#itemlist').empty();
        var category = $('.category').val();
        var donation = $('.donation').val();
        if(category){
            $.ajax({
                type:'GET',
                url:'<?php echo site_url('Store/ItemStock/Get_Item_by_Category_And_donation');?>/'+category+'/'+donation,
                success:function (response) {
                    var data = $.parseJSON(response);
                    $('#itemlist').append($('<option/>', {
                        value: 'all',
                        text : 'تمام'
                    }).attr('selected',true));
                    $.each(data, function (index, value) {
                        $('#itemlist').append($('<option/>', {
                            value: value['code'],
                            text : value['name']
                        }));
                    });
                }
            });
        }else{
            $.ajax({
                type:'GET',
                url:'<?php echo site_url('Store/ItemStock/Get_Item_by_Donation');?>/'+donation,
                success:function (response) {
                    var data = $.parseJSON(response);
                    $('#itemlist').append($('<option/>', {
                        value: 'all',
                        text : 'تمام'
                    }).attr('selected',true));
                    $.each(data, function (index, value) {
                        $('#itemlist').append($('<option/>', {
                            value: value['code'],
                            text : value['name']
                        }));
                    });
                }
            });
        }
    });
</script>