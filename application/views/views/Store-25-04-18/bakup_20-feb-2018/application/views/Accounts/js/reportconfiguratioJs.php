<script type="text/javascript">
	$('.delete_report').on('click',function(){
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
                url:'<?php echo site_url('Accounts/ReportConfigurations/DeleteReport'); ?>'+'/'+id,
                returnType:'JSON',
                success:function(response){
                    var data = $.parseJSON(response);
                    if(data['success']){
                        new PNotify({
                            title: 'حذف',
                            text: 'رپورٹ حذف کر دی گیا ہے',
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

	var id;
	$('.getid').on('click',function(){
		id=$(this).data('id');
		$.ajax({
			type: 'POST',
            url: '<?php echo site_url('Accounts/ReportConfigurations/ReportNameById'); ?>'+'/'+id,
            returnType: 'JSON',
            success:function (response) {
            	var data = $.parseJSON(response);
            	$('#ReportName').val(data.ReportName);
            }
		});
	});

    $('.reportEdit').on('click',function(){
        var post = new Object();
        post.Rname = $('#ReportName').val();
         $.ajax({
             type: 'POST',
             url: '<?php echo site_url('Accounts/ReportConfigurations/UpdateReport'); ?>'+'/'+id,
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