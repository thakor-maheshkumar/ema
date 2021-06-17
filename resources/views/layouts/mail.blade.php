<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<style type="text/css">
.emailtemplate {
    width:600px;
    border:1px solid #000;
    background-color:#fff;
}
.emailtemplate .content {
    width: 250px;
    margin:auto;
    font-size: 12px;
    word-break: break-word;
    word-wrap: normal;
}
.emailtemplate .portaltitle {
    border:3px solid #000;
    text-transform: uppercase;
    width: 250px;
    margin:auto;
    text-align: center;
    letter-spacing: 15px;
    font-weight: 900;
    font-size: 18px;
    background-color: #f2dbdb;
}
p{ font-size: 12px !important; word-break: break-word;}

</style>
<html>
<head>
@include('emails.head')
</head>
<body class="home">
<table width="600" border="0" cellspacing="0" cellpadding="0" align="center" style="width:600px; border:1px solid #000; background-color:#fff; margin: 0 auto; font-family: Arial; font-size: 12px;">
  <tr>
    <td width="600"><table width="600" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"> @include('emails.header')</td>
  </tr>
  <tr>
    <td width="250" align="center" style="text-align:center"><table width="250" border="0" align="center" cellpadding="0" cellspacing="0" style="margin: 0 auto 0;">
        <tr>
          <td style="border:3px solid #000; text-transform: uppercase;width: 250px; margin:auto; text-align: center;letter-spacing: 15px; font-weight: 900; font-size: 18px; background-color: #f2dbdb; font-family: sans-serif;">portal</td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td width="600"><table width="600" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="175" valign="top">{!! Html::image('images/email_bg.jpg') !!}</td>
        <td width="250" valign="top" style="padding: 0 10px; width: 230px;"><table width="250" border="0" align="center" cellpadding="0" cellspacing="0" style="margin: 0 auto;">
              <tr>
                <td width="250" style="width: 250px; font-size: 12px; padding:  15px 0;  word-wrap: break-word; font-family: Arial; font-size: 12px;">
                   
                    <p style=" white-space:normal; width: 250px;  word-wrap: break-word;"> @yield('content') </p>
                  
                   
              </tr>
            </table></td>
        <td width="175" valign="top">{!! Html::image('images/email_bg.jpg') !!}</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td style="background-color: #fff;color: #000;border-top: 1px solid #f4dadc;font-size: 12px; font-family: Arial;text-align: center; margin: 10px 0 0; padding: 5px 0;">@include('emails.footer')</td>
  </tr>
</table>
</td>
  </tr>
</table>
</body>
</html>