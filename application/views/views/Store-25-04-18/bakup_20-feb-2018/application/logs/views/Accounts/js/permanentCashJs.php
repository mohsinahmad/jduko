<script type="text/javascript">        // if(!$('.checkbox').is(':checked')){        //     // alert("adsd");        //     $('.copyTemp').attr("disabled","true");        //     $('.copyTemp').parents('a').addClass('not-active');        //  }    $('.searchPer').on('click',function(){        var post = new Object();        post.to = $('#to').val();        post.from = $('#from').val();        post.AccountCode = $('.AccountCode').val();        if (!post.AccountCode) {            $.ajax({                type:'POST',                url:'<?php echo site_url('Accounts/Books/getPermanentTranByDate');?>'+'/'+'<?php echo $this->uri->segment(4) ?>'+'/'+'<?php echo $this->uri->segment(5)?>',                data:post,                success:function (response) {                    $('.percashbookTable').html(response);                }            });        }else{            $.ajax({                type:'POST',                url:'<?php echo site_url('Accounts/Books/getPerTransByDateAndAccountCode');?>'+'/'+'<?php echo $this->uri->segment(4) ?>'+'/'+'<?php echo $this->uri->segment(5)?>',                data:post,                success:function (response) {                    $('.percashbookTable').html(response);                    dataTable = $('#dataTables-example').DataTable();                     dataTable.search(post.AccountCode).draw();                }            });        }    });    $('.AccountCodePer').on('keyup',function(){        var code = $(this).val();        if(code == ""){            $.ajax({                type:'GET',                url:'<?php echo site_url('Accounts/Books/getAllPer');?>'+'/'+'<?php echo $this->uri->segment(4) ?>'+'/'+'<?php echo $this->uri->segment(5)?>',                success:function(response){                    $('.percashbookTable').html(response);                    dataTable = $('#dataTables-example').DataTable();                     dataTable.search('').draw();                }            });        }else{            $.ajax({                type:'GET',                url:'<?php echo site_url('Accounts/Books/getPerTransByAccountCode');?>'+'/'+code+'/'+'<?php echo $this->uri->segment(4) ?>'+'/'+'<?php echo $this->uri->segment(5)?>',                success:function(response){                    $('.percashbookTable').html(response);                    dataTable = $('#dataTables-example').DataTable();                     dataTable.search(code).draw();                }            });        }    });    $('.PervoucherNo').on('keyup',function(){        var code = $(this).val();        if(code == ""){            $.ajax({                type:'GET',                url:'<?php echo site_url('Accounts/Books/getAllPer');?>'+'/'+'<?php echo $this->uri->segment(4) ?>'+'/'+'<?php echo $this->uri->segment(5)?>',                success:function(response){                    $('.percashbookTable').html(response);                    dataTable = $('#dataTables-example').DataTable();                     dataTable.search('').draw();                }            });        }else{            $.ajax({                type:'GET',                url:'<?php echo site_url('Accounts/Books/getByPerVoucherNo');?>'+'/'+code+'/'+'<?php echo $this->uri->segment(4) ?>'+'/'+'<?php echo $this->uri->segment(5)?>',                success:function(response){                    $('.percashbookTable').html(response);                    //var v= $(".search-input-text").val();                    dataTable = $('#dataTables-example').DataTable();                     dataTable.search(code).draw();                }            });        }    });    $("#select_all").change(function(){  //"select all" change         $(".checkbox").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status        if($(this).is(':checked')){            $('.copyTemp').removeAttr("disabled");            $('.copyTemp').parents('a').removeClass('not-active');        }else{            $('.copyTemp').attr("disabled","true");            $('.copyTemp').parents('a').addClass('not-active');        }    });    //".checkbox" change     $('.checkbox').change(function(){        if($(this).is(':checked')){            $('.copyTemp').removeAttr("disabled");            $('.copyTemp').parents('a').removeClass('not-active');        }else{            $('.copyTemp').attr("disabled","true");            $('.copyTemp').parents('a').addClass('not-active');        }        //uncheck "select all", if one of the listed checkbox item is unchecked        if(false == $(this).prop("checked")){ //if this item is unchecked            $("#select_all").prop('checked', false); //change "select all" checked status to false        }        //check "select all" if all checkbox items are checked        if ($('.checkbox:checked').length == $('.checkbox').length ){            $("#select_all").prop('checked', true);        }    });</script>