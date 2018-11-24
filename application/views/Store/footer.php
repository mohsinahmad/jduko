</div>
</div>
<script src="<?= base_url()."assets/js/"?>jquery-1.12.4.js"></script>
<script src="<?= base_url()."assets/js/"?>bootstrap.min.js"></script>
<script src="<?= base_url()."assets/js/"?>bootstrap-datepicker.js" type="text/javascript"></script>
<script src="<?= base_url()."assets/js/"?>metisMenu/metisMenu.min.js"></script>
<script src="<?= base_url()."assets/js/"?>raphael/raphael.min.js"></script>
<script src="<?= base_url()."assets/js/"?>morris/morris.min.js"></script>
<script src="<?= base_url()."assets/js/"?>sb-admin-2.js"></script>
<script src="<?= base_url()."assets/js/"?>jquery/jquery.dataTables.min.js"></script>
<script src="<?= base_url()."assets/js/"?>bootstrap/dataTables.bootstrap.min.js"></script>
<script src="<?= base_url()."assets/js/"?>jquery.print.js"></script>
<script src="<?= base_url()."assets/"?>pnotify.custom.min.js"></script>
<script src="<?= base_url()."assets/js/"?>select2.min.js"></script>
<script src="<?= base_url()."assets/js/"?>bootstrap-select.min.js"></script>
<script src="<?= base_url()."assets/js/"?>jquery-ui.js"></script>
<script src="<?= base_url()."assets/"?>awesomplete.js" type="text/javascript"></script>
<script src="<?= base_url()."assets/js/"?>moment.min.js" type="text/javascript"></script>
<script src="<?= base_url()."assets/js/"?>daterangepicker.js"></script>
<script src="<?= base_url()."assets/js/"?>select2.min.js"></script>

