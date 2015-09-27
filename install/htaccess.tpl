# errordoc
ErrorDocument 404 {{WebSitePath}}/404.php

<IfModule mod_rewrite.c>
	#强制重定向到带www的顶级域名
	RewriteEngine On
	RewriteCond %{HTTP_HOST} ^94cb.com$ [NC]
	RewriteRule ^(.*)$ https://www.94cb.com/$1 [L,R=301]

	#For SSL 
	#RewriteCond %{HTTP_HOST} ^94cb.com$ [NC]
	#RewriteCond %{SERVER_PORT} 80
	#RewriteRule ^(.*)$ https://www.94cb.com/$1 [L,R=301]
	#RewriteCond %{HTTP_HOST} ^m.94cb.com$ [NC]
	#RewriteCond %{SERVER_PORT} 80
	#RewriteRule ^(.*)$ https://m.94cb.com/$1 [L,R=301]

	RewriteCond %{REQUEST_METHOD} ^TRACE
	RewriteRule .* - [F]

	RewriteBase {{WebSitePath}}/
	#RewriteRule ^includes - [F,L]
	#RewriteRule ^styles/default/template - [F,L]
	#RewriteRule ^styles/mobile/template - [F,L]
	#RewriteRule ^styles/api/template - [F,L]

	RewriteRule ^dashboard$ dashboard.php [L]
	RewriteRule ^favorites(/page/([0-9]*))?$ favorites.php?page=$2 [L]
	RewriteRule ^forgot$ forgot.php [L]
	RewriteRule ^goto/([0-9]+)-([0-9]+)$ goto.php?topic_id=$1&post_id=$2 [L]
	RewriteRule ^json/([0-9a-z_\-]+)$ json.php?action=$1 [L]
	RewriteRule ^login$ login.php [L,QSA]
	RewriteRule ^manage$ manage.php [L]
	RewriteRule ^new$ new.php [L]
	RewriteRule ^notifications$ notifications.php [L]
	RewriteRule ^oauth-([0-9]+)$ oauth.php?app_id=$1 [L,QSA]
	RewriteRule ^page/([0-9]+)$ index.php?page=$1 [L]
	RewriteRule ^register$ register.php [L]
	RewriteRule ^reply$ reply.php [L]
	RewriteRule ^reset_password/(.*?)$ reset_password.php?access_token=$1 [L]
	RewriteRule ^robots.txt$ robots.php [L]
	RewriteRule ^search.xml$ open_search.php [L]
	RewriteRule ^search/(.*?)(/page/([0-9]*))?$ search.php?keyword=$1&page=$3 {{RedirectionType}}
	RewriteRule ^settings$ settings.php [L]
	RewriteRule ^sitemap-(topics|pages|tags|users|index)(-([0-9]+))?.xml$ sitemap.php?action=$1&page=$3 [L]
	RewriteRule ^statistics$ statistics.php [L]
	RewriteRule ^t/([0-9]+)(-([0-9]*))?$ topic.php?id=$1&page=$3 [L]
	RewriteRule ^tag/(.*?)(/page/([0-9]*))?$ tag.php?name=$1&page=$3 {{RedirectionType}}
	RewriteRule ^tags/following(/page/([0-9]*))?$ favorite_tags.php?page=$2 [L]
	RewriteRule ^tags(/page/([0-9]*))?$ tags.php?page=$2 [L]
	RewriteRule ^u/(.*?)$ user.php?username=$1 {{RedirectionType}}
	RewriteRule ^users/following(/page/([0-9]*))?$ favorite_users.php?page=$2 [L]
	RewriteRule ^upload_controller$ upload_controller.php [L]
	RewriteRule ^view-(desktop|mobile)$ view.php?view=$1 [L,QSA]
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