@extends('mail.layout.header')

<body style="margin: 0; padding: 0; width: 100%; word-break: break-word; -webkit-font-smoothing: antialiased; --bg-opacity: 1; background-color: #eceff1; ">
  <div role="article" aria-roledescription="email" aria-label="Email code send" lang="en">
    <table style="font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif; width: 100%;" width="100%" cellpadding="0" cellspacing="0" role="presentation">
      <tr>
        <td align="center" style="--bg-opacity: 1; background-color: #eceff1;font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif;" bgcolor="rgba(236, 239, 241, var(--bg-opacity))">
          <table class="sm-w-full" style="font-family: 'Montserrat',Arial,sans-serif; width: 600px;" width="600" cellpadding="0" cellspacing="0" role="presentation">
            @include('mail.layout.logo')
            <tr>
              <td align="center" class="sm-px-24" style="font-family: 'Montserrat',Arial,sans-serif;">
                <table style="font-family: 'Montserrat',Arial,sans-serif; width: 100%;" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                  <tr>
                    <td class="sm-px-24" style="--bg-opacity: 1; background-color: #ffffff; border-radius: 4px; font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif; font-size: 14px; line-height: 24px; padding: 48px; text-align: left; --text-opacity: 1; color: #626262; " bgcolor="rgba(255, 255, 255, var(--bg-opacity))" align="left">
                      <p style="font-weight: 600; font-size: 18px;  margin-top: 1px;">Hello,</p>
                      <p style="margin: 0 0 24px;">
                        Please find below your One-Time Password to login your account. Your OTP is confidential information so please do not share it further.
                      </p>
                      <p style="font-weight: 700; font-size: 20px; margin-top: 0; --text-opacity: 1; color: #ff5850; text-align: center;">OTP: {{$otp}}</p>
                      <p style="font-weight: 700; font-size: 20px; --text-opacity: 1; text-align: center;"> Note: This OTP is valid for 5 Minutes</p>
                      <table style="font-family: 'Montserrat',Arial,sans-serif; width: 100%;" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                        <tr>
                          <td style="font-family: 'Montserrat',Arial,sans-serif; padding-top: 10px; padding-bottom: 20px;">
                            <div style="--bg-opacity: 1; background-color: #eceff1;  height: 1px; line-height: 1px;">&zwnj;</div>
                          </td>
                        </tr>
                      </table>
                      <p style="margin: 0 0 16px;">Best Regards, <br>IFRS Tech Team.</p>
                    </td>
                  </tr>
                  <tr>
                    <td style="font-family: 'Montserrat',Arial,sans-serif; height: 20px;" height="20"></td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </div>
</body>

</html>
