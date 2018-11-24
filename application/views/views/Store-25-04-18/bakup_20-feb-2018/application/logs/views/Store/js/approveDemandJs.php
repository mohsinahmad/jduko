<script type="text/javascript">
    var page = '<?= $this->uri->segment(3);?>';
    $(document).on('ready',function() {
        $('.ApproveDemand').attr('disabled','true');
        $('#fancy-checkbox-success').attr('disabled','true');
        //if (page == 'AddForm' || page == 'FormEdit' ||  page == 'ResturnForm' || page == 'ReturnEdit'){
        var date = $('.englishDate').val();
        $.ajax({
            type:"GET",
            url:'<?php echo site_url('Accounts/Books/getDate');?>/'+'/'+date,
            success:function(response){
                var data = $.parseJSON(response);
                $('.islamicDate').val(data.date);
            }
        });
        ///}

    });
    //if (page == 'AddForm' || page == 'FormEdit' || page == 'ResturnForm' || page == 'ReturnEdit'){
    $('.englishDate').on('change',function(){
        var date = $(this).val();
        $.ajax({
            type:"GET",
            url:'<?php echo site_url('Accounts/Books/getDate');?>/'+'/'+date,
            success:function(response){
                var data = $.parseJSON(response);
                $('.islamicDate').val(data.date);
            }
        });
    });
    // }

    $('tbody.checkrow tr.ReturnForm input').on('keyup',function(){

        var currentRow = $(this).closest("tr");
        var col1=currentRow.find(".Item_Quantity").val();
        var col2=currentRow.find(".use_quantity").val();
        var total = col1-col2;
        if(total < 0 ){
            new PNotify({
                title: "Oops...",
                text: "ve value ho rhi ha -",
                type: 'error',
                hide: true
            });
        }else{
            currentRow.find('.remaining').val(total);
        }
    });

    $('.DemandItems').on('click',function(){
        var id = $(this).data('id');
        $.ajax({
            type:'get',
            url:'<?= site_url('Store/ApproveDemand/getApproveDemandItems');?>'+'/'+id,
            success:function(response){
                $('.DemandItemsData').html(response);
                var Total_Items = $('.Items').length;
                $('.Total_Items').val(Total_Items);
                $('.ApproveDemand').removeAttr('disabled');
                $('#fancy-checkbox-success').removeAttr('disabled');
            }
        });
        var form_num = $(this).find('td.FormNumber').html();
        $('.item-heading').html('');
        $('.item-heading').html('اشیاء  از ڈیمانڈ فارم نمبر ۔'+form_num);
    });

    $('.ApproveDemand').on('click',function(){

    });
</script>