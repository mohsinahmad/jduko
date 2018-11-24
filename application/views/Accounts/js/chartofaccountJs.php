<script type="text/javascript">
    var A_Edit_id;
    $('.a_Edit').on('click',function(){
        A_Edit_id = $(this).data('id');
        // if(A_Edit_id.length >= 21){
        //     $('#gridSystemModal').find('select #category').val('1').attr("disabled",true);
        // }
        $.ajax({
            type:'GET',
            url:'<?php echo site_url('Accounts/ChartOfAccounts/getEditAccount') ?>'+'/'+A_Edit_id,
            returnType:'JSON',
            success:function(response){
                var data = $.parseJSON(response);
                if(data.child == 1){
                    $('#category').attr("disabled","true");
                }

                $('#parentCode').val(data.parent_code);
                $('#parentName').attr("disabled","true");
                $('#parentCode').attr("disabled","true");
                $('#category').attr("disabled","true");

                if(data.parent_code == '0'){
                    $('#parentName').val('');
                    $('#category').attr("disabled","true");
                    $('.coaDelete').hide();
                }else{
                    $('#parentName').val(data.parent_name);
                    $('.coaDelete').show();
                }

                $('#type').attr("disabled","true");
                $('#type').val(data.type).attr("selected");
                $('#accountCode').val(data.account_code);
                $('#accountCode').attr("disabled","true");
                $('#accountName').val(data.account_name);
                $('#head').val(data.head).attr("selected");
                $('#head').attr("disabled","true");

                if(data.category == '2'){
                    $('#category').val(data.category).attr("selected");
                    $('.insertNewCOA').hide();
                }
                else if(data.category == '1' || $('#category').val() == '1'){
                    $('#type').val(data.type).attr("selected");
                    $('#type').attr("disabled","true");
                    $('#category').val(data.category).attr("selected");
                    $('.insertNewCOA').show();
                }else{
                    $('#type').val('7').removeAttr("selected");
                }
                $('.coaSave').hide();
            }
        });
    });

    $('.a_Edit8').on('click',function(){
        A_Edit_id = $(this).data('id');
        $('#gridSystemModal').find('select #category').val('1').attr("disabled",true);
        $.ajax({
            type:'GET',
            url:'<?php echo site_url('Accounts/ChartOfAccounts/getEditAccount'); ?>'+'/'+A_Edit_id,
            returnType:'JSON',
            success:function(response){
                var data = $.parseJSON(response);
                $('#parentCode').val(data.parent_code);
                $('#parentName').val(data.parent_name);
                $('#accountCode').val(data.account_code);
                $('#parentName').attr("disabled","true");
                $('#parentCode').attr("disabled","true");
                $('#type').attr("disabled","true");
                $('#accountName').val(data.account_name);
                $('#head').val(data.head).attr("selected");
                $('#category').attr("disabled","true");
                $('#accountCode').attr("disabled","true");
                $('#head').attr("disabled","true");
                $('#category').val(data.category).attr("selected");
                if(data.category == '1'){
                    $('#type').val(data.category).attr("selected");
                    $('#type').attr("disabled","true");
                }else{
                    $('#type').val(data.type).attr("selected");
                }
                $('.insertNewCOA').hide();
                $('.coaSave').hide();
            }
        });
    });

    $('#category').on('change',function(){
        var cat = $(this).val();
        if(cat == '1'){
            $('#type').attr("disabled","true");
            $('#type').val("7").attr("selected");
            $('.insertNewCOA').hide();
        }else{
            $('#type').removeAttr("disabled");
            $('.insertNewCOA').show();
        }
    });

    $('.insertNewCOA').on('click',function(){
        $('#category').removeAttr("disabled");
        $('#accountCode').removeAttr("disabled");
        $('.insertNewCOA').attr("disabled","true");
        $('.coaDelete').attr("disabled","true");
        var cat = $('#category').val();
        if(cat == '1'){
            $('#type').attr("disabled","true");
            $('.insertNewCOA').hide();
        }else{
            $('#type').removeAttr("disabled");
            $('.insertNewCOA').show();
        }
        $.ajax({
            type:'GET',
            url:'<?php echo site_url('Accounts/ChartOfAccounts/newAccount'); ?>'+'/'+A_Edit_id,
            returnType:'JSON',
            success:function(response){
                var data = $.parseJSON(response);
                var code = data.new_ID.toString();
                newCode = code.substr(-2);
                if(newCode == '00'){
                    new PNotify({
                        title: 'انتباہ',
                        text: "یہ اکاؤنٹ مکمل ہے اس کے مزید اکاؤنٹ نہیں بن سکتے-",
                        type: 'error',
                        delay: 1500,
                        hide: true
                    });

                    $('.coaSave').show();
                    $('.coaSave').attr("disabled","true");
                    $('.coaEdit').toggle();
                    $('.insertNewCOA').toggle();
                    $('.coaDelete').toggle();
                    $('#accountName').attr("disabled","true");
                    $('#accountCode').attr("disabled","true");
                    $('#category').attr("disabled","true");
                }else{
                    $('#category').removeAttr("disabled");
                    $('#accountName').removeAttr("disabled");
                    $('#accountCode').removeAttr("disabled");
                    $('#parentCode').val(data.parent_ID);
                    $('#parentCode').attr("disabled","true");
                    $('#accountCode').val(data.new_ID);
                    $('#accountName').val('');
                    $('#parentName').val(data.parent_Name);
                    $('#parentName').attr("disabled","true");
                    $('.coaSave').show();
                    $('#head').val(data.head).attr("selected");
                    $('#head').attr("disabled");
                    $('#category').val('1').attr("selected");
                    $('#type').val('7').attr("selected");
                    $('#type').val('7').removeAttr("selected");
                    $('.coaEdit').toggle();
                    $('.insertNewCOA').toggle();
                    $('.coaDelete').toggle();
                }
            }
        });
    });

    $('.coaSave').on('click',function(){
        var post = new Object();
        post.ParentCode = $('#parentCode').val();
        post.AccountCode = $('#accountCode').val();
        post.AccountName = $('#accountName').val();
        post.Head = $('#head').val();
        post.Category = $('#category').val();
        post.Type = $('#type').val();
        if(post.ParentCode.length >= 1){
            if(post.AccountCode.length > 5 && post.AccountCode.length < 7){
                new PNotify({
                    title: 'انتباہ',
                    text: "اکاؤنٹ کوڈ درست نہیں ہے",
                    type: 'error',
                    delay: 1500,
                    hide: true
                });
            }
            else if(post.AccountCode.length > 7 && post.AccountCode.length < 9){
                new PNotify({
                    title: 'انتباہ',
                    text: "اکاؤنٹ کوڈ درست نہیں ہے",
                    type: 'error',
                    delay: 1500,
                    hide: true
                });
            }
            else if(post.AccountCode.length > 9 && post.AccountCode.length < 11){
                new PNotify({
                    title: 'انتباہ',
                    text: "اکاؤنٹ کوڈ درست نہیں ہے",
                    type: 'error',
                    delay: 1500,
                    hide: true
                });
            }else if(post.AccountCode.length > 11 && post.AccountCode.length < 13){
                new PNotify({
                    title: 'انتباہ',
                    text: "اکاؤنٹ کوڈ درست نہیں ہے",
                    type: 'error',
                    delay: 1500,
                    hide: true
                });
            // }else if(post.AccountCode.length > 12 && post.AccountCode.length < 14){
            //     new PNotify({
            //         title: 'انتباہ',
            //         text: "اکاؤنٹ کوڈ درست نہیں ہے",
            //         type: 'error',
            //         delay: 1500,
            //         hide: true
            //     });
            // }else if(post.AccountCode.length > 14 && post.AccountCode.length < 16){
            //     new PNotify({
            //         title: 'انتباہ',
            //         text: "اکاؤنٹ کوڈ درست نہیں ہے",
            //         type: 'error',
            //         delay: 1500,
            //         hide: true
            //     });
            }else if(post.AccountName == ""){
                new PNotify({
                    title: 'انتباہ',
                    text: "اکاؤنٹ کا نام درکار ہے",
                    type: 'error',
                    delay: 1500,
                    hide: true
                });
            }else if(post.AccountCode == "" || post.AccountCode.length > 13 ){
                new PNotify({
                    title: 'انتباہ',
                    text: "اکاؤنٹ کوڈ کی ضرورت ہے اور 13 سے زیادہ نہیں ہونا چاہئے",
                    type: 'error',
                    delay: 1500,
                    hide: true
                });
            }else if(post.AccountName.length > 50){
                new PNotify({
                    title: 'انتباہ',
                    text: "اکاؤنٹ نام 50 حروف سے کم ہونا چاہئے",
                    type: 'error',
                    delay: 1500,
                    hide: true
                });
            }else if(post.Category == ""){
                new PNotify({
                    title: 'انتباہ',
                    text: "اکاؤنٹ کی قسم کی ضرورت ہے",
                    type: 'error',
                    delay: 1500,
                    hide: true
                });
            }else if(post.Type == ""){
                new PNotify({
                    title: 'انتباہ',
                    text: "اکاونٹ کی قسم شامل کرنا ضروری ھے",
                    type: 'error',
                    delay: 1500,
                    hide: true
                });
            }else{
                $.ajax({
                    type:'POST',
                    url:'<?php echo site_url('Accounts/ChartOfAccounts/SaveNewAccount'); ?>',
                    returnType:'JSON',
                    data:post,
                    success:function(response){
                        var data = $.parseJSON(response);
                        if(data['success']){
                            new PNotify({
                                title: 'کامیابی',
                                text: 'اکاؤنٹ کامیابی سے شامل ھو گیا',
                                type: 'success',
                                hide: true
                            });
                            setTimeout(function(){
                                window.location.href = "<?php echo site_url('Accounts/ChartOfAccounts') ?>";
                            },1500);
                        }
                    },
                    error:function(){
                        new PNotify({
                            title: 'انتباہ',
                            text: 'اکاؤنٹ آئی ڈی پہلے سے موجود کوئ اور کوشش کریں',
                            type: 'error',
                            hide: true
                        });
                        setTimeout(function(){
                            location.reload();
                        },1500);
                    }
                });
            }
        }
    });

    $('.coaEdit').on('click',function(){
        var post = new Object();
        post.ParentCode = $('#parentCode').val();
        post.AccountCode = $('#accountCode').val();
        post.AccountName = $('#accountName').val();
        post.Head = $('#head').val();
        post.Category = $('#category').val();
        post.Type = $('#type').val();

        if(post.AccountName == ""){
            new PNotify({
                title: 'انتباہ',
                text: "اکاؤنٹ کا نام درکار ہے",
                type: 'error',
                delay: 1500,
                hide: true
            });
        }else if(post.AccountCode == "" || post.AccountCode.length > 13 ){
            new PNotify({
                title: 'انتباہ',
                text: "اکاؤنٹ کوڈ کی ضرورت ہے اور 13 سے زیادہ نہیں ہونا چاہئے",
                type: 'error',
                delay: 1500,
                hide: true
            });
        }else if(post.AccountName.length > 50){
            new PNotify({
                title: 'انتباہ',
                text: "لیول نام 50 حروف سے کم ہونا چاہئے",
                type: 'error',
                delay: 1500,
                hide: true
            });
        }else if(post.Category == ""){
            new PNotify({
                title: 'انتباہ',
                text: "اکاؤنٹ کی قسم کی ضرورت ہے",
                type: 'error',
                delay: 1500,
                hide: true
            });
        }else if(post.Type == ""){
            new PNotify({
                title: 'انتباہ',
                text: "اکاونٹ کی قسم شامل کرنا ضروری ھے",
                type: 'error',
                delay: 1500,
                hide: true
            });
        }else{
            $.ajax({
                type:'POST',
                url:'<?php echo site_url('Accounts/ChartOfAccounts/EditAccount'); ?>'+'/'+A_Edit_id,
                returnType:'JSON',
                data:post,
                success:function(response){
                    var data = $.parseJSON(response);
                    if(data['success']){
                        new PNotify({
                            title: 'کامیابی',
                            text: 'اکاؤنٹ تدوین کامیاب',
                            type: 'success',
                            hide: true
                        });
                        setTimeout(function(){
                            location.reload();
                        },1500);
                    }
                }
            });
        }
    });

    $('.coaDelete').on('click',function(){

        (new PNotify({
            title: 'تصدیق درکار',
            text: 'کیا آپ حذف کرنا چاہتے ہیں؟',
            icon: 'glyphicon glyphicon-question-sign',
            hide: false,
            type: "success",
            confirm: {
                confirm: true,
                buttons: [{text: "جی ہاں", addClass: "", promptTrigger: true, click: function(notice, value){ notice.remove(); notice.get().trigger("pnotify.confirm", [notice, value]); }},{text: "نیہں", addClass: "", click: function(notice){ notice.remove(); notice.get().trigger("pnotify.cancel", notice); }}]
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
        })).get().on('pnotify.confirm', function(){

            $.ajax({
                url:'<?php echo site_url('Accounts/ChartOfAccounts/DeleteAccount'); ?>'+'/'+A_Edit_id,
                returnType:'JSON',
                success:function(response){
                    var data = $.parseJSON(response);
                    if(data['success']){
                        new PNotify({
                            title: 'کامیابی',
                            text: 'اکاؤنٹ حذف کامیاب',
                            type: 'success',
                            hide: true
                        });
                        setTimeout(function(){
                            location.reload();
                        },2500);
                    }
                    if(data['error']){
                        new PNotify({
                            title: 'انتباہ',
                            text: data['message'],
                            type: 'error',
                            delay: 3000,
                            hide: true
                        });
                        setTimeout(function(){
                            location.reload();
                        },1500);
                    }
                    if(data['link']){
                        new PNotify({
                            title: 'انتباہ',
                            text: "اکاونٹ حذف کرنے سے پہلے اسکا ربط حذف کریں",
                            type: 'error',
                            delay: 3000,
                            hide: true
                        });
                        setTimeout(function(){
                            location.reload();
                        },2500);
                    }
                }
            });
        }).on('pnotify.cancel', function(){
        });
    });

    $('#close').on('click',function(){
        setTimeout(function(){
            location.reload();
        });
    });
</script>

<!--       ************************** LINKS ********************* -->

<script>
    $(document).ready(function(){
        $.ajax({
            type:'GET',
            url:'<?php echo site_url('Accounts/Link/getLastInserted') ?>',
            success:function(response){
                var data = $.parseJSON(response);
                $('#comm').text(data.AccountName);
                $('#comm1').text(data.AccountID);
            }
        });
    });

    $('.get').on('click',function(){
        var id = $(this).data('id');
        $.ajax({
            type:'GET',
            url:'<?php echo site_url('Accounts/Link/getdata'); ?>'+'/'+id,
            returnType:'JSON',
            success:function(response){
                var data = $.parseJSON(response);
                $('#chart').append($(this).text())
                    .text(data.account_name);
                $('#chart1').append($(this).text())
                    .text(id);
            }
        });
    });

    $('.a_get').on('click',function(){
        var id = $(this).data('id');
        $(this).addClass('mycalss');
        $.ajax({
            type:'GET',
            url:'<?php echo site_url('Accounts/Link/get_c_data'); ?>'+'/'+id,
            returnType:'JSON',
            success:function(response){
                var data = $.parseJSON(response);
                $('#comm').text($(this).text())
                    .text(data.level_name);
                $('#comm1').text($(this).text())
                    .text(id);
            }
        });
    });

    $('.delete_data').on('click',function(){
        var id=$(this).data('id');
        (new PNotify({
            title: 'تصدیق درکار',
            text: 'کیا آپ حذف کرنا چاہتے ہیں؟',
            icon: 'glyphicon glyphicon-question-sign',
            hide: false,
            type: "success",
            confirm: {
                confirm: true,
                buttons: [{text: "جی ہاں", addClass: "", promptTrigger: true, click: function(notice, value){ notice.remove(); notice.get().trigger("pnotify.confirm", [notice, value]); }},{text: "نیہں", addClass: "", click: function(notice){ notice.remove(); notice.get().trigger("pnotify.cancel", notice); }}]
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
        })).get().on('pnotify.confirm', function(){

            $.ajax({
                url:'<?php echo site_url('Accounts/Link/DeleteLink'); ?>'+'/'+id,
                returnType:'JSON',
                success:function(response){
                    var data = $.parseJSON(response);
                    if(data['success']){
                        new PNotify({
                            title: 'حذف',
                            text: data['message'],
                            type: 'success',
                            hide: true
                        });
                        setTimeout(function(){
                            location.reload();
                        },0.5);
                    }
                }
            });
        }).on('pnotify.cancel', function(){
        });
    });

    $('.edit_data').on('click',function(e){
        e.preventDefault();
        var id = $(this).data('id');
        var active = $(this).data('value');
        $.ajax({
            type:'POST',
            url:'<?php echo site_url('Accounts/Link/EditLink');?>'+'/'+id,
            data:{'Active':active},
            success:function(response){
                var data = $.parseJSON(response);
                if(data['success']){
                    new PNotify({
                        title: 'کامیابی',
                        text: 'اکاؤنٹ تدوین کامیاب',
                        type: 'success',
                        hide: true
                    });
                    setTimeout(function(){
                        location.reload();
                    },500);
                }
            }
        });
    });

    $(document).ready(function(){
        $("#accountCode").keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                // Allow: Ctrl/cmd+A
                (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                // Allow: Ctrl/cmd+C
                (e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
                // Allow: Ctrl/cmd+X
                (e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
                // Allow: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                // let it happen, don't do anything
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
    });

    $(document.body).on('hidden.bs.modal', function () {
        $('.insertNewCOA').removeAttr('disabled');
        $('.coaEdit').removeAttr('disabled');
        $('.coaEdit').removeAttr('style');
        $('.coaSave').removeAttr('disabled');
        $('.coaDelete').removeAttr('disabled');
    });

</script>