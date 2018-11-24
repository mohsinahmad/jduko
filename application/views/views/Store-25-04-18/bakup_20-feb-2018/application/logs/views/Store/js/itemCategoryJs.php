<script type="text/javascript">
	$('.delete_category').on('click',function(){
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
                url:'<?php echo site_url('Store/Category/DeleteCategory'); ?>'+'/'+id,
                returnType:'JSON',
                success:function(response){
                    var data = $.parseJSON(response);
                    if(data['success']){
                        new PNotify({
                            title: 'حذف',
                            text: 'حذف کامیاب',
                            type: 'success',
                            hide: true
                        });
                        setTimeout(function(){
                            location.reload();
                        },0.5);
                    }else if(data['has_category']){
                       new PNotify({
                            title: 'حذف',
                            text: 'حژف ناممکن ، کیٹیگری آئیٹم سے منسلک ہے۔',
                            type: 'error',
                            hide: true
                        });
                    }else{
                        new PNotify({
                            title: 'حذف',
                            text: 'حذف ناممکن ، سب کیٹیگری موجود ہے۔',
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
            url: '<?php echo site_url('Store/Category/CategoryById'); ?>'+'/'+id,
            returnType: 'JSON',
            success:function (response) {
            	var data = $.parseJSON(response);
                $('#Code').val(data.Code);
                $('#Category').val(data.Name);
            	$('#Parent').val(data.Parent_Name);
            }
		});
	});

    $('.update_category').on('click',function(){
        var post = new Object();
        post.Code = $('#Code').val();
        post.Name = $('#Category').val();
         $.ajax({
             type: 'POST',
             url: '<?php echo site_url('Store/Category/UpdateCategory'); ?>'+'/'+id,
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
                 }else{
                    new PNotify({
                        title: 'حذف',
                        text: 'Code Cannot Be Updated First Delete its child Category',
                        type: 'error',
                        hide: true
                    });
                 }
             }
         });
    });
</script>