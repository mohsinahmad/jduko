<script type="text/javascript">
    $(document).ready(function(){
        var now = new Date();
        var day = ("0" + now.getDate()).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);
        var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
        if ($('#url').val() != 'EditTransaction'){
            $('.englishDate').val(today);
        }
    });

    if ($('#url').val() == 'AddTransaction' || $('#url').val() == 'EditTransaction') {
        $(document).on('ready',function(){
            var date = $('.englishDate').val();
            $.ajax({
                type:"GET",
                url:'<?php echo site_url('Accounts/Books/getDate');?>'+'/'+date,
                success:function(response){
                    var data = $.parseJSON(response);
                    $('.islamicDate').val(data.date);
                }
            });
        });

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

    $('#accountName').on('change',function(){
        var accountId = $(this).val();
        var div = $('#accoundCode');
        $.ajax({
            type:"POST",
            url:'<?php echo site_url('Accounts/Books/getAccountCode');?>'+'/'+accountId,
            success:function(response){
                $('.dataEdit').removeAttr("disabled");
                //var data = $.parseJSON(response);
                $('#accountCode').val(response._id);
                $('select').trigger('change.select2');
                if(response._type == 2){
                    $('#chequeData').show();
                }else{
                    $('#chequeData').hide();
                }
            }
        });
    });

    $('#EditaccountName').on('change',function(){
        var accountId = $(this).val();
        var div = $('#accoundCode');
        $.ajax({
            type:"POST",
            url:'<?php echo site_url('Accounts/Books/getAccountCode');?>'+'/'+accountId,
            success:function(response){
                //var data = $.parseJSON(response);
                $('#EditaccountCode').val(response._id);
                $('select').trigger('change.select2');
                if(response._type == 2){
                    $('#chequeData').show();
                }else{
                    $('#chequeData').hide();
                }
            }
        });
    });

    $('#accountCode').on('change',function(){

        var accountId = $(this).val();

        $.ajax({

            type:"POST",

            url:'<?php echo site_url('Accounts/Books/getAccountCode');?>'+'/'+accountId,

            success:function(response){
                $('.dataEdit').removeAttr("disabled");
                //var data = $.parseJSON(response);

                $('#accountName').val(response._id);

                $('select').trigger('change.select2');

                if(response._type == 2){

                    $('#chequeData').show();

                }else{

                    $('#chequeData').hide();

                }

            }

        });

    });

    $('#EditaccountCode').on('change',function(){

        var accountId = $(this).val();

        $.ajax({

            type:"POST",

            url:'<?php echo site_url('Accounts/Books/getAccountCode');?>'+'/'+accountId,

            success:function(response){

                //var data = $.parseJSON(response);

                $('#EditaccountName').val(response._id);

                $('select').trigger('change.select2');

                if(response._type == 2){

                    $('#chequeData').show();

                }else{

                    $('#chequeData').hide();

                }

            }

        });

    });



    // $('#datetimepicker1').on('change',function(){

    //     var date = $(this).val();

    //     $.ajax({

    //         type:"POST",

    //         url:'<?php echo site_url('Accounts/Books/getDate');?>',

    //         data:{'date':date},

    //         success:function(response){

    //             var data = $.parseJSON(response);

    //             $('#EislamicDate').val(data.date);

    //         }

    //     });

    // });



    $('#company').on('change',function(){

        var id = $(this).val();

        $('#account').empty();

        $.ajax({

            type:"GET",

            url:'<?php echo site_url('Accounts/Books/getAccount');?>'+'/'+id,

            success:function(response){

                var data = $.parseJSON(response);

                $.each(data,function(ind,val){

                    $('#account').append($("<option></option>")

                        .attr("value",val.acc_id)

                        .text(val.AccountName));

                });

            }

        });

    });



    $('#accountId').on('change',function(){

        var id = $(this).val();

        $.ajax({

            type:'GET',

            url:'<?php echo site_url('Accounts/Books/getAccountBalance') ?>'+'/'+id+'/'+'<?php echo $this->uri->segment(4) ?>',

            success:function(response){

                var data = $.parseJSON(response);

                $("#currentBalance").val(data.currBal);

            }

        });

    });



    $('.bsaveTransaction').on('click',function(){

        var post = new Object();

        post.bookId = $('#bookId').val();

        post.companyId = $('#companyId').val();

        post.paidTo = $('#paidTo').val();

        post.voucherCode = $('#voucherCode').val();

        post.accountId = $('#accountId').val();

        post.currentBalance = $('#currentBalance').val();

        post.englishDate = $('.englishDate').val();

        post.islamicDate = $('#islamicDate').val();

        post.recieved = $('#recieved').val();

        post.payment = $('#payment').val();

        post.details = $('#details').val();

        post.chequeno = $('#chequeno').val();

        post.chequedate = $('#chequedate').val();



        $.ajax({

            type:'POST',

            url:'<?php echo site_url('Accounts/Books/savebankTransaction');?>',

            data:post,

            success:function(response){

                var data = $.parseJSON(response);

                if(data['success']){

                    new PNotify({

                        title: 'کامیابی',

                        text: 'ٹراسیکشن کامیابی سے ہوگئ',

                        type: 'success',

                        hide: true

                    });

                    setTimeout(function(){

                        location.reload();

                    },2000);

                }

                if(data['error']){

                    new PNotify({

                        title: 'کامیابی',

                        text: 'Error',

                        type: 'error',

                        hide: true

                    });

                }

                if(data['Rerror']){

                    new PNotify({

                        title: 'کامیابی',

                        text: 'recieved Error',

                        type: 'error',

                        hide: true

                    });

                }

                if(data['Perror']){

                    new PNotify({

                        title: 'کامیابی',

                        text: 'Payment Error',

                        type: 'error',

                        hide: true

                    });

                }

                if(data['form_error']){

                    $('.accId').text(data.accountId);

                    $('.engDate').text(data.englishDate);

                    $('.islDate').text(data.islamicDate);

                    $('.Recieved').text(data.recieved);

                    $('.Payment').text(data.payment)

                }

            }

        });

    });



    var tran_id;

    $('.edit_transaction').on('click',function() {

        tran_id = $(this).data('id');

        $.ajax({

            type:'GET',

            url:'<?php echo site_url('Accounts/Books/getEditTransactions');?>'+'/'+tran_id,

            success:function(response){

                var data = $.parseJSON(response);

                $('#EcompanyId').val(data.LevelID).attr("selected");

                $('#EpaidTo').val(data.PaidTo);

                $('#EvoucherCode').val(data.AccountCode);

                $('#EaccountId').val(data.AccountID).attr("selected");

                $('#EaccountId').attr("disabled","true");

                $('#EcurrentBalance').val(data.CurrentBalance);

                $('.EenglishDate').val(data.EnglishDate);

                $('#EislamicDate').val(data.IslamicDate);

                if(data.Debit == null){

                    $('#Erecieved').attr("disabled","true");

                }else{

                    $('#Erecieved').val(data.Debit);

                }

                if(data.Credit == null){

                    $('#Epayment').attr("disabled","true");

                }else{

                    $('#Epayment').val(data.Credit);

                }

                $('#Edetails').val(data.Details);

            }

        });

    });



    $('.bsaveEditTransaction').on('click',function(){

        var post = new Object();

        post.paidTo = $('#EpaidTo').val();

        post.englishDate = $('.EenglishDate').val();

        post.islamicDate = $('#EislamicDate').val();

        post.recieved = $('#Erecieved').val();

        post.payment = $('#Epayment').val();

        post.details = $('#Edetails').val();

        post.chequeno = $('#chequeno').val();

        post.chequedate = $('#chequedate').val();



        $.ajax({

            type:'POST',

            url:'<?php echo site_url('Accounts/Books/saveBankEditTransaction');?>'+'/'+tran_id,

            data:post,

            success:function(response){

                var data = $.parseJSON(response);

                if(data['success']){

                    new PNotify({

                        title: 'کامیابی',

                        text: 'ٹرانزیکشن تصیح کامیاب',

                        type: 'success',

                        hide: true

                    });

                    setTimeout(function(){

                        location.reload();

                    },2000);

                }

                if(data['error']){

                    new PNotify({

                        title: 'کامیابی',

                        text: 'Error',

                        type: 'error',

                        hide: true

                    });

                }

                if(data['Rerror']){

                    new PNotify({

                        title: 'کامیابی',

                        text: 'recieved Error',

                        type: 'error',

                        hide: true

                    });

                }

                if(data['Perror']){

                    new PNotify({

                        title: 'کامیابی',

                        text: 'Payment Error',

                        type: 'error',

                        hide: true

                    });

                }

            }

        });

    });



    // $('.bdeleteTransaction').on('click',function(){

    //    (new PNotify({

    //               title: 'تصدیق درکار',

    //               text: 'کیا آپ حذف کرنا چاہتے ہیں؟',

    //               icon: 'glyphicon glyphicon-question-sign',

    //               hide: false,

    //               type: "success",

    //               confirm: {

    //                   confirm: true,

    //                   buttons: [{

    //                       text: "ٹھیک ہے", addClass: "", promptTrigger: true, click: function (notice, value) {

    //                           notice.remove();

    //                           notice.get().trigger("pnotify.confirm", [notice, value]);

    //                       }

    //                   }, {

    //                       text: "منسوخ", addClass: "", click: function (notice) {

    //                           notice.remove();

    //                           notice.get().trigger("pnotify.cancel", notice);

    //                       }

    //                   }]

    //               },

    //               buttons: {

    //                   closer: false,

    //                   sticker: false

    //               },

    //               history: {

    //                   history: false

    //               },

    //               addclass: 'stack-modal',

    //               stack: {'dir1': 'down', 'dir2': 'right', 'modal': true}

    //           })

    //       ).get().on('pnotify.confirm', function () {



    //           $.ajax({

    //               url: '<?php echo site_url('Accounts/Books/deleteBankTransaction'); ?>' + '/' + tran_id,

    //               success: function (response) {

    //                   var data = $.parseJSON(response);

    //                   if (data['success']) {

    //                       new PNotify({

    //                           title: 'حذف',

    //                           text: "ٹراسیکشن حذف کامیاب",

    //                           type: 'success',

    //                           hide: true

    //                       });

    //                   }

    //                   setTimeout(function () {

    //                       location.reload();

    //                   }, 1000);

    //               }

    //           });

    //       }).on('pnotify.cancel', function () {



    //       });

    // });



    // $('.acc').on('click',function(e){

    //     e.preventDefault();

    //     alert($(this).data('id'));

    // });



    $('.voucherNo').on('keyup',function(){

        var code = $(this).val();

        if(code == ""){

            $.ajax({

                type:'GET',

                url:'<?php echo site_url('Accounts/Books/getAll');?>'+'/'+'<?php echo $this->uri->segment(4) ?>'+'/'+'<?php echo $this->uri->segment(5)?>',

                success:function(response){

                    $('.cashbookTable').html(response);

                }

            });



        }else{

            $.ajax({

                type:'GET',

                url:'<?php echo site_url('Accounts/Books/getByVoucherNo');?>'+'/'+code+'/'+'<?php echo $this->uri->segment(4) ?>'+'/'+'<?php echo $this->uri->segment(5)?>',

                success:function(response){

                    $('.cashbookTable').html(response);

                }

            });

        }

    });
</script>
<script type="text/javascript" src="<?php echo base_url()."assets/"; ?>js/moment.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()."assets/"; ?>js/daterangepicker.js"></script>
<script type="text/javascript">

    $(function() {
//     $('input[name="daterange"]').daterangepicker({

//     "startDate": moment(),

//     "endDate": moment(),

//     }, function(start, end) {

//         $('#to').val(start.format('YYYY-MM-DD'));

//         $('#from').val(end.format('YYYY-MM-DD'));



//     });

// });

// $('#daterange').on('apply.daterangepicker', function(ev, picker) {

//     var to = picker.startDate.format('YYYY-M-D');

//     var from = picker.endDate.format('YYYY-M-D');

//     $.ajax({

//         type:'POST',

//         url:'<?php echo site_url('Accounts/Books/getTransactionByDate');?>',

//         data:{'to':to,"from":from},

//         success:function (response) {

//             $('.cashbookTable').html(response);

//         }

//     });

// });

        $("#select_all").change(function(){  //"select all" change

            $(".checkbox").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status

            if($(this).is(':checked')){

                $('.copyTemp').removeAttr("disabled");

                $('.copyTemp').parents('a').removeClass('not-active');

            }else{

                $('.copyTemp').attr("disabled","true");

                $('.copyTemp').parents('a').addClass('not-active');

            }



        });



        //".checkbox" change

        $('.checkbox').change(function(){

            if($(this).is(':checked')){

                $('.copyTemp').removeAttr("disabled");

                $('.copyTemp').parents('a').removeClass('not-active');

            }else{

                $('.copyTemp').attr("disabled","true");

                $('.copyTemp').parents('a').addClass('not-active');

            }

            //uncheck "select all", if one of the listed checkbox item is unchecked

            if(false == $(this).prop("checked")){ //if this item is unchecked

                $("#select_all").prop('checked', false); //change "select all" checked status to false

            }

            //check "select all" if all checkbox items are checked

            if ($('.checkbox:checked').length == $('.checkbox').length ){

                $("#select_all").prop('checked', true);

            }

        });

        $('.search').on('click',function(){

            var post = new Object();

            post.to = $('#to').val();

            post.from = $('#from').val();

            post.AccountCode = $('.AccountCode').val();

            if (!post.AccountCode) {

                $.ajax({

                    type:'POST',

                    url:'<?php echo site_url('Accounts/Books/getTransactionByDate');?>'+'/'+'<?php echo $this->uri->segment(4) ?>'+'/'+'<?php echo $this->uri->segment(5)?>',

                    data:post,

                    success:function (response) {

                        $('.cashbookTable').html(response);

                    }

                });

            }else{

                $.ajax({

                    type:'POST',

                    url:'<?php echo site_url('Accounts/Books/getTransactionByDateAndAccountCode');?>'+'/'+'<?php echo $this->uri->segment(4) ?>'+'/'+'<?php echo $this->uri->segment(5)?>',

                    data:post,

                    success:function (response) {

                        $('.cashbookTable').html(response);

                    }

                });

            }

        });



        $('.AccountCode').on('keyup',function(){

            var code = $(this).val();

            if(code == ""){

                $.ajax({

                    type:'GET',

                    url:'<?php echo site_url('Accounts/Books/getAll');?>'+'/'+'<?php echo $this->uri->segment(4) ?>'+'/'+'<?php echo $this->uri->segment(5)?>',

                    success:function(response){

                        $('.cashbookTable').html(response);

                    }

                });



            }else{

                $.ajax({

                    type:'GET',

                    url:'<?php echo site_url('Accounts/Books/getByAccountCode');?>'+'/'+code+'/'+'<?php echo $this->uri->segment(4) ?>'+'/'+'<?php echo $this->uri->segment(5)?>',

                    success:function(response){

                        $('.cashbookTable').html(response);

                    }

                });

            }

        });

    });

</script>