server{
    listen 80 default_server;
    listen 443 ssl;
    server_name {{{NGINX_SERVER_NAME}}};
    ssl_certificate /etc/ssl/certs/localhost.crt;
    ssl_certificate_key /etc/ssl/certs/localhost.key;
    root {{{NGINX_ROOT_PATH}}};
    client_max_body_size 64M;

    set $http_x_device "desktop";

    if ($http_user_agent ~* "(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino") {
        set $http_x_device "mobile";
    }

    #####################################################################################
    # APP 1

    location / {
        root {{{NGINX_ROOT_PATH}}};
        index  index.php index.html index.htm;
        fastcgi_read_timeout 240;
        proxy_http_version 1.1;
        proxy_set_header Connection "";
    }

    #Rewrites sample to App 1
    rewrite \-([0-9]+).html?(.*) /index.php?id_product=$1&$2 break; # PRODUCTS
    rewrite /resources/(.*)      /resources/$1 break;               # STATIC FILES
    rewrite ((.+)[/]?)+?         /index.php break;                  # DEFAULT

    ######################################################################################
    # NGINX SERVER CONF

    location ~ \.php$ {
        #fastcgi_split_path_info ^(.+\.php)(/.+)$;
        #fastcgi_param PATH_INFO $fastcgi_path_info;
        fastcgi_param  HTTP_X_DEVICE $http_x_device;
        fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
        fastcgi_pass   {{{NGINX_FAST_CGI_PASS}}};
        fastcgi_buffers 16 16k;
        fastcgi_buffer_size 32k;
        fastcgi_index  index.php;
        include        fastcgi_params;

         if ($request_method = 'OPTIONS') {
            add_header 'Access-Control-Allow-Origin' '*';
            add_header 'Access-Control-Allow-Credentials' 'true';
            add_header 'Access-Control-Allow-Methods' 'GET, POST, PUT, OPTIONS';
            add_header 'Access-Control-Allow-Headers' 'X-Auth,Authorization,DNT,X-CustomHeader,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,MMSESSID';
            add_header 'Access-Control-Max-Age' 1728000;

            return 200;
        }

        if ($request_method = 'POST') {
            add_header 'Access-Control-Allow-Origin' '*';
            add_header 'Access-Control-Allow-Credentials' 'true';
            add_header 'Access-Control-Allow-Methods' 'GET, POST, PUT, OPTIONS';
            add_header 'Access-Control-Allow-Headers' 'X-Auth,Authorization,DNT,X-CustomHeader,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,MMSESSID';
            add_header 'Access-Control-Max-Age' 1728000;
        }

        if ($request_method = 'GET') {
            add_header 'Access-Control-Allow-Origin' '*';
            add_header 'Access-Control-Allow-Credentials' 'true';
            add_header 'Access-Control-Allow-Methods' 'GET, POST, PUT, OPTIONS';
            add_header 'Access-Control-Allow-Headers' 'X-Auth,Authorization,DNT,X-CustomHeader,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,MMSESSID';
            add_header 'Access-Control-Max-Age' 1728000;
        }


        if ($request_method = 'DELETE') {
            add_header 'Access-Control-Allow-Origin' '*';
            add_header 'Access-Control-Allow-Credentials' 'true';
            add_header 'Access-Control-Allow-Methods' 'GET, POST, PUT, OPTIONS';
            add_header 'Access-Control-Allow-Headers' 'X-Auth,Authorization,DNT,X-CustomHeader,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,MMSESSID';
            add_header 'Access-Control-Max-Age' 1728000;
        }


        if ($request_method = 'PUT') {
            add_header 'Access-Control-Allow-Origin' '*';
            add_header 'Access-Control-Allow-Credentials' 'true';
            add_header 'Access-Control-Allow-Methods' 'GET, POST, PUT, OPTIONS';
            add_header 'Access-Control-Allow-Headers' 'X-Auth,Authorization,DNT,X-CustomHeader,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,MMSESSID';
            add_header 'Access-Control-Max-Age' 1728000;
        }
    }
}

server{
        listen 443;
        server_name {{{NGINX_SERVER_NAME}}};
        root {{{NGINX_ROOT_PATH}}};

        location ~ .* {
            proxy_pass http://127.0.0.1:8383;
        }
}
