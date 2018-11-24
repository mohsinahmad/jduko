<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title style="font-family: 'Noto Nastaliq Urdu', serif;">دارالعلوم کراچی</title>
        <link href="<?= base_url()."assets/"; ?>css/bootstrap.min.css" rel="stylesheet">
        <link href="<?= base_url()."assets/"; ?>css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">
        <link href="<?= base_url()."assets/"; ?>css/plugins/timeline.css" rel="stylesheet">
        <link href="<?= base_url()."assets/"; ?>css/sb-admin-2.css" rel="stylesheet">
        <link href="<?= base_url()."assets/"; ?>css/plugins/morris.css" rel="stylesheet">
        <link href="<?= base_url()."assets/"; ?>css/font-awesome/font-awesome.min.css" rel="stylesheet" type="text/css">
        <style>
            @font-face {
                font-family: "Noto Nastaliq Urdu";
                src: url(<?= base_url().'assets/'; ?>fonts/NotoNastaliqUrdu-Regular.ttf) format("truetype");
            }

            @-moz-document url-prefix() {
                #names {
                    padding-top: 0;
                    padding-bottom: 0;
                }
            }
        </style>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="<?= base_url().'assets/'; ?>js/html5shiv.js"></script>
        <script src="<?= base_url().'assets/'; ?>js/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="logo">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-offset-4"><img src="<?= base_url()."assets/"; ?>images/karachi.jpg" style="width: 100%; margin-top: 20%; margin-bottom: -85px; opacity: 0.8; height: 150px;">
                    <div class="login-panel panel panel-default">
                        <div class="panel-heading">
                            <h3 style="font-family: 'Noto Nastaliq Urdu', serif;" class="panel-title">لاگ ان کریں</h3>
                        </div>
                        <div class="panel-body">
                            <form style="font-family: 'Noto Nastaliq Urdu', serif;">
                                <div style="">
                                    <div  class="alert  alert-success alert-dismissable" id="salert" style="display: none">
                                        <a href="#"  class="close" data-dismiss="alert" aria-label="close">&times;</a>    لاگ ان  کامیاب
                                    </div>
                                    <div class="alert alert-danger alert-dismissable" id="dalert" style="display: none">
                                        <a href="#"  class="close" data-dismiss="alert" aria-label="close">&times;</a>   لاگ ان ناکام اپنی اسناد کی جانچ پڑتال کریں !
                                    </div>
                                    <div class="alert alert-danger alert-dismissable" id="lalert" style="display: none">
                                        <a href="#" class="close"  data-dismiss="alert" aria-label="close">&times;</a>   آپ  کا اکاؤنٹ غیر فعال ہے  !
                                    </div>
                                </div>
                                <fieldset>
                                    <div class="form-group">
                                        <input style="font-family: 'Noto Nastaliq Urdu', serif;" class="form-control username" id="names" placeholder="رکن کا نام" type="text" autofocus>
                                        <div class="name" style="color:red; font-family: 'Noto Nastaliq Urdu', serif;"></div>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control password" style="font-family: 'Noto Nastaliq Urdu', serif;" placeholder="خفیہ کوڈ"  type="password" value="">
                                        <div class="pass" style="color:red; font-family: 'Noto Nastaliq Urdu', serif;"></div>
                                    </div>
                                    <!-- Change this to a button or input when using this as a form -->
                                    <input type="button" class="btn btn-lg btn-success btn-block login" value="لاگ ان کریں"></input>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="<?= base_url()."assets/"; ?>js/jquery-1.11.0.js"></script>
        <script src="<?= base_url()."assets/"; ?>js/bootstrap.min.js"></script>
        <script src="<?= base_url()."assets/"; ?>js/metisMenu/metisMenu.min.js"></script>
        <script src="<?= base_url()."assets/"; ?>js/sb-admin-2.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                $('#salert').hide();
                $('#dalert').hide();
                $('#lalert').hide();
            });

            $('.form-control').keypress(function(e){
                if(e.which == 13){
                    login();
                }
            });

            $('.login').on('click',function(e){
                e.preventDefault();
                login();
            });

            function login(){
                var post = new Object();
                post.username = $('.username').val();
                post.password = $('.password').val();
                $.ajax({
                    type:'POST',
                    url:'<?= site_url('login/userLogin');?>',
                    data:post,
                    success:function(response){
                        var data = $.parseJSON(response);
                        if(data['form_error']){
                            $('.name').html(data['username']).show();
                            $('.pass').html(data['password']).show();
                            setTimeout(function () {
                                location.reload();
                            }, 1000);
                        }else if(data['success']){
                            $('#salert').show();
                            setTimeout(function () {
                                window.location.href = "<?= site_url('Accounts/Dashboard/menu') ?>";
                            }, 1000);
                        }else if(data['error']){
                            $('#dalert').show();
                        }else if(data['Locked']){
                            $('#lalert').show();
                            setTimeout(function () {
                                location.reload();
                            }, 1000);
                        }
                    }
                });
            }
        </script>
    </body>
</html>
