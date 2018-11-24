<?php $count = 0;

$a  = ' '.$this->uri->segment(3).'';

foreach($demandItems as $key => $demandItem): ?>
    <tr class="Items" data-id='<?= $demandItem->code?>'>
        <td style="text-align: center"><?= $demandItem->code?></td>
        <td style="text-align: center" class="ItemName"><?= $demandItem->name?></td>
        <td style="text-align: center" class="DemandQuantity"><?= $demandItem->Item_Quantity?></td>
        <td style="text-align: center" class="DonationType " ><?= $demandItem->Donation_Type?></td>
        <?php if(strpos($a,'getApproveDemandItemsEdit')){?>
        <td style="text-align: center"><input class="form-control ApprovedQuantity<?= $count ?>" style="width: 100%; text-align:center;" type="text" name="" value="<?= $approve[$key]->Approve_Quantity?>" readonly ></td>
        <?php }else{?>

            <td style="text-align: center"><input class="form-control ApprovedQuantity<?= $count ?>" style="width: 100%; text-align:center;" type="text" name="" value="0" readonly ></td>
        <?php } ?>
        <td style="text-align: center" class="DonationType<?= $count?>">
            <button type="button" class="toEdit" data-id="<?= $demandItem->S_ID?>">منظور</button>
        </td>
        <input type="hidden" name="demand_id" class="demand_id" value="<?= $demandItem->d_id?>">
        <input type="hidden" name="Item_Id[]" class="Item_Id<?= $count?>" value="<?= $demandItem->Item_Id?>">
        <input type="hidden" name="Item_Code[]" id="Item_Code<?= $count?>" value="<?= $demandItem->code?>">
        <input type="hidden" name="Row_no" class="Row_no<?= $count?>" id="Row_no" value="<?= $count?>">
        <input type="hidden" name="Total_Items" class="Total_Items">
        <?php if(strpos($a,'getApproveDemandItemsEdit')){?>
        <td style="display: none"><textarea name="items[]" class="item_ids<?= $count?>"><?= $demandItem->S_ID?></textarea></td>
        <td style="display: none"><textarea name="quantity[]" class="AppQuan<?= $count?>"><?= $approve[$key]->Approve_Quantity?></textarea></td>
        <?php }else{?>
            <td style="display: none"><textarea name="items[]" class="item_ids<?= $count?>"></textarea></td>
            <td style="display: none"><textarea name="quantity[]" class="AppQuan<?= $count?>"></textarea></td>
        <?php } ?>
    </tr>
    <?php ++$count; endforeach ?>
