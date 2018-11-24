<br><h1 style="text-align: center;">اپ ڈیٹ پاس ورڈ</h1><br><br>

<div style="border:1px solid #eee;

            box-shadow:0 0 10px rgba(0, 0, 0, .15);

            max-width:600px;

            margin:auto;

            padding:20px;">



        <div style="line-height:-1%;" class="row">



            <div class="col-md-6">

                <label class="control-label" for="inputSuccess">رکن کا نام</label>

                <input class="form-control" id="uname" name="username" style="width: 250px;" value="<?php echo $_SESSION['user'][0]->UserName;?> " type="text" readonly autofocus>

            </div>



            <div style="" class="col-md-6">

                <label class="control-label" for="inputSuccess">پاس ورڈ</label>

                <input class="form-control" id="upass" name="pass" style="width: 250px;"  type="password">

               <div class="upass" style="color:red; font-family: 'Noto Nastaliq Urdu', serif;"></div>

            </div>

        </div>

        <div class="row" style="margin-top: 3%;">

            <div class="col-md-6">

                <label class="control-label" for="inputSuccess">پاس ورڈ کی توثیق</label>

                <input class="form-control" id="conpass" name="cpass" style="width: 250px;" type="password">

                <div class="conpass" style="color:red; font-family: 'Noto Nastaliq Urdu', serif;"></div>

                

            </div>

            

        </div>

        <br><br>

        

            <input type="button" name="userupdate" id="userupdate" class="button updateuser" value="تصدیق کریں">

        

</div>

<style type="text/css">

.button {

   

    padding: 5px 8px;

    text-align: center;

    font-size: 13px;

    }

</style>