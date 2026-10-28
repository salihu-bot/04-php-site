# Daabu Car Vendor Ltd

A small, responsive, multi-page website for a fictional car vendor, built as a learning project with PHP 8. The site introduces the business, lists services and service details, and provides a contact form whose submissions can be reviewed in a password-protected local inbox.

## Features

- Shared PHP configuration, header, footer, and navigation across pages
- Responsive styling for desktop and mobile layouts
- Data-driven service cards and service detail pages
- Team profiles rendered from shared PHP data
- Contact form with server-side validation, sticky values, CSRF protection, and JSONL storage
- Local messages page with password login, newest-first ordering, and escaped output
- Apache access protection for the submissions storage directory

## Tech stack

- PHP 8 with strict types and native includes
- HTML5 and CSS3
- Apache through XAMPP
- JSON Lines (`.jsonl`) for local contact form submissions

## Run with XAMPP

1. Install and start **Apache** from the XAMPP Control Panel.
2. Place the project folder at:

   ```text
   C:\xampp\htdocs\swd\04-php-site
   ```

3. Open the site in your browser:

   [http://localhost/swd/04-php-site/](http://localhost/swd/04-php-site/)

4. Open the private messages page at:

   [http://localhost/swd/04-php-site/messages.php](http://localhost/swd/04-php-site/messages.php)

5. The local learning password is defined as `$messagesPassword` in `includes/config.php`. Change it before using the messages page, even on a shared development computer.

Submissions are appended to `storage/messages.jsonl`. The directory is created automatically when the first valid contact form is submitted. The included `.htaccess` denies direct requests to files in that directory when Apache's access-control directives are enabled.

## Project structure

```text
04-php-site/
├── .gitignore
├── about.php
├── contact.php
├── index.php
├── messages.php
├── service.php
├── services.php
├── info.php
├── README.md
├── assets/
│   └── css/
│       └── style.css
├── includes/
│   ├── config.php
│   ├── data.php
│   ├── footer.php
│   ├── functions.php
│   └── header.php
└── storage/
    └── .htaccess
```

`storage/messages.jsonl` is generated after the first successful contact form submission and is intentionally not committed.

## What I learned

- **PHP pages and includes:** [Write what you learned about sharing code between pages.]
- **Arrays and loops:** [Write what you learned about rendering services and team members from data.]
- **Validation and security:** [Write what you learned about validating input, CSRF tokens, and escaping output.]
- **Sessions and redirects:** [Write what you learned about login state and Post/Redirect/Get.]
- **Styling and responsive design:** [Write what you learned while building the layouts.]

## Local-only password warning

The messages page uses a hard-coded password in `includes/config.php` as a simple learning example. This is **not production-grade authentication**: the password is stored alongside application code, there is no user management or rate limiting, and the session setup is intentionally minimal. Do not use this approach for real customer data or deploy the page publicly. A production system should use securely stored password hashes, proper authentication and authorization, HTTPS, secure session settings, login throttling, and storage outside the public web root. The `.htaccess` rule is an additional local Apache safeguard, not a substitute for keeping sensitive files out of the web root.

`info.php` is a PHP information page included in the learning workspace; remove it before making the site publicly accessible.
