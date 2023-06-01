<html>
<head>
</head>
<body>
<table style="width: 900px; margin: 0 auto; font-family: Arial, Helvetica, sans-serif; font-size: 14px;">
  <tr class="header">
    <td><table class="table" style="width: 100%; border-bottom: 1px solid #00aff0;">
        <tr>
          <td style="width: 150px;"><img src="{{ $logo }}" class="logo" width="100%" /></td>
          <td align="right"><h4 style="font-size: 24px;">{{ $title ?? '' }}</h4></td>
        </tr>
      </table></td>
  </tr>
  <tr class="body">
    <td><div class="" style="margin-bottom: 30px; margin-top: 40px;">
        <p style="margin-bottom: 8px; font-size: 18px;">{!! $heading ?? '' !!}</p>
      </div>
      <div class="m-t-30" style="margin-bottom: 20px;">
        <?php
                    if(isset($table_data)) {
                    ?>
        <table class="table plainTable" border="1" style="width: 100%; margin: 30px 0; border-collapse: collapse; border-color: #00aff0;">
          <?php foreach ($table_data as $key => $value) { ?>
          <tr>
            <th style="text-align: left; background: #cdf0fd; padding: 10px 10px;">{!! $value['key'] !!}</th>
            <td style="text-align: left; padding: 10px 10px;">{!! $value['value'] !!}</td>
          </tr>
          <?php } ?>
        </table>
        <?php } ?>
        {!! $message !!} </div>
      </div></td>
  </tr>
  <!-- .body ends -->
  <tr class="footer">
    <td><p style="font-size: 10px; color: #888;">Disclaimer: The information provided on OnlyFans is for general informational purposes only. All information on the Site is provided in good faith, however we make no representation or warranty of any kind, express or implied, regarding the accuracy, adequacy, validity, reliability, availability or completeness of any information on the Site.</p></td>
  </tr>
</table>
</body>
</html>