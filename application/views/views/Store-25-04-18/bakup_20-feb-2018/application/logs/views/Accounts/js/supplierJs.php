<script type="text/javascript">

	$('.delete_supplier').on('click',function(){
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
                url:'<?= site_url('Accounts/Supplier/DeleteSupplier'); ?>'+'/'+id,
                returnType:'JSON',
                success:function(response){
                    var data = $.parseJSON(response);
                    if(data['success']){
                        new PNotify({
                            title: 'حذف',
                            text: 'سپلائر حذف کر دیا گیا ہے',
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
		id = $(this).data('id');
		$.ajax({
    		type: 'POST',
            url: '<?= site_url('Accounts/Supplier/SupplierById'); ?>'+'/'+id,
            returnType: 'JSON',
            success:function (response) {
            	var data = $.parseJSON(response);
                $('#NameU').val(data.NameU);
                $('#NameE').val(data.NameE);
                $('#NTN_number').val(data.NTN_number);
                $('#CNIC').val(data.CNIC);
                // $('#Phone_number').val(data.Phone_number);
                $('#AddressU').val(data.AddressU);
                $('#AddressE').val(data.AddressE);
            	$('#Contact_person').val(data.Contact_person);
            	$('#Nature_Of_Payment').val(data.Nature_Of_Payment);
                $('#accounts_list option[value='+data.ChartOfAcc_id+']').attr('selected','selected');
            }
		});
	});

    $(document).on('click','.supplierEdit',function(){
        var post = new Object();
        post.SUname = $('#NameU').val();
        post.SEname = $('#NameE').val();
        post.ntn = $('#NTN_number').val();
        post.cnic = $('#CNIC').val();
        // post.Pnum = $('#Phone_number').val();
        post.EAddre = $('#AddressE').val();
        post.UAddre = $('#AddressU').val();
        post.Cperson = $('#Contact_person').val();
        post.Nature_Of_Payment = $('#Nature_Of_Payment').val();
        post.account = $('#accounts_list').val();
        $.ajax({
            type: 'POST',
            url: '<?= site_url('Accounts/Supplier/UpdateSupplier'); ?>'+'/'+id,
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