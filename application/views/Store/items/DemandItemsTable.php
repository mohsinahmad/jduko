<?php $count = 1;
foreach($demandItems as $key => $demandItem): ?>
    <tr data-id="toedit<?php echo $key?>" class="toEdit">
        <td style="text-align: center"><?= $demandItem->code?></td>
        <td class="ItemName<?= $count?>" style="text-align: center"><?= $demandItem->name?></td>
        <?php if($demandItem->Approve_Quantity){?>
        <td class="ApproveQuantity<?= $count?>" style="text-align: center"><?= $demandItem->Approve_Quantity?></td>
        <?php }else{?>
        <td class="ApproveQuantity<?= $count?>" style="text-align: center">0.00</td>
        <?php }?>
<!--        <td class="donation" style="text-align: center"><select class="donationtype" required>-->
<!--                <option value="" disabled selected>تعاون کی قسم منتخب کریں</option>-->
<!--            </select></td>-->
        <td class="donation" style="text-align: center">
            <button type="button" data-toggle="modal" data-target="#detail_modal" class="btn btn-primary detail" data-id="<?= $demandItem->S_ID?>">تفصیل</button>
        </td>
        <input type="hidden" name="demand_id" class="demand_id" value="<?= $demandItem->d_id?>">
        <input type="hidden" name="detail[]" class="detail<?php echo $key?>" value="<?= $demandItem->issued_id?>">
        <input type="hidden" name="Item_Id[]" class="Item_Id<?= $count?>" value="<?= $demandItem->Item_Id?>">
        <input type="hidden" name="approved_ID" class="approved_ID" value="<?= $demandItems->Approve_id?>">
<!--        <input type="hidden" name="item_id" class="item_id" value="--><?//= $demandItems->Item_id?><!--">-->
    </tr>
    <?php ++$count;
endforeach ?>
<style>
    .donation{
    padding: 0px;
    width: 20%;
    }
    .select2-container{
        width: 100% !important;
    }
</style>
<script>
    var approve_quantity;
    $(document).ready(function () {
        $('.detail').click(function () {
            // data_post.empty();
            approve_quantity = $(this).parent('td').parent('tr').children('td').eq(2).html();
            deatail_row_id  = $(this).parent('td').parent('tr').data('id');
            $.ajax({
                url:'<?php echo base_url()?>Store/ItemIssue/get_item_for_issue/'+$(this).data('id'),
                type:'get',
                success:function (response) {
                    // debugger;
                    var result = $.parseJSON(response);
                    var tr = ''
                    $.each(result,function (index,value) {
                        tr += '<tr data-id="'+(result[index].issue_id)+'"><td>'+result[index].name+'</td>' +
                            '<td>'+result[index].Donation_Type+'</td>'+
                            '<td>'+result[index].remain_quantity+'</td>'+
                            '<td>'+result[index].Item_remarks+'</td>'+
                            '<td>'+result[index].CreatedOn+'</td>'+
                            '<td><input type="checkbox" '+((index == 0 )?'checked':'')+' class="form-control cb seleted_item'+index+'" name="selected_items[]"></td>'+
                            '<td><input class="form-control item_quantity'+index+'" readonly type="number" placeholder="Quantity" value='+((index == 0 )?approve_quantity:'')+' name="selected_items_quantity[]"></td>'+
                            '<td style="display: none"><input class="donation'+index+'" value="'+result[index].donation_id+'" type="hidden"></td>'+
                            '<td style="display: none"><input class="item_id'+index+'" value="'+result[index].Item_id+'" type="hidden"></td>'+
                            '</tr>';
                           
                    });                   
                    $('#issue_details tbody').html(tr);
                    $('.cb:checked').trigger("change");
                }
            });
                    $(this).css("background","green");
                    $(this).css("display", "none");                    

        });
        $.ajax({
            url:'<?php echo base_url()?>Store/items/get_donation',
            success:function (response) {
                var donation = "";
                var result = JSON.parse(response);
                $.each(result,function (index) {
                    $('.donationtype').append("<option value='"+result[index].Id+"'>"+result[index].Donation_Type+"</option>");
                });
            }
        });
        $('select').select2();
    });
    $('#myModal').modal({
        backdrop: 'static',
        keyboard: false
    });
</script>
