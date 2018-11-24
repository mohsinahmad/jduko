<script type="text/javascript">
    //$('#checkdonation').hide();
    var data_post = new Object();    
    var data_arr = new Array();
    $('#ItemsubCategory').attr('readonly',true);
    $('#ItemCategory').change(function () {
        $('#ItemsubCategory').empty();
        var ItemCategory = $('#ItemCategory').val();
        $.ajax({
            type:'GET',
            url:'<?= site_url('Store/Category/GetSubCategory');?>/'+ItemCategory,
            success:function (response) {
                var data = $.parseJSON(response);
                if (data == '') {
                    $('#ItemsubCategory').attr('readonly',true);
                    $('#ItemsubCategory').append($('<option/>', {
                        value: 0,
                        text : 'منتخب کریں'
                    }).attr('readonly',true).attr('selected',true));
                }else{
                    $('#ItemsubCategory').removeAttr('readonly');
                    $('#ItemsubCategory').append($('<option/>', {
                        value: 0,
                        text : 'منتخب کریں'
                    }).attr('readonly',true).attr('selected',true));
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

function isEmptyObject(obj) {
  return JSON.stringify(obj) == '{}';
}
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
        // console.log(clone);
        select.select2({
            placeholder: "منتخب کریں",
            dir: "rtl"
        });
        clone.insertAfter('div.checkdonation:last').find('.js-example-basic-single').select2({
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
//        alert($(this).attr('unit'));
//        alert($(this).attr('donation'));
        var unit = $(this).attr('unit');
        var donation = $(this).attr('donation');
        $.ajax({
            type: 'POST',
            data:{unit:unit,donation:donation},
            url: '<?= site_url('Store/items/get_items_details'); ?>'+'/'+id,
//            returnType: 'JSON',
            success:function (response) {
//                console.log(response);
// currently workin is bein gon this form
                $('#item-edit tbody').html(response);
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
       console.log(data_arr);
       data_arr.shift();
        var id = $(this).data('id');
        $.ajax({
            type:'get',
            url:'<?= site_url('Store/ItemIssue/getDemandItems');?>'+'/'+id,
            success:function(response){
//                console.log(response);
                $('.DemandItemsData').html(response);
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
           // $('#copy').modal('show');
        }
    });

    $('.Save').on('click',function(){
        var val  = $('.cb:checked').parent('td').parent('tr').children('td').eq(6).find('input[type=number]').val();
        if($('.cb:checkbox:checked').length == 0 || val == ''){
            new PNotify({
                title: '',
                text: 'کم سے کم ایک تفصیل منتخب کریں اور مقدار انٹر کریں',
                type: 'error',
                hide: true
            });
        }
        else {
            var issue_post = new Object();
            
            var tr = $('#issue_details tbody tr').length;
            var total_quantity = 0;
            issue_post.QuantityArr = new Array();
            issue_post.IDArr = new Array();
            issue_post.donation = new Array();
            issue_post.detail_id = new Array();
            issue_post.item_id = new Array();
            issue_post.Demand_id = $('.demand_id').val();
            // alert(post.Demand_id);
            // post.Approved_id = $('.approved_ID').val();
            // post.DateG = $('.englishDate').val();
            // post.DateH = $('.islamicDate').val();
            // post.Remarks = $('#Details').val();
            // post.donation = $('.donationtype').val();
//       debugger;
            for(i=0; i < tr; i++){
                var detal_id = ($('.detail'+i).val() != undefined)?$('.detail'+i).val():0;
                var id = $('.seleted_item'+i+':checked').parent('td').parent('tr').data('id');
                var quantity = $('.seleted_item'+i+':checked').parent('td').parent('tr').children('td').eq(6).find('input').val();
                issue_post.IDArr.push(id);
                issue_post.QuantityArr.push(quantity);
                if(typeof quantity != 'undefined') {
                    total_quantity += parseInt(quantity);
                }
                var donation = $('.seleted_item'+i+':checked').parent('td').parent('tr').children('td').eq(7).find('.donation'+i).val();
                issue_post.donation.push(donation);
                issue_post.detail_id.push(detal_id);
                var items = $('.seleted_item'+i+':checked').parent('td').parent('tr').children('td').eq(8).find('.item_id'+i).val();
                issue_post.item_id.push(items);
            }

            if( total_quantity > approve_quantity ){
                setTimeout(function() {
                    new PNotify({
                        title: 'ناکام',
                        text: 'اجرا کردہ کّل مقدار منظور کردہ مقدار سے زیادہ ہے',
                        type: 'error',
                        hide: true
                    });
                }, 1000);
            }
            else{
                data_arr.push(issue_post);
                // console.log(data_arr);
                $('#detail_modal').modal('hide');
            }
        }
    });


    
    $('.demand').on('change','.cb',function () {
            if ($(this).prop('checked')) {
              $(this).parent('td').parent('tr').children('td').eq(6).find('input[type=number]').removeAttr('readonly');
                $('.Issue').removeAttr('disabled');
            }
            else {
                $(this).parent('td').parent('tr').children('td').eq(6).find('input[type=number]').attr('readonly',true);
                $('.Issue').attr('disabled',true);
            }
     });

    $('.Issue').on('click',function(){
        var demand_id = $('.detail').parent('td').parent('tr').find('.demand_id').val();
        $.ajax({
            type: 'POST',
            url: '<?= site_url('Store/ItemIssue/SaveIssueItem'); ?>',
            data: {"points" : JSON.stringify(data_arr),'d_id':demand_id},
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

function isEmpty(obj) {
    return Object.keys(obj).every(k => !Object.keys(obj[k]).length)
}
</script>