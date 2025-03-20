Prosloika is a MVC Framework which is built with KISS principle in mind. It means that its' source code is kept as simple as possible while providing the required functionality.
It is secure and simple to use framework which also weights less than 80 KB.
It uses MVP.css from https://github.com/andybrewer/mvp to create UI.
So, what does it provide:
- MVC functionality
- Very small size
- Simple(really simple) use
- Primitive yet working ActiveRecord implementation
- Simple access control
- Ready for working with CORS
- Query builder that is better not to use
- It is obviously faster that some MVC combine
- Well-documented project - not only by self-documenting code, but also it comes with complete explainations on how it should be used. PHPDocumentor compatible as well
- REST support
- Extremely easy to configure, yet provides flexible configuration
- Fresh PHP version
- Free and open source
- Comes with safety in mind
- Supports shiny new PHP stuff
- Easy to modify
Did I mention it is completely independent from Composer?
Downsides of this project are:
- Does not support multipart/form-data
- The functionality may be too primitive for some projects. You are free to extend it. I.e. it comes without mailer by default.
- It provides very basic frontend functionality.
- Lack of Composer support.
- Since that, lack of addons support.
- Requires PHP 8 or later
- NIH
- The most important part is that changes in routing will by default require restarting php-fpm. You can change that in index.php if you want.

It may be suitable and easy to deploy for the following projects:
- Admin panel for embedded systems
- API layer between the frontend and your SQL database
- Amateur projects

LICENSE
MicroMC is licensed under BSD 0-clause license. See LICENSE for license text
MVP.CSS is licensed under MIT license

INSTALLING:
copy these files in your /var/www/micromc directory, then add the following line to your /etc/nginx/sites-available/your-site
Inside of a server section:
root /var/www/micromc;
Then replace your location section with these lines:
location / {
try_files $uri $uri /index.php?/$request_uri;
}