<script type="text/javascript">
    //$('#checkdonation').hide();

    $('#ItemsubCategory').attr('disabled',true);
    $('#ItemCategory').change(function () {

        $('#ItemsubCategory').empty();
        var ItemCategory = $('#ItemCategory').val();
        $.ajax({
            type:'GET',
            url:'<?= site_url('Store/Category/GetSubCategory');?>/'+ItemCategory,
            success:function (response) {
                var data = $.parseJSON(response);
                if (data == '') {
                    $('#ItemsubCategory').attr('disabled',true);
                    $('#ItemsubCategory').append($('<option/>', {
                        value: 0,
                        text : 'منتخب کریں'
                    }).attr('disabled',true).attr('selected',true));
                }else{
                    $('#ItemsubCategory').removeAttr('disabled');
                    $('#ItemsubCategory').append($('<option/>', {
                        value: 0,
                        text : 'منتخب کریں'
                    }).attr('disabled',true).attr('selected',true));
                    $.each(data, function (index, value) {
                        $('#ItemsubCategory').append($('<option/>', {
                            value: value['Id'],
                            text : value['Name']
                        }));
                    });
                }
            }
        });
    });

    $('.ItemsubCategory').attr('disabled',true);
    $('.ItemCategory').change(function () {

        $('.ItemsubCategory').empty();
        var ItemCategory = $('.ItemCategory').val();
        $.ajax({
            type:'GET',
            url:'<?= site_url('Store/Category/GetSubCategory');?>/'+ItemCategory,
            success:function (response) {
                var data = $.parseJSON(response);
                if (data == '') {
                    $('.ItemsubCategory').attr('disabled',true);
                    $('.ItemsubCategory').append($('<option/>', {
                        value: 0,
                        text : 'منتخب کریں'
                    }).attr('disabled',true).attr('selected',true));
                }else{
                    $('.ItemsubCategory').removeAttr('disabled');
                    $('.ItemsubCategory').append($('<option/>', {
                        value: 0,
                        text : 'منتخب کریں'
                    }).attr('disabled',true).attr('selected',true));
                    $.each(data, function (index, value) {
                        $('.ItemsubCategory').append($('<option/>', {
                            value: value['Id'],
                            text : value['Name']
                        }));
                    });
                }
            }
        });
    });

    // $('#quantity_check').click(function() {
    //     $('#checkdonation').toggle();
    // });

    var select = $('.js-example-basic-single');
    $('.edit').on('click',function(){
        var div = $('#checkdonation');
        select.select2("destroy");
        var clone = div.clone(true).find('input[type=number]').val("").end();
        clone.find('label').hide();
        select.select2({
            placeholder: "منتخب کریں",
            dir: "rtl"
        });
        clone.insertAfter('div#checkdonation:last').find('.js-example-basic-single').select2({
            placeholder: "منتخب کریں",
            dir: "rtl"
        });
    });

    $('.del').on('click',function(){
        var div = $(this);
        lengthClass = $('.checkdonation').length;

        if (lengthClass === 1) {
            new PNotify({
                title: "ہوشیار",
                text: "Cannot remove the only remaining field!",
                type: 'error',
                hide: true
            });
        }else{
            // (new PNotify({
            //     title: 'تصدیق درکار',
            //     text: 'کیا آپ حذف کرنا چاہتے ہیں؟',
            //     icon: 'glyphicon glyphicon-question-sign',
            //     hide: false,
            //     type: "warning",
            //     confirm: {
            //         confirm: true,
            //         buttons: [{text: "جی ہاں", addClass: "", promptTrigger: true, click: function(notice, value){ notice.remove(); notice.get().trigger("pnotify.confirm", [notice, value]); }},{text: "نیہں", addClass: "", click: function(notice){ notice.remove(); notice.get().trigger("pnotify.cancel", notice); }}]
            //     },
            //     buttons: {
            //         closer: false,
            //         sticker: false
            //     },
            //     history: {
            //         history: false
            //     },
            //     addclass: 'stack-modal',
            //     stack: {'dir1': 'down', 'dir2': 'right', 'modal': true}
            // })).get().on('pnotify.confirm', function(){
            //     div.closest('#checkdonation').remove();
            //     new PNotify({
            //         title: "حذف",
            //         text: "حذف کامیاب",
            //         type: 'success',
            //         hide: true
            //     });
            // }).on('pnotify.cancel', function(){
            //     new PNotify({
            //         title: "Cancelled",
            //         text: "Your field is safe :)",
            //         type: 'error',
            //         hide: true
            //     });
            // });
            div.closest('#checkdonation').remove();
            new PNotify({
                title: "حذف",
                text: "حذف کامیاب",
                type: 'success',
                hide: true
            });
        }
    });

    var page = '<?= $this->uri->segment(2);?>';
    $(document).on('ready',function() {
        if (page == 'ItemIssue'){
            $('.Issue').attr('disabled','true');
            $('#fancy-checkbox-success').attr('disabled','true');
            var date = $('.englishDate').val();
            $.ajax({
                type:"GET",
                url:'<?= site_url('Accounts/Books/getDate');?>'+'/'+date,
                success:function(response){
                    var data = $.parseJSON(response);
                    $('.islamicDate').val(data.date);
                }
            });
        }
    });

    if (page == 'ItemIssue'){
        $('.englishDate').on('change',function(){
            var date = $(this).val();
            $.ajax({
                type:"GET",
                url:'<?= site_url('Accounts/Books/getDate');?>'+'/'+date,
                success:function(response){
                    var data = $.parseJSON(response);
                    $('.islamicDate').val(data.date);
                }
            });
        });
    }

    $('.delete_item').on('click',function(e){
        e.preventDefault();
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
                url:'<?= site_url('Store/items/DeleteItem'); ?>'+'/'+id,
                returnType:'JSON',
                success:function(response){
                    var data = $.parseJSON(response);
                    if(data['success']){
                        new PNotify({
                            title: 'حذف',
                            text: 'ٹراسیکشن حذف کامیاب',
                            type: 'success',
                            hide: true
                        });
                        setTimeout(function(){
                            location.reload();
                        },0.5);
                    }else if(data['userecord']){
                        new PNotify({
                            title: 'غلطی',
                            text: 'Cannot Deleted Due To Some Error',
                            type: 'error',
                            hide: true
                        });
                    }else{
                        new PNotify({
                            title: 'غلطی',
                            text: 'Cannot Deleted Due To Some Error',
                            type: 'error',
                            hide: true
                        });
                    }
                }
            });
        }).on('pnotify.cancel', function(){
        });
    });

    var id;
    $('.getid').on('click',function(){
        id = $(this).data('id');
        $.ajax({
            type: 'POST',
            url: '<?= site_url('Store/items/ItemById'); ?>'+'/'+id,
            returnType: 'JSON',
            success:function (response) {
                var data = $.parseJSON(response);
                $('.ItemsubCategory').removeAttr("disabled");
                $('#Code').val(data[0].code);
                $('#Name').val(data[0].name);
                $('.unit_of_measure').val(data[0].unit_of_measure);
                $('.ItemCategory').val(data[0].Name).attr("selected");
                $('.ItemsubCategory').empty();
                var ItemCategory = $('.ItemCategory').val();

                $.ajax({
                    type:'GET',
                    url:'<?= site_url('Store/Category/GetSubCategory');?>/'+ItemCategory,
                    success:function (response) {
                        var data = $.parseJSON(response);
                        if (data == '') {
                            $('.ItemsubCategory').attr('disabled',true);
                            $('.ItemsubCategory').append($('<option></option>', {
                                value: 0,
                                text : 'منتخب کریں'
                            }).attr('disabled',true).attr('selected',true));
                        }else{
                            $('.ItemsubCategory').removeAttr('disabled');
                            $('.ItemsubCategory').append($('<option></option>', {
                                value: 0,
                                text : 'منتخب کریں'
                            }));
                            $.each(data, function (index, value) {
                                $('.ItemsubCategory').append($('<option></option>', {
                                    value: value['Id'],
                                    text : value['Name']
                                }));
                            });
                        }
                    }
                }).done(function(){
                    if(data.ItemsubCategory == ''){
                        $('.ItemsubCategory').val('0').attr("selected");
                    }else{
                        $('.ItemsubCategory').val(data.ItemsubCategory).attr("selected");
                    }
                });
                $.each(data, function (index, value) {
                    $('.donationtypes')
                        .append('<tr class="donations"><td><select name="DonationType[]" class="form-control js-example-basic-single" id="DonationType" style="padding-bottom:0px;padding-top:0px;width:150px;" required><option value="" selected>'+value.Donation_Type+'</option></select></td><td><input class="form-control" name="" value="'+value.opening_quantity+'"></td><td><input class="form-control" name="" value="'+value.current_quantity+'"></td><td><button type="button" class="btn btn-info btn-circle del" ><i class="fa fa-trash-o"></i></button><button type="button" class="btn btn-info btn-circle modalclone" id="toEdit" ><i class="fa fa-plus"></i></button></td></tr>');
                });
            }
        });
    });

    $('.update_item').on('click',function(){
        var post = new Object();

        post.code = $('#Code').val();
        post.name = $('#Name').val();
        post.category_id = $('.ItemCategory').val();
        post.subCategory_id = $('.ItemsubCategory').val();
        post.unit_of_measure = $('.unit_of_measure').val();
        post.opening_quantity = $('.OpeningQuanity').val();
        post.current_quantity = $('.CurrentQuanity').val();
        post.donation_type = $('.DonationType').val();
        $.ajax({
            type: 'POST',
            url: '<?= site_url('Store/items/UpdateItems'); ?>'+'/'+id,
            returnType: 'JSON',
            data: post,
            success: function (response) {
                var data = $.parseJSON(response);
                if (data['success']) {
                    new PNotify({
                        title: 'کامیابی',
                        text: 'تدوین کامیاب',
                        type: 'success',
                        hide: true
                    });
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                }
            }
        });
    });

    $('.DemandItems').on('click',function(){
        //alert('asd');
        var id = $(this).data('id');
        $.ajax({
            type:'get',
            url:'<?= site_url('Store/ItemIssue/getDemandItems');?>'+'/'+id,
            success:function(response){
                console.log(response);
                $('.DemandItemsData').html(response);
                $('.Issue').removeAttr('disabled');
                $('#fancy-checkbox-success').removeAttr('disabled');
            }
        });
    });

    $('.acceptedQuantity').on('keypress',function(e){
        if(e.which == 13){
            id = $(this).data('id');

        }
    });

    $('.Issue').on('click',function(){
        var is_error = 0;
        var quantity;
        var DemandQ;
        var CurrentQ;
        var tr = $('.toEdit').length;
        for(i=1; i <= tr; i++){
            quantity = $('.accepted'+i).val();
            DemandQ = $('.DemandQuantity'+i).html();
            CurrentQ = $('.CurrentQuantity'+i).html();
            if(parseInt(quantity) > parseInt(DemandQ)){
                var item = $('.ItemName'+i).html();
                new PNotify({
                    title: 'انتباع',
                    text: item + 'کی اجرا  مقدار ڈیمانڈ سے زیادہ ہے',
                    type: 'error',
                    hide: true
                });
                is_error = 1;
            }else if(parseInt(quantity) > parseInt(CurrentQ)){
                new PNotify({
                    title: 'انتباع',
                    text: 'اجرا مقدارموجودہ مقدار سے زیادہ ہے',
                    type: 'error',
                    hide: true
                });
                is_error = 1;
            }
        }

        if(!is_error){
            $('#copy').modal('show');
        }
    });

    $('.Save').on('click',function(){
        var tr = $('.toEdit').length;
        var post = new Object();
        post.QuantityArr = new Array();
        post.ItemArr = new Array();
        post.Demand_id = $('.demand_id').val();
        post.Approved_id = $('.approved_ID').val();
        post.DateG = $('.englishDate').val();
        post.DateH = $('.islamicDate').val();
        post.Remarks = $('#Details').val();

        for(i=1; i <= tr; i++){
            var item_id = $('.Item_Id'+i).val();
            post.ItemArr.push(item_id);
            var ApproveQ = $('.ApproveQuantity'+i).html();
            post.QuantityArr.push(ApproveQ);
        }
        console.log(post);
        $.ajax({
            type: 'POST',
            url: '<?= site_url('Store/ItemIssue/SaveIssueItem'); ?>',
            returnType: 'JSON',
            data: post,
            success: function (response) {
                var data = $.parseJSON(response);
                if (data['success']) {
                    new PNotify({
                        title: 'کامیابی',
                        text: 'اشیاء کا اجراء ہوگیا!',
                        type: 'success',
                        hide: true
                    });
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                }
            }
        });
    });

    $(".newOpeningQuanity").keyup(function(){
        var quantity = $(this).parent('div').parent('div');
        quantity.siblings('div').find('.newCurrentQuanity').val($(this).val());
    });

</script>