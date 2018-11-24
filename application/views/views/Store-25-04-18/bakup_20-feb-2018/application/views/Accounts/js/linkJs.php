<script type="text/javascript">
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

        var rights = $('#rights').val();
        if (rights == '11' || <?= $_SESSION['user'][0]->id ?> == 1){   //changing
            $('#userInput').show();
        }else {
            $('#userInput').hide();
        }
    });

    $('.get').on('click',function(){

        var id = $(this).data('id');
        var name_arr = [];
        var code_arr = [];
        $.ajax({
            type:'GET',
            url:'<?php echo site_url('Accounts/Link/getdata'); ?>'+'/'+id,
            returnType:'JSON',
            success:function(response){
                var data = $.parseJSON(response);
                name_arr.push(data.account_name);
                code_arr.push(data.account_code);

                $('#chart').append(name_arr+'،');
                $('#chart1').append(code_arr+',');
            }
        });
    });


    $('#chart').on('change',function(){
        var code = $(this).val();
        $('#chart1').text(code);
    });

    <?php if (isset($_SESSION['comp_id'])){
        $Access_level = $_SESSION['comp_id'];
    }elseif (isset($_SESSION['comp'])){
        $Access_level = $_SESSION['comp'];
    }else{ $Access_level = ''; } ?>

    $('.a_get').on('click',function(){
        var code = $(this).data('id');
        $(this).addClass('mycalss');
        $.ajax({
            type:'GET',
            url:'<?php echo site_url('Accounts/Link/get_c_data'); ?>'+'/'+code,
            returnType:'JSON',
            success:function(response){
                var data = $.parseJSON(response);
                var access_level = '<?= $Access_level ?>';   //changing
                if (data.id == access_level || <?= $_SESSION['user'][0]->IsAdmin?> == 1){
                    $('#comm').text($(this).text())
                        .text(data.level_name);
                    $('#comm1').text($(this).text())
                        .text(code);
                }else{
                    new PNotify({
                        title: 'توجہ فرمائیں',
                        text: 'از راہ کرم کمپنی منتخب کیجیئے ۔۔!!',
                        type: 'error',
                        hide: true
                    });
                }
            }
        });
    });

    $('.delete_data').on('click',function(){
        var id = $(this).data('id');
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
                    if(data['error']){
                        new PNotify({
                            title: 'حذف',
                            text: data['message'],
                            type: 'error',
                            hide: true
                        });
                        setTimeout(function(){
                            location.reload();
                        },2000);
                    }
                }
            });
        }).on('pnotify.cancel', function(){
        });
    });

    var coaId;
    $('.edit_data').on('click',function(){
        coaId = $(this).data('id');
        $.ajax({
            type:'GET',
            url:'<?php echo site_url('Accounts/Link/EditLink/');?>'+coaId,
            returnType:'JSON',
            success:function(response){
                var data = $.parseJSON(response);
                $('#coa').val(data.accountname);
                $('#comp').val(data.levelname);
                $('#OBalance').val(data.opening);
                $('#IsActive').val(data.active);
                $('#Series').val(data.Separate_Series);
                $('#sep_series').val(parseInt(data.maxSeparate_Series) + parseInt(1));

                if(data.active == 0){
                    $('#zero').hide();
                }else{
                    $('#one').hide();
                }if(data.Separate_Series == 0){
                    $('#Same_Series').hide();
                }else{
                    $('#Seprate_Series').hide();
                }
            }
        });
    });

    $('.linkupdate').on('click',function(){
        var post = new Object();
        post.OBalance = $('#OBalance').val();

        if($('.messageCheckbox:checked').val() == undefined){
            post.Active = $('#IsActive').val();
        }else{
            post.Active = $('.messageCheckbox:checked').val();
        }if($('.SeriesCheckbox:checked').val() == undefined){
            post.seprate = $('#Series').val();
        }else{
            post.seprate = $('.SeriesCheckbox:checked').val();
        }
        $.ajax({
            type: 'POST',
            url: '<?= site_url('Accounts/Link/updatelink/')?>'+coaId+'/'+post.OBalance+'/'+post.Active+'/'+post.seprate,
            returnType: 'JSON',
            data: post,
            success: function (response) {
                if (response['success']) {
                    new PNotify({
                        title: 'کامیابی',
                        text: data['message'],
                        type: 'success',
                        hide: true
                    });
                }
                setTimeout(function() {
                    window.location.reload();
                }, 1000);
                if (response['error']) {
                    new PNotify({
                        title: 'کامیابی',
                        text: data['message'],
                        type: 'error',
                        hide: true
                    });
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                }
            }
        });
    });

    $('.accountcode').on('keyup',function(){
        var code = $(this).val();
        if(code == ""){
            $.ajax({
                type:'GET',
                url:'<?php echo site_url('Accounts/Link/getAll');?>',
                success:function(response){
                    $('.linktable').html(response);
                }
            });
        }else{
            $.ajax({
                type:'GET',
                url:'<?php echo site_url('Accounts/Link/getByAccountCode');?>'+'/'+code,
                success:function(response){
                    $('.linktable').html(response);
                }
            });
        }
    });
</script>