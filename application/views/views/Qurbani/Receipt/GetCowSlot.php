<form role ="from" action="<?php echo site_url('Qurbani/Receipt/ViewCowSlip')?>" method="POST" target="_blank" id="UserInput">
    <br><h1 style="text-align: center;">گائے نمبر</h1><br><br>
    <div style="border:1px solid #eee;

            box-shadow:0 0 10px rgba(0, 0, 0, .15);

            max-width:600px;

            margin:auto;

            padding:20px;">
        <div style="" class="row">
            <div class="col-md-6" style="margin-top: 6%;">
                <label class="control-label" for="inputSuccess">قربانی کا دن</label>
                <br>
                <div class="form-group">
                    <label class="radio-inline"><input type="radio" value="1" name="Collection_Day" required checked>۱۰ ذی الحج</label>
                    <label class="radio-inline"><input type="radio" value="2" name="Collection_Day" required>۱۱  ذی الحج</label>
                    <label class="radio-inline"><input type="radio" value="3" name="Collection_Day" required>۱۲ ذی الحج</label>
                </div>
            </div>
            <div class="col-md-3">
                <label class="control-label" for="inputSuccess" style="margin-top: 27%; padding-bottom: 5%;" >گائے نمبر سے</label><br>
                <input type="number" class="form-control" name="from">
            </div>
            <div class="col-md-3">
                <label class="control-label" for="inputSuccess" style="margin-top: 27%; padding-bottom: 5%;" > گائے نمبر تک</label><br>
                <input type="number" class="form-control" name="to">
            </div>
        </div>
        <br>
        <input type="submit" name="get" value="رپورٹ حا صل کریں" style="line-height: 210%;">
    </div>
</form>
<!-- <script src="<?php echo base_url()."assets/"; ?>js/jquery-1.12.4.js"></script>
<script>

    $('input[name=Collection_Day]').on('click',function(){
            var cat_type = $('input[name=Collection_Day]:checked').val();
            alert(cat_type);
            //    $.ajax({
            //     type:'GET',
            //     url:'<?php echo site_url('Store/items/GetCategoryViseItem');?>'+'/'+cat_type,
            //     success:function(result){
            //         var data = $.parseJSON(result);
            //         $('#ItemName').empty();
            //         $('#ItemName').append($('<option/>', {
            //             value: 0,
            //             text : 'منتخب کریں'
            //         }).attr('disabled',true).attr('selected',true));
            //         $.each(data, function (index, value) {
            //             $('#ItemName').append($('<option/>', {
            //                 value: value['Id'],
            //                 text : value['name']
            //             }));
            //         });
            //     }
            // })
        });

</script> -->