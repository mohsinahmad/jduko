<form>
    <br><h1 style="text-align: center;">رپورٹس کی کنفیگریشن</h1><br><br>
    <div style="border:1px solid #eee;box-shadow:0 0 10px rgba(0, 0, 0, .15);max-width:600px;margin:auto;padding:20px;">
        <div style="line-height:10%;" class="row">
            <div class="col-lg-8">
                <div class="form-group">
                    <label style="position: absolute;margin-top: 14px;" class="control-label" for="inputSuccess">رپورٹس </label>
                    <select name="report" class="form-control" id="report" style="margin-right: 51px;width: 73%;padding-bottom: 0px;padding-top: 0px;margin-top: 19px;" required>
                        <option value="0" selected disabled>منتخب کیجیئے</option>
                        <?php foreach($report as $item):?>
                            <option value="<?= $item->Id?>"><?= $item->ReportName?></option>
                        <?php endforeach?>
                    </select>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <button type="button" style="margin-top: 8%" class="btn btn-info submit">آگے بڑھئے</button>
                </div>
            </div>
        </div>
    </div>
</form>
<script src="<?= base_url()."assets/"; ?>js/jquery-1.12.4.js"></script>
<script type="text/javascript">
    $('.submit').on('click',function(){
        var id = $('#report option:selected').val();
        window.location = '<?= site_url("Accounts/Configuration/config/")?>'+id;
    });
</script>