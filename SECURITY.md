# Security Policy

## Supported Versions

Only the latest code on the `main` branch (the version deployed to Vercel) receives security fixes.

| Version        | Supported |
| -------------- | --------- |
| `main` (latest) | ✅        |
| Older commits  | ❌        |

## Reporting a Vulnerability

**Please do not report security problems in public GitHub issues.**

Report them privately through GitHub:

1. Open the repository's **Security** tab.
2. Click **Report a vulnerability**.
3. Describe the problem, the steps to reproduce it, and the impact you expect.

You should get a reply within **7 days**. Once the problem is confirmed, a fix is released as soon as possible and you are credited, unless you would rather stay anonymous.

## Scope

In scope:
- the public site and the admin panel (`/admin`)
- login, registration and sessions
- bookings and PayHere payments (`pay_now.php`, `payhere_notify.php`)
- file uploads (images and PDFs)

Out of scope:
- third-party services (PayHere, Vercel, the MySQL host, the SMTP provider)
- denial-of-service or brute-force testing against the live site
- the bundled third-party libraries (`admin/inc/mpdf`, `inc/phpmailer`); report those to their own projects

## Notes for anyone deploying this project

- Never commit `.env`. Copy `.env.example` and set real values as environment variables.
- After importing `hms.sql`, change the default admin password right away.
- Use your own PayHere merchant secret, and keep `PAYHERE_SANDBOX=1` until you go live.
