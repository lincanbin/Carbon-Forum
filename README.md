# Carbon-Forum    [![Build Status](https://travis-ci.org/lincanbin/Carbon-Forum.svg?branch=develop)](https://travis-ci.org/lincanbin/Carbon-Forum)

A high performance open-source forum software written in PHP.



## Related Projects

* [API Documentation](https://github.com/lincanbin/Carbon-Forum-API-Documentation)
* [Android Client for Carbon Forum](https://github.com/lincanbin/Android-Carbon-Forum)

## Demo/Official Website

* [项目简体中文官网: www.94cb.com](http://www.94cb.com/)
* [Project's English Official Website: en.94cb.com](http://en.94cb.com/)
* [專案繁體中文官網: tw.94cb.com](http://tw.94cb.com/)

## Requirements

* PHP version 5.4.0 or higher.
* The [__PDO_MYSQL__](http://php.net/manual/en/ref.pdo-mysql.php) PHP Package.
* MySQL version 5.0 or higher.
* The [__mod_rewrite__](http://httpd.apache.org/docs/2.2/mod/mod_rewrite.html) Apache module / [__ngx_http_rewrite_module__](https://github.com/lincanbin/Carbon-Forum/blob/master/nginx.conf) / [__ISAPI_Rewrite__](http://www.helicontech.com/isapi_rewrite/) IIS module / IIS7+. 
* The [__mod_headers__](http://httpd.apache.org/docs/2.2/mod/mod_headers.html) module is needed if you run Carbon Forum on Apache HTTP Server.

## Install

1. Ensure that the entire directory are writable.
2. Open ```http://www.yourdomain.com/install``` and install.
3. Open the Forum, and register. The first registered user will become administrator.

## Upgrade

1. Backup files( ```/upload/*``` ) and databases. 
2. Delete all files except ```/upload/*```, and upload the new version files that extract from the the latest version packet. 
3. Ensure that the entire directory are writable.
4. Open ```http://www.yourdomain.com/update``` and update.

## Features

* Mobile version. 
* Real-time notifications push. 
* Discussions Tags based with Quora/StackOverflow style. 
* High FE&BE performance. 
* Full asynchronous design, improve the loading speed. 
* Excellent search engine optimization (mobile adaptation Sitemap support) .
* Perfect draft saving mechanism. 
* The modern Notification Center (currently supported and @ replies).

## Donate for Carbon Forum

* [Donation list](http://www.94cb.com/t/2465)

* Alipay: 

![Alipay](https://www.94cb.com/upload/donate_small.png)

* Wechat: 

![Wechat](https://www.94cb.com/upload/donate_weixin_small.png)

* Paypal: 

  https://www.paypal.me/lincanbin

## Contributors

[Show all](https://github.com/lincanbin/Carbon-Forum/graphs/contributors)



## License

``` 
Copyright 2006-2017 Canbin Lin (lincanbin@hotmail.com)

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

   http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
```
