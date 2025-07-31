@extends('mail.layout.header')

<body style="margin: 0; padding: 0; width: 100%; word-break: break-word; -webkit-font-smoothing: antialiased; --bg-opacity: 1; background-color: #eceff1; ">
  <div role="article" aria-roledescription="email" aria-label="Reset your Password" lang="en">
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
                      <p style="font-weight: 600; font-size: 18px; margin-bottom: 0;">Hey</p>
                      <p style="font-weight: 700; font-size: 20px; margin-top: 0; --text-opacity: 1; color: #ff5850; ">{{$name}}</p>
                      <p style="margin: 0 0 24px;">
                        A request to reset password was received from your
                        <span style="font-weight: 600;">Delta</span> Account -
                        <a href="#" class="hover-underline" style="--text-opacity: 1; color: #7367f0;  text-decoration: none;">{{$email}}</a>
                      </p>
                      <p style="margin: 0 0 24px;">Use this link to reset your password and login.</p>
                      <table style="font-family: 'Montserrat',Arial,sans-serif;" cellpadding="0" cellspacing="0" role="presentation">
                        <tr>
                          <td style="mso-padding-alt: 16px 24px; --bg-opacity: 1; background-color: #7367f0;  border-radius: 4px; " bgcolor="rgba(115, 103, 240, var(--bg-opacity))">
                            <a href="{{ route('password.reset', ['token'=> $token,'email'=> $email])}}" style="display: block; font-weight: 600; font-size: 14px; line-height: 100%; padding: 16px 24px; --text-opacity: 1; color: #ffffff;  text-decoration: none;">Reset Password &rarr;</a>
                          </td>
                        </tr>
                      </table>
                      <p style="margin: 24px 0;">
                        <span style="font-weight: 600;">Note:</span> This link is valid for 1 hour from the time it was
                        sent to you and can be used to change your password only once.
                      </p>
                      <p style="margin: 0;">
                        If you did not intend to deactivate your account or need our help keeping the account, please
                        contact us at
                        <a href="mailto:support@example.com" class="hover-underline" style="--text-opacity: 1; color: #7367f0;  text-decoration: none;">support@Delta.com</a>
                      </p>
                      <table style="font-family: 'Montserrat',Arial,sans-serif; width: 100%;" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                        <tr>
                          <td style="font-family: 'Montserrat',Arial,sans-serif; padding-top: 32px; padding-bottom: 32px;">
                            <div style="--bg-opacity: 1; background-color: #eceff1;  height: 1px; line-height: 1px;">&zwnj;</div>
                          </td>
                        </tr>
                      </table>
                      <p style="margin: 0 0 16px;">
                        Not sure why you received this email? Please
                        <a href="mailto:support@example.com" class="hover-underline" style="--text-opacity: 1; color: #7367f0;  text-decoration: none;">let us know</a>.
                      </p>
                      <p style="margin: 0 0 16px;">Thanks, <br>The Delta Team</p>
                    </td>
                  </tr>
                  <tr>
                    <td style="font-family: 'Montserrat',Arial,sans-serif; height: 20px;" height="20"></td>
                  </tr>
               @include('mail.layout.social')
                  <tr>
                    <td style="font-family: 'Montserrat',Arial,sans-serif; height: 16px;" height="16"></td>
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
