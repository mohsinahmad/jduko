<form role ="from" action="<?= site_url('Qurbani/ReceiptReport/getReport')?>" method="POST" target="_blank" id="UserInput">
    <br><h1 style="text-align: center;">یوم وار کی رپورٹ</h1><br><br>
    <div style="border:1px solid #eee;

            box-shadow:0 0 10px rgba(0, 0, 0, .15);

            max-width:600px;

            margin:auto;

            padding:20px;">
        <div style="" class="row">
            <div class="col-md-6" style="margin-top: -4%;">
                <label class="control-label" for="inputSuccess" style="margin-top: 10%; padding-bottom: 5%;" >تاریخ منتخب کریں</label>
                 <input type="text" class="form-control" id="daterange" name="daterange" />
                <input type="hidden" id="to" name="to">
                <input type="hidden" id="from" name="from">
            </div>
            </div>
            <br>
                <input type="submit" name="get" value="رپورٹ حا صل کریں" style="line-height: 210%;">
        </div>
</form>