<script type="text/javascript">
    $(".js-example-basic-single").select2({
        dir: "rtl"
    });

    $(".js-example-basic-multiple").select2({
        placeholder: "منتخب کریں",
        dir: "rtl"
    });

      $( function() {
        $( "#datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
        $( "#datepicker1" ).datepicker({ dateFormat: 'yy-mm-dd' });
        });

    $(function() {
        var max_date = function () {
            var tmp = null;
            $.ajax({
                'async': false,
                type:'GET',
                url:'<?= site_url('Accounts/Calendar/getMaxDate')?>',
                success:function(response){
                    var data = $.parseJSON(response);
                    tmp = data.date;
                }
            });
            return tmp;
        }();

        var min_date = function () {
            var temp = null;
            $.ajax({
                'async': false,
                type:'GET',
                url:'<?= site_url('Accounts/Calendar/getMinDate')?>',
                success:function(response){
                    var data = $.parseJSON(response);
                    temp = data.date;
                }
            });
            return temp;
        }();

        $('input[name="daterange"]').daterangepicker({
            "minDate": new Date(min_date),
            "maxDate": new Date(max_date),
            "startDate": moment(),
            "endDate": moment()
        }, function(start, end) {
            $('#to').val(start.format('YYYY-MM-DD'));
            $('#from').val(end.format('YYYY-MM-DD'));
        });
        // $('.selectpicker').selectpicker({

        // });
    });

    $('.search').on('click',function(){
        var post = new Object();
        var voucherno = $('.voucherno').val();
        var book_type = $('#booktype').val();
        var to = $('#to').val();
        var from = $('#from').val();
        if (voucherno == "" && book_type == "") {
            $.ajax({
                type:'GET',
                url:'<?= site_url('Accounts/Dashboard/all');?>',
                success:function (response) {
                    $('.tr_tabel').html(response);
                }
            });
        }
        else if ((to !='' && from != '') || book_type == "") {
            $.ajax({
                type:'POST',
                url:'<?= site_url('Accounts/Dashboard/GetTransactionByDate');?>'+'/'+book_type,
                data:{'to':to,"from":from},
                success:function (response) {
                    $('.tr_tabel').html(response);
                }
            });
        }
        else{
            $.ajax({
                type:'GET',
                url:'<?= site_url('Accounts/Dashboard/GetByVoucherNoAndType');?>'+'/'+voucherno+'/'+book_type,
                success:function (response) {
                    $('.tr_tabel').html(response);
                }
            });
        }
    });

    $(document).ready(function() {
        window.parent.document.body.style.zoom = 1;
        $('#dataTables-example').DataTable({
            responsive: true,
            oLanguage: {sLengthMenu: "<select id='length'>"+
            "<option value='10'>10</option>"+
            "<option value='25'>25</option>"+
            "<option value='50'>50</option>"+
            "<option value='100'>100</option>"+
            "<option value='-1'>All</option>"+
            "</select>"},
            "iDisplayLength": 10
        });
        $('.typeall').hide();
        $('#datetimepicker').datepicker({
            dateFormat: 'yy-mm-dd',
            todayHighlight:true,
            orientation: "bottom auto",
            rtl:true
        });

        $('#datetimepicker1').datepicker({
            dateFormat: 'yy-mm-dd',
            todayHighlight:true,
            orientation: "bottom auto",
            rtl:true
        });

        $('.input-daterange input').each(function() {
            $(this).datepicker({
                clearDates:false,
                dateFormat: 'yy-mm-dd',
                todayHighlight:true,
                rtl:true
            });
        });

        $(".dataTables_filter input").attr({'lang': 'fa','type': 'hidden', 'id':'data' });
        $(".dataTables_filter label").text('');
        oTable = $('#dataTables-example').DataTable();
        oTable1 = $('#dataTables-example1').DataTable();
        $('#myInputTextField').keyup(function(){
            oTable.search($(this).val()).draw() ;
        });
        $('#myInputTextField1').keyup(function(){
            oTable1.search($(this).val()).draw() ;
        });
        $('.dataSave').hide();
        $('#link').hide();
    });

    var check = '<?= $this->session->userdata('one')?>';
    if(!check){
        $.ajax({
            type:'GET',
            url:'<?= site_url('Accounts/Books/lastCompanyByUser');?>',
            success:function (response) {
                var data = $.parseJSON(response);
                $('.link').text(data.par_name+' - '+data.Name);
                $('#cashComp').html('<a href="#"><i class="fa fa-company fa-fw id"></i>'+data.par_name+' - '+data.Name+'</a>');
                Mycmenu(data);
           }
        });
    }

    var comp_id = '<?= $this->session->userdata('comp');?>';
    $('.comp').on('click',function(e){
        e.preventDefault();
        var compID = $(this).data('id');
        $('#com').val(compID);
        $('.cmenu').empty();
        $('.link').text($(this).text());
        $.ajax({
            type:'GET',
            url:'<?= site_url('Accounts/Books/getCompanyName') ?>'+'/'+compID,
            success:function(response){
                var data = $.parseJSON(response);
                ajaxCall(compID);
                location.reload();
                Mycmenu(data);
            }
        });
    });

    if(comp_id)
    {
        $.ajax({
            type:'GET',
            url:'<?= site_url('Accounts/Books/getCompanyName') ?>'+'/'+comp_id,
            success:function(response){
                var data = $.parseJSON(response);
                $('.link').text(data.par_name+' - '+data.Name);
            }
        });
    }

    if(comp_id){
        $.ajax({
            type:'GET',
            url:'<?= site_url('Accounts/Books/getCompanyName') ?>'+'/'+comp_id,
            success:function(response){
                var data = $.parseJSON(response);
                ajaxCall(comp_id);
                Mycmenu(data);
            }
        });
    }

    function getBooksByUser(company_id) {
        $.ajax({
            type:'GET',
            url:'<?= site_url('Accounts/Books/getCompanyName') ?>'+'/'+company_id,
            success:function(response){
                ajaxCall(comp_id);
                var data = $.parseJSON(response);
                Mycmenu(data);
            }
        });
    }

    function Mycmenu(data) {
        $('.cmenu')
            .append($("<li class='active'>").append($("<a>").attr("href","<?= site_url('Store/DonationType') ?>").text('تعوّن کی اقسام').append($("</a>").append($("</li>")))))
            .append($("<li class='active'>").append($("<a>").attr("href","<?= site_url('Store/Category') ?>").text('آئٹم کیٹیگری ').append($("</a>").append($("</li>")))))
            .append($("<li class='active'>").append($("<a>").attr("href","<?= site_url('Store/items/ItemSetup') ?>").text(' آئٹم سیٹ اپ ').append($("</a>").append($("</li>")))))
            .append($("<li class='active'>").append($("<a>").attr("href","<?= site_url('Store/DemandForm') ?>").text('مطلوب اشیاء').append($("</a>").append($("</li>")))))
            .append($("<li class='active'>").append($("<a>").attr("href","<?= site_url('Store/ApproveDemand') ?>").text('ڈیمانڈ منظوری').append($("</a>").append($("</li>")))))
            .append($("<li class='active'>").append($("<a>").attr("href","<?= site_url('Store/ItemIssue') ?>").text('اشیاء کا اجراء').append($("</a>").append($("</li>")))))
            .append($("<li class='active'>").append($("<a>").attr("href","<?= site_url('Store/ItemReturn') ?>").text('جاری شدہ اشیاء').append($("</a>").append($("</li>")))))
            .append($("<li class='active'>").append($("<a>").attr("href","<?= site_url('Store/StockSlip') ?>").text('اسٹاک وصولی').append($("</a>").append($("</li>")))));
    }

    function ajaxCall(company_id) {
        $.ajax({
            type:'GET',
            url:'<?= site_url('Accounts/Books/getCompanyName') ?>'+'/'+company_id,
            success:function(responseeee){
                var datass = $.parseJSON(responseeee);
                $('#cashComp').html('<a href="#" data-id='+datass.id+' ><i class="fa fa-company fa-fw id"></i>'+datass.par_name+' - '+datass.Name+'</a>');
            }
        });
    }

    //header js
    $('.year').on('click',function(e){
        e.preventDefault();
        var year = $(this).data('id');
        var current_year = '<?= $this->session->userdata('current_year');?>';
        if(current_year != year){
            $.ajax({
                url:'<?= site_url('Accounts/Dashboard/setYear');?>'+'/'+year,
                dataType:'json',
                success:function (response) {
                    if(response == '1'){
                        location.reload();
                    }
                }
            })
        }
    });

    //End header Js

    $.fn.extend({
        treed: function (o) {
            var openedClass = 'glyphicon-minus-sign';
            var closedClass = 'glyphicon-plus-sign';

            if (typeof o != 'undefined'){
                if (typeof o.openedClass != 'undefined'){
                    openedClass = o.openedClass;
                }
                if (typeof o.closedClass != 'undefined'){
                    closedClass = o.closedClass;
                }
            }

            //initialize each of the top levels

            var tree = $(this);
            tree.addClass("tree");
            tree.find('li').has("ul").each(function () {
                var branch = $(this); //li with children ul
                branch.prepend("<i class='indicator glyphicon " + closedClass + "'></i>");
                branch.addClass('branch');
                branch.on('click', function (e) {
                    if (this == e.target) {
                        var icon = $(this).children('i:first');
                        icon.toggleClass(openedClass + " " + closedClass);
                        $(this).children().children().toggle();
                    }
                });
                branch.children().children().toggle();
            });
            //fire event from the dynamically added icon
            tree.find('.branch .indicator').each(function(){
                $(this).on('click', function () {
                    $(this).closest('li').click();
                });
            });
            //fire event to open branch if the li contains an anchor instead of text
            tree.find('.branch>a').each(function () {
                $(this).on('click', function (e) {
                    $(this).closest('li').click();
                    e.preventDefault();
                });
            });
            //fire event to open branch if the li contains a button instead of text
            tree.find('.branch>button').each(function () {
                $(this).on('click', function (e) {
                    $(this).closest('li').click();
                    e.preventDefault();
                });
            });
        }
    });

    //Initialization of treeviews

    $('#tree1').treed();
    $('#tree').treed();

    $('input[type=number]').on('keydown', function(e) {
        if ( e.which == 38 || e.which == 40 || e.which == 69 )
            e.preventDefault();
    });
</script>
</body>
</html>