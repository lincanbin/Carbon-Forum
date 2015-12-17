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

	RewriteRule ^dashboard$ dashboard.php [L,QSA]
	RewriteRule ^favorites(/page/([0-9]*))?$ favorites.php?page=$2 [L,QSA]
	RewriteRule ^forgot$ forgot.php [L,QSA]
	RewriteRule ^goto/([0-9]+)-([0-9]+)$ goto.php?topic_id=$1&post_id=$2 [L,QSA]
	RewriteRule ^json/([0-9a-z_\-]+)$ json.php?action=$1 [L,QSA]
	RewriteRule ^login$ login.php [L,QSA]
	RewriteRule ^manage$ manage.php [L,QSA]
	RewriteRule ^new$ new.php [L,QSA]
	RewriteRule ^notifications$ notifications.php [L,QSA]
	RewriteRule ^oauth-([0-9]+)$ oauth.php?app_id=$1 [L,QSA]
	RewriteRule ^page/([0-9]+)$ index.php?page=$1 [L,QSA]
	RewriteRule ^register$ register.php [L,QSA]
	RewriteRule ^reply$ reply.php [L,QSA]
	RewriteRule ^reset_password/(.*?)$ reset_password.php?access_token=$1 [L,QSA]
	RewriteRule ^robots.txt$ robots.php [L,QSA]
	RewriteRule ^search.xml$ open_search.php [L,QSA]
	RewriteRule ^search/(.*?)(/page/([0-9]*))?$ search.php?keyword=$1&page=$3 {{RedirectionType}}
	RewriteRule ^settings$ settings.php [L,QSA]
	RewriteRule ^sitemap-(topics|pages|tags|users|index)(-([0-9]+))?.xml$ sitemap.php?action=$1&page=$3 [L,QSA]
	RewriteRule ^statistics$ statistics.php [L,QSA]
	RewriteRule ^t/([0-9]+)(-([0-9]*))?$ topic.php?id=$1&page=$3 [L,QSA]
	RewriteRule ^tag/(.*?)(/page/([0-9]*))?$ tag.php?name=$1&page=$3 {{RedirectionType}}
	RewriteRule ^tags/following(/page/([0-9]*))?$ favorite_tags.php?page=$2 [L,QSA]
	RewriteRule ^tags(/page/([0-9]*))?$ tags.php?page=$2 [L,QSA]
	RewriteRule ^u/(.*?)$ user.php?username=$1 {{RedirectionType}}
	RewriteRule ^users/following(/page/([0-9]*))?$ favorite_users.php?page=$2 [L,QSA]
	RewriteRule ^upload_controller$ upload_controller.php [L,QSA]
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
#Code by Tecflare http://www.tecflare.com
Options All -Indexes
<IfModule mod_setenvif.c>
  <IfModule mod_headers.c>
    # mod_headers, y u no match by Content-Type?!
    <FilesMatch "\.(gif|ico|jpe?g|png|svg|svgz|webp)$">
      SetEnvIf Origin ":" IS_CORS
      Header set Access-Control-Allow-Origin "*" env=IS_CORS
    </FilesMatch>
  </IfModule>
</IfModule>
<IfModule mod_headers.c>
  <FilesMatch "\.(eot|font.css|otf|ttc|ttf|woff)$">
    Header set Access-Control-Allow-Origin "*"
  </FilesMatch>
</IfModule>
AddType application/javascript         js jsonp
AddType application/json               json

# Audio
AddType audio/mp4                      m4a f4a f4b
AddType audio/ogg                      oga ogg

# Video
AddType video/mp4                      mp4 m4v f4v f4p
AddType video/ogg                      ogv
AddType video/webm                     webm
AddType video/x-flv                    flv

# SVG
#   Required for svg webfonts on iPad
#   twitter.com/FontSquirrel/status/14855840545
AddType     image/svg+xml              svg svgz
AddEncoding gzip                       svgz

# Webfonts
AddType application/vnd.ms-fontobject  eot
AddType application/x-font-ttf         ttf ttc
AddType application/x-font-woff        woff
AddType font/opentype                  otf

