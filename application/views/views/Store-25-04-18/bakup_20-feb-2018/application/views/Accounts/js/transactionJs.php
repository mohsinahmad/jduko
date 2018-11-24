<script type="text/javascript" src="<?php echo base_url()."assets/"; ?>js/moment.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()."assets/"; ?>js/daterangepicker.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        var now = new Date();

        var day = ("0" + now.getDate()).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);
        var today = now.getFullYear()+"-"+(month)+"-"+(day) ;

        if ($('#url').val() != 'EditTransaction'){
            $('.englishDate').val(today);
            $('.CenglishDate').val(today);
        }

        if(!$('.checkbox').is(':checked')){
            $('.copyTemp').attr("disabled","true");
            $('.copyTemp').parents('a').addClass('not-active');
            $('.copyIncTemp').attr("disabled","true");
            $('.copyIncTemp').parents('a').addClass('not-active');
        }
    });  //cashBook  C_Edit

    if ($('#url').val() == 'AddTransaction' || $('#url').val() == 'EditTransaction')
    {
        $(document).on('ready',function() {
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
            // var str = date;
            // var day = str.substr(0,2);
            // var month = str.substr(2,3);
            // var year = str.substr(6,4);
            // var finaldate = year + month +'-'+ day;
            //alert(finaldate);
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

    $('#accountName').on('change',function()
    {
        var accountId = $(this).val();
        $.ajax({
            type:"POST",
            url:'<?php echo site_url('Accounts/Books/getAccountCode');?>'+'/'+accountId,
            success:function(response){
                $('.dataEdit').removeAttr("disabled");
                $('#accountCode').val(response._id);
                $('select').trigger('change.select2');
                if(response._type == 2){
                    $('#chequeData').show();
                    $('#chequeData2').show();
                    $('.chequeData3').show();

                }else{

                    $('#chequeData').hide();
                    $('#chequeData2').hide();
                    $('.chequeData3').hide();
                }
            }
        });
    });
    $('#EditaccountName').on('change',function(){
        var accountId = $(this).val();
        $.ajax({
            type:"POST",
            url:'<?php echo site_url('Accounts/Books/getAccountCode');?>'+'/'+accountId,
            success:function(response){
                $('#EditaccountCode').val(response._id);
                $('select').trigger('change.select2');
                if(response._type == 2){
                    $('#chequeData').show();
                    $('#chequeData2').show();
                    $('.chequeData3').show();
                }else{
                    $('#chequeData').hide();
                    $('#chequeData2').hide();
                    $('.ch equeData3').hide();
                }
            }
        });
    });

    $('#accountCode').on('change',function(){
        var accountId = $(this).val();
        $.ajax({
            type: 'POST',
            dataType:  'json',
            url:'<?php echo site_url('Accounts/Books/getAccountCode');?>'+'/'+accountId,
            success:function(response){
                $('.dataEdit').removeAttr("disabled");
                $('#accountName').val(response._id);
                $('select').trigger('change.select2');
            }
        });
    });

    $('#EditaccountCode').on('change',function(){
        var accountId = $(this).val();
        $.ajax({
            type: 'POST',
            dataType:  'json',
            url:'<?php echo site_url('Accounts/Books/getAccountCode');?>'+'/'+accountId,
            success:function(response){
                $('#EditaccountName').val(response._id);
                $('select').trigger('change.select2');
            }
        });
    });

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

    var tran_id;

    $('.saveEditTransaction').on('click',function(){
        var post = new Object();
        post.DepartmentId=$('#departId').val();
        post.PaidTo = $('#EpaidTo').val();
        post.EnglishDate = $('.EenglishDate').val();
        post.IslamicDate = $('#EislamicDate').val();
        post.Remarks=$('#remarks').val();

        $.ajax({
            type:'POST',
            url:'<?php echo site_url('Accounts/Books/saveEditTransaction');?>'+'/'+tran_id,
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
            }
        });
    });

    $('.deleteTransaction').on('click',function(){
        (new PNotify({
                title: 'تصدیق درکار',
                text: 'کیا آپ حذف کرنا چاہتے ہیں؟',
                icon: 'glyphicon glyphicon-question-sign',
                hide: false,
                type: "success",
                confirm: {
                    confirm: true,
                    buttons: [{
                        text: "جی ہاں", addClass: "", promptTrigger: true, click: function (notice, value) {
                            notice.remove();
                            notice.get().trigger("pnotify.confirm", [notice, value]);
                        }
                    }, {
                        text: "نیہں", addClass: "", click: function (notice) {
                            notice.remove();
                            notice.get().trigger("pnotify.cancel", notice);
                        }
                    }]
                },
                buttons: {
                    closer: false,
                    sticker: false
                },
                history: {
                    history: false
                },
                addclass: 'stack-modal',
                stack: {'dir1': 'down', 'dir2': 'right', 'modal': true}
            })
        ).get().on('pnotify.confirm', function () {

            $.ajax({
                url: '<?php echo site_url('Accounts/Books/deleteTransaction'); ?>' + '/' + tran_id,
                success: function (response) {
                    var data = $.parseJSON(response);
                    if (data['success']) {
                        new PNotify({
                            title: 'حذف',
                            text: "ٹراسیکشن حذف کامیاب",
                            type: 'success',
                            hide: true
                        });
                    }
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                }
            });
        }).on('pnotify.cancel', function () {
        });
    });

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
                    dataTable = $('#dataTables-example').DataTable();
                    dataTable.search(code).draw();
                }
            });
        }
    });

    var t_id;
    $('.copy_transaction').on('click',function(){
        t_id = $(this).data('id');
       // alert(t_id);
        $.ajax({
            type:'GET',
            url:'<?php echo site_url('Accounts/Books/getMoveTransaction');?>'+'/'+t_id,
            success:function(response){
                var data = $.parseJSON(response);
                $('#voucher_no').text(data[0]._voucherType + '-' + data[0]._voucherNo);
                $('#t_id').val(data[0]._trans_id);
                $('#voucher_type').val(data[0]._voucherType);
                $('#vouch_no').val(data[0]._voucherNo);
                $('#Seprate_series_num').val(data[0]._Seprate_series_num);
                $('#level_id').val(data[0]._levelID);
                $.each(data, function( index, value) {
                    if (value._accountType == 2){
                        if (value._accountDebit == 0.00){
                            $('#bank_details').html('<div><div class="col-xs-6"><div class="form-group" style="width:250px;"><div class="form-group" id="accname"><input class="form-control" id="acc_name" type="text" value="'+value._accountName+'" readonly></div></div></div><div class="col-xs-6"><div class="form-group" id="accamount"><input class="form-control" id="amount" name="" style="width: 250px;"  type="text" value="کریڈٹ : '+value._accountCredit+'" readonly></div></div><div class="col-xs-6"><div class="form-group" style="width:250px;"><label>چیک نمبر</label><div class="form-group" id="bank_details_ChequeNumber"><input class="form-control ChequeNumber" type="text" name="ChequeNumber" id="ChequeNumber" value="'+ data[0]._ChequeNumber +'"></div></div></div><div class="col-xs-6"><label class="control-label" for="inputSuccess">چیک کی تاریخ</label><div class="form-group" id="bank_details_ChequeDate"><input class="form-control englishDate ChequeDate" id="ChequeDate" name="ChequeDate" style="width: 250px;"  type="date" value="'+ data[0]._ChequeDate +'"></div></div><input class="bt_id" name="bt_id[]" type="hidden" value="'+value._trans_id+'"><hr></div>');
                        }else {
                            $('#bank_details').html('<div><div class="col-xs-6"><div class="form-group" style="width:250px;"><div class="form-group" id="accname"><input class="form-control" id="acc_name" type="text" value="'+value._accountName+'" readonly></div></div></div><div class="col-xs-6"><div class="form-group" id="accamount"><input class="form-control" id="amount" name="" style="width: 250px;"  type="text" value="ڈیبٹ : '+value._accountDebit+'" readonly></div></div><div class="col-xs-6"><div class="form-group" style="width:250px;"><label>چیک نمبر</label><div class="form-group" id="bank_details_ChequeNumber"><input class="form-control ChequeNumber" type="text" name="ChequeNumber" id="ChequeNumber" value="'+ data[0]._ChequeNumber +'"></div></div></div><div class="col-xs-6"><label class="control-label" for="inputSuccess">چیک کی تاریخ</label><div class="form-group" id="bank_details_ChequeDate"><input class="form-control englishDate ChequeDate" id="ChequeDate" name="ChequeDate" style="width: 250px;"  type="date"  value="'+ data[0]._ChequeDate +'"></div></div><input class="bt_id" name="bt_id[]" type="hidden" value="'+value._trans_id+'"><hr></div>');
                        }
                    }
                });
            }
        });

        $('#copy').on('hidden.bs.modal', function () {
            $(this).find("input,textarea,select").val('').end();
        });

        var date = $('.englishDate').val();
        $.ajax({
            type:"GET",
            url:'<?php echo site_url('Accounts/Books/getDate');?>'+'/'+date,
            success:function(response){
                var data = $.parseJSON(response);
                $('.islamicDate').val(data.date);
            }
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

    });

    $('.copyTransaction').on('click',function(){

        var post = new Object();
        post.tran_id = $('#t_id').val();
        post.v_type = $('#voucher_type').val();
        post.v_no = $('#vouch_no').val();
        post.Seprate_series_num = $('#Seprate_series_num').val();
        post.level = $('#level_id').val();
        post.Edate = $('.CenglishDate').val();
        post.Idate = $('#CislamicDate').val();
        post.ChequeNumber = '';
        post.ChequeDate = '';
        post.bt_id = '';
        var ChequeNumber = [];
        var ChequeDate = [];
        var bt_id = [];
        $( ".ChequeNumber" ).each(function( index ) {
            ChequeNumber.push($(this).val());
        });

        $( ".ChequeDate" ).each(function( index ) {
            ChequeDate.push($(this).val());
        });
        $( ".bt_id" ).each(function( index ) {
            bt_id.push($(this).val());
        });

        post.ChequeNumber = ChequeNumber;
        post.ChequeDate = ChequeDate;
        post.bt_id = bt_id;
        $('.copyTransaction').attr('disabled',true);
        $.ajax({
            type:'POST',
            url:'<?php echo site_url('Accounts/Books/moveTransaction');?>'+'/'+t_id+'/'+post.v_type,
            data:post,
            success:function(response){
                var data = $.parseJSON(response);
                if(data['success']){
                    new PNotify({
                        title: 'کامیابی',
                        text: 'واؤچر  کامیابی  سے منتقل  ھو گیا ھے',
                        type: 'success',
                        hide: true
                    });
                    setTimeout(function(){
                        location.reload();
                    },2000);
                }
                if(data['error']){
                    new PNotify({
                        title: 'انتباہ',
                        text: 'واؤچر  منتقل  نیہں ہوا ہےف',
                        type: 'error',
                        hide: true
                    });
                    setTimeout(function(){
                        location.reload();
                    },2000);
                }
            }
        });
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
                    dataTable = $('#dataTables-example').DataTable();
                    dataTable.search(post.AccountCode).draw();
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
                    dataTable = $('#dataTables-example').DataTable();
                    dataTable.search(code).draw();
                }
            });
        }
    });

    $('.moveTemp').on('click',function(e){
        e.preventDefault();
        var keepVoucherNo = '';
        var post = new Object();
        post.data = { 'toTemp' : []};
        post.vouch_no = { 'VoucherNo' : []};
        $("input:checked").each(function() {
            post.data['toTemp'].push($(this).val());
            var voucher_no = $(this).siblings('input.vouch_no').val();
            post.vouch_no['VoucherNo'].push(voucher_no);
        });
        // var myCheckboxes = new Array();
        (new PNotify({
                title: 'تصدیق درکار',
                text: '  کیا آپ مستقل واؤچر نمبر  رکھنا  چاہتے ہو ؟',
                icon: 'glyphicon glyphicon-question-sign',
                hide: false,
                type: "success",
                confirm: {
                    confirm: true,
                    buttons: [{
                        text: "جی ہاں", addClass: "", promptTrigger: true, click: function (notice, value) {
                            notice.remove();
                            notice.get().trigger("pnotify.confirm", [notice, value]);
                        }
                    }, {
                        text: "جی نہیں", addClass: "", click: function (notice) {
                            notice.remove();
                            notice.get().trigger("pnotify.cancel", notice);
                        }
                    }]
                },
                buttons: {
                    closer: false,
                    sticker: false
                },
                history: {
                    history: false
                },
                addclass: 'stack-modal',
                stack: {'dir1': 'down', 'dir2': 'right', 'modal': true}
            })
        ).get().on('pnotify.confirm', function () {
            keepVoucherNo = 1;
            $.ajax({
                type:'POST',
                url:'<?php echo site_url('Accounts/Books/updatePermanentVoucher');?>'+'/'+'<?php echo $this->uri->segment(4) ?>'+'/'+keepVoucherNo+'/'+'<?php echo $this->uri->segment(5) ?>',
                data:post,
                success:function(response){
                    var data = $.parseJSON(response);
                    if(data['success']){
                        new PNotify({
                            title: 'کامیابی',
                            text: 'واؤچر  کامیابی  سے ٹرانسفر  ھو گیا ھے',
                            type: 'success',
                            hide: true
                        });
                        setTimeout(function(){
                            location.reload();
                        },2000);
                    }
                }
            });
        }).on('pnotify.cancel', function () {
            keepVoucherNo = 0;
            //alert(JSON.stringify(post));
            $.ajax({
                type:'POST',
                url:'<?php echo site_url('Accounts/Books/updatePermanentVoucher');?>'+'/'+'<?php echo $this->uri->segment(4) ?>'+'/'+keepVoucherNo+'/'+'<?php echo $this->uri->segment(5) ?>',
                data:post,
                success:function(response){
                    var data = $.parseJSON(response);
                    if(data['success']){
                        new PNotify({
                            title: 'کامیابی',
                            text: 'واؤچر  کامیابی  سے ٹرانسفر  ھو گیا ھے',
                            type: 'success',
                            hide: true
                        });
                        setTimeout(function(){
                            location.reload();
                        },2000);
                    }
                }
            });
        });
    });

    $('.copyTemp').on('click',function(e){
        $('.copyTemp').attr('disabled',true);
        e.preventDefault();

        // var myCheckboxes = new Array();
        var post = new Object();
        post.data = { 'copyToTemp' : []};
        post.vouch_no = { 'VoucherNo' : []};
        post.Seprate_series = { 'Seprate_series' : []};
        $("input:checked").each(function() {
            post.data['copyToTemp'].push($(this).val());
            var voucher_no = $(this).siblings('input.vouch_no').val();
            var Seprate_series_num = $(this).siblings('input.Seprate_series_num').val();
            post.vouch_no['VoucherNo'].push(voucher_no);
            post.Seprate_series['Seprate_series'].push(Seprate_series_num);
        });

        $.ajax({
            type:'POST',
            url:'<?= site_url('Accounts/Books/copyVoucher');?>'+'/'+'<?= $this->uri->segment(4) ?>'+'/'+'<?= $this->uri->segment(5) ?>',
            data:post,
            success:function(response){
                var data = $.parseJSON(response);
                if(data['success']){
                    new PNotify({
                        title: 'کامیابی',
                        text: 'واؤچر  کامیابی  سے کاپی  ھو گیا ھے',
                        type: 'success',
                        hide: true
                    });
                    setTimeout(function(){
                        window.location.href = "<?php echo site_url('Accounts/Books/AllBooks').'/'.$this->uri->segment(4).'/'.$this->uri->segment(5)?>";
                    },2000);
                }
            }
        });
    });

    $('.copyIncTemp').on('click',function(e){
        e.preventDefault();
        // var myCheckboxes = new Array();
        var post = new Object();
        post.data = { 'copyToTemp' : []};
        post.vouch_no = { 'VoucherNo' : []};
        $("input:checked").each(function() {
            post.data['copyToTemp'].push($(this).val());
            var voucher_no = $(this).siblings('input.vouch_no').val();
            post.vouch_no['VoucherNo'].push(voucher_no);
        });

        $.ajax({
            type:'POST',
            url:'<?php echo site_url('Accounts/Books/copyIncVoucher');?>'+'/'+'<?php echo $this->uri->segment(5) ?>',
            data:post,
            success:function(response){
                var data = $.parseJSON(response);
                if(data['success']){
                    new PNotify({
                        title: 'کامیابی',
                        text: 'واؤچر  کامیابی  سے کاپی  ھو گیا ھے',
                        type: 'success',
                        hide: true
                    });
                    setTimeout(function(){
                        window.location.href = "<?php echo site_url('Accounts/Books/AllBooks').'/'.$this->uri->segment(4).'/'.$this->uri->segment(5)?>";
                    },2000);
                }
            }
        });
    });

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

    $("#select_all").change(function(){  //"select all" change 
        $(".checkbox").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
        if($(this).is(':checked')){
            $('.copyIncTemp').removeAttr("disabled");
            $('.copyIncTemp').parents('a').removeClass('not-active');
        }else{
            $('.copyIncTemp').attr("disabled","true");
            $('.copyIncTemp').parents('a').addClass('not-active');
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

    $('.checkbox').change(function(){
        if($(this).is(':checked')){
            $('.copyIncTemp').removeAttr("disabled");
            $('.copyIncTemp').parents('a').removeClass('not-active');
        }else{
            $('.copyIncTemp').attr("disabled","true");
            $('.copyIncTemp').parents('a').addClass('not-active');
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
</script>