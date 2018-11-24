<?php $count = 0;

$a  = ' '.$this->uri->segment(3).'';
echo $a;
foreach($demandItems as $key => $demandItem): ?>
    <tr class="Items" data-id='<?= $demandItem->code?>'>
        <td style="text-align: center"><?= $demandItem->code?></td>
        <td style="text-align: center" class="ItemName"><?= $demandItem->name?></td>
        <td style="text-align: center" class="DemandQuantity"><?= $demandItem->Item_Quantity?></td>
        <td style="text-align: center" class="DonationType " ><?= $demandItem->total?></td>
        <?php if(isset($demandItem->Approve_Quantity)){?>
        <td style="text-align: center"><input name="quantity[]" class="form-control ApprovedQuantity<?= $count ?>" style="width: 100%; text-align:center;" type="text" name="" autocomplete="off" value="<?= $demandItem->Approve_Quantity?$demandItem->Approve_Quantity:'0'?>" ></td>
        <?php }else{?>
            <td style="text-align: center"><input autocomplete="off" name="quantity[]" class="form-control ApprovedQuantity<?= $count ?>" style="width: 100%; text-align:center;" type="text" name="" value="0"></td>
        <?php } ?>
        <td style="text-align: center" class="DonationType<?= $count?>">
            <button type="button" class="toEdit" data-id="<?= $demandItem->S_ID?>">تفصیل</button>
        </td>
        <input type="hidden" name="demand_detail_id[]" class="demand_id" value="<?= $demandItem->d_id?>">
        <input type="hidden" name="demand_id" class="demand_id" value="<?= $demandItem->demand_id?>">
        <input type="hidden" name="Item_Id[]" class="Item_Id<?= $count?>" value="<?= $demandItem->Item_Id?>">
        <input type="hidden" name="Item_Code[]" id="Item_Code<?= $count?>" value="<?= $demandItem->code?>">
        <input type="hidden" name="Row_no" class="Row_no<?= $count?>" id="Row_no" value="<?= $count?>">
        <input type="hidden" name="Total_Items" class="Total_Items">
        <?php if(isset($approve[$key])){?>
        <td style="display: none"><textarea name="items[]" class="item_ids<?= $count?>"><?= $demandItem->S_ID?></textarea></td>
        <?php }else{?>
            <td style="display: none"><textarea name="items[]" class="item_ids<?= $count?>"></textarea></td>
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
        $('#gridModalLabel').html(ItemName);
        $('#itemApprove').modal("show");
        $.ajax({
            type:'get',
            url:'<?= site_url('Store/ApproveDemand/get_demand_item_detail');?>'+'/'+code,
            success:function(response){
                var data = $.parseJSON(response);
                var tabl_row = '';
                $.each(data,function(index, value){
                    tabl_row +=  '<tr>'+
                            '<td>'+data[index].Donation_Type+'</td>'+
                            '<td>'+data[index].total_quantity+'</td>'
                            +'</tr>'
                });
                $('#detail-table tbody').html(tabl_row);
            }
        });
    }) ;
    var error = '';
    $('.demand tbody tr').each(function (index,value) {
        $('.ApprovedQuantity'+index).focusout(function () {
           var val = $(this).val();
            $(this).attr('value',val);
            var item_demand =  parseFloat($(this).parent('td').parent('tr').children('td').eq(2).text());
            var stock_item =   parseFloat($(this).parent('td').parent('tr').children('td').eq(3).text());
            if($(this).val() > item_demand){
                new PNotify({
                    title: 'Error',
                    text: ' منظور شدہ مقدار ڈیمانڈ سے زیادہ ہے',
                    type: 'error',
                    hide: true
                });
                error = 'error';
            }
            else if($(this).val() > stock_item){
                $(this).attr('value',$(this).val());
              
                $('.AppQuan'+index).html($(this).val());
                $(this).attr('value',$(this).val())
                new PNotify({
                    title: 'کامیاب',
                    text: 'منظور شدہ مقدار اسٹاک سے زیادہ ہے',
                    type: 'error',
                    hide: true
                });
                error = 'error';
            }
            else{
                error = '';
            }
        });
    });
    $('.ApproveDemand').click(function (e) {
        if(error == 'error'){
            e.preventDefault();
        }
    });

</script>