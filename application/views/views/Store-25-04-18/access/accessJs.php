<script type="text/javascript">    $('#feature').on('change',function(){        debugger;        var nid = $(this).val();        $.ajax({            type:'GET',            url:'<?php echo site_url('Access/getfeatureid') ?>'+'/'+nid,            success:function(response){                var data= $.parseJSON(response);                $('.typeall').show();                if(data.type == 1){                    $('.type1').show();                    $('.type0').hide();                }                if(data.type == 0){                    $('.type1').hide();                    $('.type0').show();                }                if(data.type == 2){                    $('.type1').show();                    $('.type0').show();                }            }        });    });    $( document ).ready(function() {        debugger;        var rights = $('#rights').val();        if (rights == '11' || <?= $_SESSION['user'][0]->IsAdmin?> == 1){            $('#userInput').show();        }else {            $('#userInput').hide();        }    });    $('.access').on('click',function(){        debugger;        var id = $(this).data('id');        $.ajax({            type:'GET',            url:'<?php echo site_url('Access/getdata'); ?>'+'/'+id,            returnType:'JSON',            success:function(response){                var data = $.parseJSON(response);                $('.userid').val(data.level_code);            }        });    });    $('.data-save').on('click',function(e){        debugger;        e.preventDefault();        var post = new Object();        post.UserID = $('#user_name').val();        post.LevelID = $('#levelId').val();        post.FeatureID = $('#feature').val();        var ac = $("input.raccess");        if (ac.is(":checked")) {            post.access = 1;        } else {            post.access = 0;        }        var ad = $("input.radd");        if (ad.is(":checked")) {            post.add = 1;        } else {            post.add = 0;        }        var ed = $("input.redit");        if (ed.is(":checked")) {            post.edit = 1;        } else {            post.edit = 0;        }        var de = $("input.rdelete");        if (de.is(":checked")) {            post.delete = 1;        } else {            post.delete = 0;        }        var vi = $("input.rview");        if (vi.is(":checked")) {            post.view = 1;        } else {            post.view = 0;        }        var pr = $("input.rprint");        if (pr.is(":checked")) {            post.print = 1;        } else {            post.print = 0;        }        $.ajax({            type:'POST',            url:'<?php echo site_url('Access/SaveDataForStore')?>',            data:post,            success:function(response){                var data = $.parseJSON(response);                if(data['success']){                    new PNotify({                        title: 'کامیابی',                        text: "حقوق کامیابی سےدرج ہوگئے",                        type: 'success',                        hide: true                    });                    setTimeout(function(){                        location.reload();                    },0.5);                }                if(data['emptyCheckbox']){                    new PNotify({                        title: 'ناکامی',                        text: "حق دینا ضروری ہے",                        type: 'error',                        hide: true                    });                }                if(data['error']){                    new PNotify({                        title: 'ناکامی',                        text:  "براہ مہربانی فیچر منتخب کریں",                        type: 'error',                        hide: true                    });                    setTimeout(function(){                        location.reload();                    },0.5);                }                if(data['match']){                    new PNotify({                        title: 'ناکامی',                        text: "حقوق موجود ہے",                        type: 'error',                        hide: true                    });                }            }        });    });    $('.delete_data').on('click',function(){        var id=$(this).data('id');        (new PNotify({            title: 'تصدیق درکار',            text: 'کیا آپ حذف کرنا چاہتے ہیں؟',            icon: 'glyphicon glyphicon-question-sign',            hide: false,            type: "success",            confirm: {                confirm: true,                buttons: [{text: "جی ہاں", addClass: "", promptTrigger: true, click: function(notice, value){ notice.remove(); notice.get().trigger("pnotify.confirm", [notice, value]); }},{text: "نیہں", addClass: "", click: function(notice){ notice.remove(); notice.get().trigger("pnotify.cancel", notice); }}]            },            buttons: {                closer: false,                sticker: false            },            history: {                history: false            },            addclass: 'stack-modal',            stack: {'dir1': 'down', 'dir2': 'right', 'modal': true}        })).get().on('pnotify.confirm', function(){            $.ajax({                url:'<?php echo site_url('Access/DeleteData'); ?>'+'/'+id,                returnType:'JSON',                success:function(response){                    var data = $.parseJSON(response);                    if(data['success']){                        new PNotify({                            title: 'حذف',                            text: data['message'],                            type: 'success',                            hide: true                        });setTimeout(function(){                            location.reload();                        },0.5);                    }                }            });        }).on('pnotify.cancel', function(){        });    });$("#select_all").change(function(){  //"select all" change        $(".all").prop('checked', $(this).prop("checked"));    });</script>