# The is a Procfile. It tells Heroku to:
# 1. Start PHP with Apache 
# 2. Pass along our Apache configuration file to setup URL ReWriting. 
# 3. Use the public/ folder as webroot
web: vendor/bin/heroku-php-apache2 -C apache_app.conf public/