<script src="<?= base_url()."assets/"; ?>js/jquery-1.12.4.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script type="text/javascript">
    var Row_no;
    $('.toEdit').on('click',function(){
        var code = $(this).data('id');
        $('#gridModalLabel').html('');
        var ItemName = $(this).parent('td').parent('tr').find('.ItemName').html();
        var DemandQuantity = $(this).parent('td').parent('tr').find('.DemandQuantity').html();
        var DonationType = $(this).parent('td').parent('tr').find('.DonationType').html();
        Row_no = $(this).parent('td').parent('tr').find('#Row_no').val();
        $('#gridModalLabel').html(ItemName+' : '+DonationType+' - '+DemandQuantity);
        $('#itemApprove').modal("show");
        // $('#itemApprove').on('shown.bs.modal', function() {
        $.ajax({
            type:'get',
            url:'<?= site_url('Store/ApproveDemand/getDataToApprove');?>'+'/'+code,
            success:function(response){
                var data = $.parseJSON(response);
//                console.log(response);
                $('.items').empty();
                // console.log(data);
                $.each(data['donation_type'],function(index, value){
//                    console.log(data['donation_type'][index]);
                    $('.quan').html("بقیہ مقدار"+'-'+data['Available_to_approve'][index]);
                    if (index == 0){
                        $('.items').append('<div class="row itemsRow">' +
                            '<div class="panel-body" style="padding-bottom: 0px;">' +
                            '<div class="row"><div class="col-xs-4"> ' +
                            '<div class="form-group"><label>تعون کی قسم</label>' +
                            '<div class="form-group">' +
                            '<input class="form-control" type="text" name="" value="'+value+'" placeholder="" autofocus>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '<div class="col-xs-2">' +
                            '<div class="form-group">' +
                            '<label class="control-label" for="inputSuccess">اسٹاک</label>' +
                            '<input class="form-control" value="'+data['current_quantity'][index]+'" id="stock" name="" type="text" style="padding-left: 0px;padding-right: 0px;text-align: center;" readonly>' +
                            '</div></div>' +
                            '<div class="col-xs-2">' +
                            '<div class="form-group">' +
                            '<label class="control-label" for="inputSuccess">منظورشدہ</label>' +
                            '<input class="form-control" id="approved" name="" value="'+data['Approve_Quantity'][index]+'" type="text" style="padding-left: 0px;padding-right: 0px;text-align: center;" readonly>' +
                            '</div>' +
                            '</div>' +
//                            '<div class="col-xs-2">' +
//                           '<div class="form-group">' +
//                            '<label class="control-label" for="inputSuccess">قابل منظور</label>' +
//                            '<input class="form-control" id="demand_quantity" name="" value="'+data['demand_quantity'][index]+'" type="text" style="padding-left: 0px;padding-right: 0px;text-align: center;" readonly>' +
//                            '</div>' +
//                            '</div>' +
                            '<div class="col-xs-2">' +
                            '<div class="form-group">' +
                            '<label class="control-label" for="inputSuccess">موجود</label>' +
                            '<input class="form-control current'+index+'" id="available" name="" value="'+data['Available_to_approve'][index]+'" type="text" style="padding-left: 0px;padding-right: 0px;text-align: center;" readonly>' +
                            '</div>' +
                            '</div>' +
                            '<div class="col-xs-2">' +
                            ' <div class="form-group">' +
                            '<label class="control-label" for="inputSuccess">منظور</label>' +
                            '<input class="form-control a_quantity ApproveQuan'+index+'" id="ApproveQuantity'+index+'" name="" style="padding-left: 0px;padding-right: 0px;text-align: center;" type="number">' +
                            '</div>' +
                            '</div>' +
                            '<input type="hidden" class="Itemid'+index+'" value="'+data['Item_id'][index]+'" name="Item_id">' +
                            '<input type="hidden" id="DemandQuantity"  name="DemandQuantity">' +
                            '</div>' +
                            '</div>' +
                            '</div>');
                    }else{
                        $('.items').append('<div class="row itemsRow">' +
                            '<div class="panel-body" style="padding-bottom: 0px;">' +
                            '<div class="row"><div class="col-xs-4">' +
                            ' <div class="form-group">' +
                            '<div class="form-group">' +
                            '<input class="form-control" type="text" name="" value="'+value+'" placeholder="" autofocus>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '<div class="col-xs-2">' +
                            '<div class="form-group">' +
                            '<input class="form-control" value="'+data['current_quantity'][index]+'" id="stock" name="" type="text" style="padding-left: 0px;padding-right: 0px;text-align: center;" readonly>' +
                            '</div>' +
                            '</div>' +
                            '<div class="col-xs-2">' +
                            '<div class="form-group">' +
                            '<input class="form-control" id="approved" name="" value="'+data['Approve_Quantity'][index]+'" type="text" style="padding-left: 0px;padding-right: 0px;text-align: center;" readonly>' +
                            '</div>' +
                            '</div>' +
                            '<div class="col-xs-2">' +
                            '<div class="form-group">' +
                            '<input class="form-control current'+index+'" id="available" name="" value="'+data['Available_to_approve'][index]+'" type="text" style="padding-left: 0px;padding-right: 0px;text-align: center;" readonly>' +
                            '</div>' +
                            '</div>' +
                            '<div class="col-xs-2"> ' +
                            '<div class="form-group"><input class="form-control a_quantity ApproveQuan'+index+'" id="ApproveQuantity'+index+'" name="" style="padding-left: 0px;padding-right: 0px;text-align: center;" type="number">' +
                            '</div>' +
                            '</div>' +
                            '<input type="hidden" class="Itemid'+index+'" value="'+data['Item_id'][index]+'" name="Item_id"><input type="hidden" value="'+DemandQuantity+'" id="DemandQuantity"  name="DemandQuantity">' +
                            '</div>' +
                            '</div>' +
                            '</div>');
                    }
                });
            }
        });
        //$('#ApproveQuantity0').val($(this).parent('td').parent('tr').find('.DemandQuantity').html());
    });
    var tr_id;
    $('.demand tbody tr').click(function () {
        tr_id = $(this).data('id');
      //  alert(tr_id);
    });
    var approved_quantity = 0;
    var demand_quantity = 0;
    var itemlength = 0;
    var sum = 0;
    var i =0;
    var myarray = [];
    var items = [];
    $('#itemApprove').on('shown.bs.modal', function() {
                 $('#ApproveQuantity0').val($('tr[data-id='+tr_id+']').children('td').eq(2).html());
        //        $(document).on('keyup','.a_quantity',function(){
//            $('.itemsRow').each(function(index,value){
//                demand_quantity = $('.ApproveQuan'+index).val();
//                if(demand_quantity != ''){
//                    myarray.push(demand_quantity);
//                    approved_quantity = parseInt(approved_quantity) + parseInt(demand_quantity);
//                }
//            });
//            items = myarray.splice(-3);
//            itemlength = items.length;
//            for ( i = 0; i<= itemlength; i++) {
//                //alert(items.i);
//                sum += items.i;
//            }
//            console.log(sum);
//        });
       // $('#ApproveQuantity0').val($('.toEdit').parent('td').parent('tr').find('.DemandQuantity').html());

        //var items = $('.itemsRow').length;
        //alert($('.ApproveQuan'+index).val());
//            alert($('.ApproveQuan'+index).val());
//             approved_quantity = $('.a_quantity').val();
//             demand_quantity = $(".DemandQuantity").html();
//            total =+ demand_quantity - approved_quantity;
    });


    //    $(document).on('keyup','.asd',function(){
    //        $(".quan").html("");
    //        var items = $('.itemsRow').length;
    //
    //            //alert(items);
    //        $('.itemsRow').each(function(index,value){
    //            //alert($('.ApproveQuan'+index).val());
    //            demand_quantity = $('.ApproveQuan'+index).val();
    //            if(demand_quantity != ''){
    //                approved_quantity = parseInt(approved_quantity) + parseInt(demand_quantity);
    //            }
    //console.log(approved_quantity);
    ////            alert($('.ApproveQuan'+index).val());
    //
    ////             approved_quantity = $('.a_quantity').val();
    ////             demand_quantity = $(".DemandQuantity").html();
    ////            total =+ demand_quantity - approved_quantity;
    //        });
    //
    //        $(".quan").html("بقیہ مقدار"+'-'+total);
    //        // // for ( i = 0; i< items; i++) {
    //        //     var approved_quantity = $(this).val();
    //        //     var check = $(".quan").html();
    //        //     var demand_quantity = $(".DemandQuantity").html();
    //        //     var total = check + demand_quantity - approved_quantity;
    //        //      $(".quan").html("بقیہ مقدار"+'-'+total);
    //        // // }
    //    });

    var post = new Object();
    post.Items = [];
    post.Quantity = [];
    $('.Approve').on('click',function(){
        //  debugger;
        var previous_val = $('tr[data-id='+tr_id+']').children('td').eq(4).find('input[type=text]').val();
        var u_demand = $('tr[data-id='+tr_id+']').children('td').eq(2).html();
        //debugger;
        var TotalQuanApp = 0;
        var AppQuant;
        var AppQuantity;
        var current;
        var AQuan;
        var items = $('.itemsRow').length;
        var Demand = u_demand;
        for (var i = 0; i < items; i++) {
            AppQuant =  $('#ApproveQuantity'+i).val();
            current = $('.current' +i).val();
            AQuan = $('.ApproveQuan' +i).val();
            if(AppQuant != ''){
                AppQuantity = AppQuant;
            }else{
                AppQuantity = 0;
            }
            TotalQuanApp = TotalQuanApp + parseFloat(AppQuantity);
        }
        if(TotalQuanApp > Demand ){
            new PNotify({
                title: 'Error',
                text: ' منظور شدہ مقدار ڈیمانڈ سے زیادہ ہے',
                type: 'error',
                hide: true
            });
        }else{
            if(TotalQuanApp > current) {
                new PNotify({
                    title: 'Error',
                    text: ' منظور شدہ مقدار اسٹاک سے زیادہ ہے',
                    type: 'error',
                    hide: true
                });
            }
            else
            {
                $('.ApprovedQuantity' + Row_no).val(TotalQuanApp);
                QuantityArr = new Array();
                ItemArr = new Array();
                post.Demand_id = $('.demand_id').val();

                for(i=0; i < items; i++){
                    var item_id = $('.Itemid'+i).val();

                    var url = window.location.pathname;

//            if(previous_val > 0) {
                    var quantity = $('.ApproveQuan' + i).val();
//            }
//            else {
//                quantity = 0.00;
//            }
                    if(quantity != ''){
                        ItemArr.push(item_id);
                        QuantityArr.push(quantity);
                    }
                }
                $('.item_ids'+Row_no).text(ItemArr);
                $('.AppQuan'+Row_no).text(QuantityArr);
                post.Items[Row_no] = $('.item_ids'+Row_no).text();
                post.Quantity[Row_no] = $('.AppQuan'+Row_no).text();
                $('#itemApprove').modal("hide");
            }
            }
//        QuantityArr = new Array();
//        ItemArr = new Array();
//        post.Demand_id = $('.demand_id').val();
//
//        for(i=0; i < items; i++){
//            var item_id = $('.Itemid'+i).val();
//
//            var url = window.location.pathname;
//
////            if(previous_val > 0) {
//                var quantity = $('.ApproveQuan' + i).val();
////            }
////            else {
////                quantity = 0.00;
////            }
//            if(quantity != ''){
//                ItemArr.push(item_id);
//                QuantityArr.push(quantity);
//            }
//        }

    });
</script>