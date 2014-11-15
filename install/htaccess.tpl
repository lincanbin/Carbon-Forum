# errordoc
ErrorDocument 404 {{WebSitePath}}404.php

<IfModule mod_rewrite.c>
	#强制重定向到带www的顶级域名
	RewriteEngine On
	RewriteCond %{HTTP_HOST} ^94cb.com$ [NC]
	RewriteRule ^(.*)$ http://www.94cb.com/$1 [L,R=301]

	RewriteEngine On
	RewriteCond %{REQUEST_METHOD} ^TRACE
	RewriteRule .* - [F]

	RewriteBase {{WebSitePath}}
	RewriteRule ^dashboard$ dashboard.php [L]
	RewriteRule ^favorites(/page/([0-9]*))?$ favorites.php?page=$2 [L]
	RewriteRule ^json/(get_tags|tag_autocomplete|check_username)$ json.php?action=$1 [L]
	RewriteRule ^login$ login.php [L]
	RewriteRule ^manage$ manage.php [L]
	RewriteRule ^new$ new.php [L]
	RewriteRule ^notifications$ notifications.php [L]
	RewriteRule ^page/([0-9]+)$ index.php?page=$1 [L]
	RewriteRule ^register$ register.php [L]
	RewriteRule ^reply$ reply.php [L]
	RewriteRule ^robots.txt$ robots.php [L]
	RewriteRule ^settings$ settings.php [L]
	RewriteRule ^sitemap-(topics|pages|tags|users|index)(-([0-9]+))?.xml$ sitemap.php?action=$1&page=$3 [L]
	RewriteRule ^t/([0-9]+)(-([0-9]*))?$ topic.php?id=$1&page=$3 [L]
	RewriteRule ^tag/(.*?)(/page/([0-9]*))?$ tag.php?name=$1&page=$3 [L]
	RewriteRule ^tags/following(/page/([0-9]*))?$ favorite_tags.php?page=$2 [L]


	#For IIS
	#RewriteRule ^tag/(.*?)(/page/([0-9]*))?$ tag.php?name=$1&page=$3 [QSA,NU,PT,L]
	#RewriteRule ^u/(.*?)$ user.php?username=$1 [QSA,NU,PT,L]


	RewriteRule ^u/(.*?)$ user.php?username=$1 [L]
	RewriteRule ^users/following(/page/([0-9]*))?$ favorite_users.php?page=$2 [L]
	RewriteRule ^upload_controller$ upload_controller.php [L]

</IfModule>

# Image and Flash content Caching for One Month
<IfModule mod_expires.c>
	ExpiresActive On
	<FilesMatch ".(js|css|flv|gif|jpg|jpeg|png|ico|swf|html)$">
		Header set Cache-Control "max-age=604800"
	</FilesMatch>
</IfModule>