# Assorted types
AddType application/octet-stream            safariextz
AddType application/x-chrome-extension      crx
AddType application/x-opera-extension       oex
AddType application/x-shockwave-flash       swf
AddType application/x-web-app-manifest+json webapp
AddType application/x-xpinstall             xpi
AddType application/xml                     rss atom xml rdf
AddType image/webp                          webp
AddType image/x-icon                        ico
AddType text/cache-manifest                 appcache manifest
AddType text/vtt                            vtt
AddType text/x-component                    htc
AddType text/x-vcard                        vcf
<IfModule mod_deflate.c>

  # Force deflate for mangled headers developer.yahoo.com/blogs/ydn/posts/2010/12/pushing-beyond-gzipping/
  <IfModule mod_setenvif.c>
    <IfModule mod_headers.c>
      SetEnvIfNoCase ^(Accept-EncodXng|X-cept-Encoding|X{15}|~{15}|-{15})$ ^((gzip|deflate)\s*,?\s*)+|[X~-]{4,13}$ HAVE_Accept-Encoding
      RequestHeader append Accept-Encoding "gzip,deflate" env=HAVE_Accept-Encoding
    </IfModule>
  </IfModule>

  # Compress all output labeled with one of the following MIME-types
  # (for Apache versions below 2.3.7, you don't need to enable `mod_filter`
  # and can remove the `<IfModule mod_filter.c>` and `</IfModule>` lines as
  # `AddOutputFilterByType` is still in the core directives)
  <IfModule mod_filter.c>
    AddOutputFilterByType DEFLATE application/atom+xml \
                                  application/javascript \
                                  application/json \
                                  application/rss+xml \
                                  application/vnd.ms-fontobject \
                                  application/x-font-ttf \
                                  application/xhtml+xml \
                                  application/xml \
                                  font/opentype \
                                  image/svg+xml \
                                  image/x-icon \
                                  text/css \
                                  text/html \
                                  text/plain \
                                  text/x-component \
                                  text/xml
  </IfModule>

</IfModule>
<IfModule mod_expires.c>
  ExpiresActive on

# Perhaps better to whitelist expires rules? Perhaps.
  ExpiresDefault                          "access plus 1 month"

# cache.appcache needs re-requests in FF 3.6 (thanks Remy ~Introducing HTML5)
  ExpiresByType text/cache-manifest       "access plus 0 seconds"

# Your document html
  ExpiresByType text/html                 "access plus 0 seconds"

# Data
  ExpiresByType application/json          "access plus 0 seconds"
  ExpiresByType application/xml           "access plus 0 seconds"
  ExpiresByType text/xml                  "access plus 0 seconds"

# Feed
  ExpiresByType application/atom+xml      "access plus 1 hour"
  ExpiresByType application/rss+xml       "access plus 1 hour"

# Favicon (cannot be renamed)
  ExpiresByType image/x-icon              "access plus 1 week"

# Media: images, video, audio
  ExpiresByType audio/ogg                 "access plus 1 month"
  ExpiresByType image/gif                 "access plus 1 month"
  ExpiresByType image/jpeg                "access plus 1 month"
  ExpiresByType image/png                 "access plus 1 month"
  ExpiresByType video/mp4                 "access plus 1 month"
  ExpiresByType video/ogg                 "access plus 1 month"
  ExpiresByType video/webm                "access plus 1 month"

# HTC files  (css3pie)
  ExpiresByType text/x-component          "access plus 1 month"

# Webfonts
  ExpiresByType application/vnd.ms-fontobject "access plus 1 month"
  ExpiresByType application/x-font-ttf    "access plus 1 month"
  ExpiresByType application/x-font-woff   "access plus 1 month"
  ExpiresByType font/opentype             "access plus 1 month"
  ExpiresByType image/svg+xml             "access plus 1 month"

# CSS and JavaScript
  ExpiresByType application/javascript    "access plus 1 year"
  ExpiresByType text/css                  "access plus 1 year"

</IfModule>
<IfModule mod_headers.c>
Header set Cache-Control "no-transform"
</IfModule>
<IfModule mod_headers.c>
  Header unset ETag
</IfModule>

# Since we're sending far-future expires, we don't need ETags for
# static content.
#   developer.yahoo.com/performance/rules.html#etags
FileETag None





# ----------------------------------------------------------------------
# UTF-8 encoding
# ----------------------------------------------------------------------

# Use UTF-8 encoding for anything served text/plain or text/html
AddDefaultCharset utf-8

# Force UTF-8 for a number of file formats
AddCharset utf-8 .atom .css .js .json .rss .vtt .xml





# ----------------------------------------------------------------------
# A little more security
# ----------------------------------------------------------------------

# "-Indexes" will have Apache block users from browsing folders without a
# default document Usually you should leave this activated, because you
# shouldn't allow everybody to surf through every folder on your server (which
# includes rather private places like CMS system folders).
<IfModule mod_autoindex.c>
  Options -Indexes
</IfModule>

# Block access to "hidden" directories or files whose names begin with a
# period. This includes directories used by version control systems such as
# Subversion or Git.
<IfModule mod_rewrite.c>
  RewriteCond %{SCRIPT_FILENAME} -d [OR]
  RewriteCond %{SCRIPT_FILENAME} -f
  RewriteRule "(^|/)\." - [F]
</IfModule>

# Block access to backup and source files. These files may be left by some
# text/html editors and pose a great security danger, when anyone can access
# them.
<FilesMatch "(\.(bak|config|dist|fla|inc|ini|log|psd|sh|sql|swp)|~)$">
  Order allow,deny
  Deny from all
  Satisfy All
</FilesMatch>

# Increase cookie security
<IfModule mod_php5.c>
  php_value session.cookie_httponly true
</IfModule>
