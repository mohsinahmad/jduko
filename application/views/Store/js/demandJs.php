<script type="text/javascript">
    var page = '<?= $this->uri->segment(3);?>';
    $(document).on('ready',function() {
        if (page == 'AddForm' || page == 'FormEdit' ||  page == 'ResturnForm' || page == 'ReturnEdit'){
            var date = $('.englishDate').val();
            $.ajax({
                type:"GET",
                url:'<?php echo site_url('Accounts/Books/getDate');?>'+'/'+date,
                success:function(response){
                    var data = $.parseJSON(response);
                    $('.islamicDate').val(data.date);
                }
            });
        }

    });
    if (page == 'AddForm' || page == 'FormEdit' || page == 'ResturnForm' || page == 'ReturnEdit'){
        $('.englishDate').on('change',function(){
            var date = $(this).val();
            $.ajax({
                type:"GET",
                url:'<?php echo site_url('Accounts/Books/getDate');?>'+'/'+date,
                success:function(response){
                    var data = $.parseJSON(response);
                    $('.islamicDate').val(data.date);
            }
            });
        });
    }

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

</script>