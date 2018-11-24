<script type="text/javascript">
	$('.delete_type').on('click',function(){
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
                url:'<?php echo site_url('Store/DonationType/DeleteDonationType'); ?>'+'/'+id,
                returnType:'JSON',
                success:function(response){
                    var data = $.parseJSON(response);
                    if(data['success']){
                        new PNotify({
                            title: 'حذف',
                            text: 'عطیہ حذف کر دیا گیا ہے',
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
                            text: 'عطیہ حذف نیہں ہو سکتا ہے',
                            type: 'error',
                            hide: true
                        });
                        setTimeout(function(){
                            location.reload();
                        },1000);
                    }
                }
            });
        }).on('pnotify.cancel', function(){
        });
    });




    $('.delete_unit').on('click',function(){
      //alert();
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
                url:'<?php echo site_url('Store/Unit_of_measure/Delete_unit'); ?>'+'/'+id,
                returnType:'JSON',
                success:function(response){
                    var data = $.parseJSON(response);
                    if(data['success']){
                        new PNotify({
                            title: 'حذف',
                            text: 'پیمائش کی اکائی حذف کر دی گئی ہے',
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
                            text: 'پیمائش کی اکائی حذف نیہں ہو سکتی ہے',
                            type: 'error',
                            hide: true
                        });
                        setTimeout(function(){
                            location.reload();
                        },1000);
                    }
                }
            });
        }).on('pnotify.cancel', function(){
        });
    });












	var id;
	$('.getid').on('click',function(){
		id=$(this).data('id');
		$.ajax({
			type: 'POST',
            url: '<?php echo site_url('Store/DonationType/DonationTypeById'); ?>'+'/'+id,
            returnType: 'JSON',
            success:function (response) {
            	var data = $.parseJSON(response);
            	$('#donation').val(data.Donation_Type);
            }
		});
	});

    $('.get_unit_id').on('click',function(){
        id=$(this).data('id');
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url('Store/unit_of_measure/unitById'); ?>'+'/'+id,
            returnType: 'JSON',
            success:function (response) {
                var data = $.parseJSON(response);
                //alert(data.unit_of_measure);
                $('#_unit').val(data.unit_of_measure);
            }
        });
    });

    $('.donationtEdit').on('click',function(){
        var post = new Object();
        post.Dtype = $('#donation').val();
         $.ajax({
             type: 'POST',
             url: '<?php echo site_url('Store/DonationType/UpdateDonationType'); ?>'+'/'+id,
             returnType: 'JSON',
             data: post,
             success: function (response) {
                 if (response['success']) {
                     new PNotify({
                         title: 'کامیابی',
                         text: 'edit ho gya ha',
                         type: 'success',
                         hide: true
                     });
                 }
                 setTimeout(function() {
                     window.location.reload();
                 }, 1000);
             }
         });
    });

    $('.unittoEdit').on('click',function(){
        var post = new Object();
        post.unit = $('#_unit').val();
         $.ajax({
             type: 'POST',
             url: '<?php echo site_url('Store/unit_of_measure/update_unit'); ?>'+'/'+id,
             returnType: 'JSON',
             data: post,
             success: function (response) {
                 if (response['success']) {
                     new PNotify({
                         title: 'کامیابی',
                         text: 'edit ho gya ha',
                         type: 'success',
                         hide: true
                     });
                 }
                 setTimeout(function() {
                     window.location.reload();
                 }, 1000);
             }
         });
    });
</script>