# errordoc
ErrorDocument 404 {{WebSitePath}}/404.php

<IfModule mod_rewrite.c>
	#强制重定向到带www的顶级域名
	RewriteEngine On
	RewriteCond %{HTTP_HOST} ^94cb.com$ [NC]
	RewriteRule ^(.*)$ https://www.94cb.com/$1 [L,R=301]

	#For SSL 
	#RewriteCond %{HTTP_HOST} ^www.94cb.com$ [NC]
	#RewriteCond %{SERVER_PORT} 80
	#RewriteRule ^(.*)$ https://www.94cb.com/$1 [L,R=301]
	#RewriteCond %{HTTP_HOST} ^m.94cb.com$ [NC]
	#RewriteCond %{SERVER_PORT} 80
	#RewriteRule ^(.*)$ https://m.94cb.com/$1 [L,R=301]

	RewriteCond %{REQUEST_METHOD} ^TRACE
	RewriteRule .* - [F]

	RewriteBase {{WebSitePath}}/
	RewriteRule ^.git - [F,L]
	RewriteRule ^controller - [F,L]
	RewriteRule ^docker_resources - [F,L]
	RewriteRule ^library - [F,L]
	RewriteRule ^service - [F,L]
	RewriteRule ^view - [F,L]

	# Redirect Trailing Slashes If Not A Folder...
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteRule ^(.*)/$ /$1 [L,R=301]

	# Handle Front Controller...
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteRule ^ index.php [L]
</IfModule>

# Expire static files after a month in the client's cache
# http://httpd.apache.org/docs/2.4/mod/mod_expires.html
<IfModule mod_expires.c>
	# enable expirations
	ExpiresActive On
	ExpiresByType text/javascript A604800
	ExpiresByType application/x-javascript A604800
	ExpiresByType text/css A604800
	ExpiresByType application/x-shockwave-flash A2592000
	ExpiresByType image/png A2592000
	ExpiresByType image/gif A2592000
	ExpiresByType image/jpeg A2592000
	ExpiresByType image/x-icon A2592000
</IfModule>