server {
    listen 80 default_server;
    server_name {{{NGINX_SERVER_NAME}}};
    root {{{NGINX_ROOT_PATH}}};
    client_max_body_size 64M;

    set $http_x_device "desktop";

    if ($http_user_agent ~* "(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino") {
        set $http_x_device "mobile";
    }

    # Configure the resource access here
    location ~ (^/resource.php) {
        rewrite ^/fc-core/(.*)      /$1 break;
        proxy_set_header            X-Real-IP             $remote_addr;
        proxy_set_header            X-Forwarded-For       $proxy_add_x_forwarded_for;
        proxy_pass                  http://127.0.0.1:8081;
        break;
    }

    ######################################################################################
    # Rewrites should be made here
    rewrite /app/v1/(.*)                           /app/v1/index.php?$1 break;

    ######################################################################################
    # APP 1

    location / {
        root {{{NGINX_ROOT_PATH}}};
        index  index.php index.html index.htm;
    }

    ######################################################################################
    # App1 - Rewrites should be made here

    rewrite \-([0-9]+).html?(.*) /index.php?id_product=$1&$2 break; # PRODUCTS
    rewrite /static/(.*-)([0-9]+)_([0-9]+)x([0-9]+)\.jpg /static/photos.php?name=$1&id=$2&width=$3&height=$4 break; # MIDIAS
    rewrite /static/(.*) /static/$1 break; # STATIC FILES
    rewrite /ajax/(.*) /index.php?get_params=$1 last;

    # Configure your media resource here
    location ~ (^/midia_resource.php) {
        rewrite ((.+)[/]?)+? /index.php break; # MIDIA CATEGORY
    }

    ######################################################################################
    # NGINX Server Conf

    location ~ \.php$ {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_param PATH_INFO $fastcgi_path_info;
        fastcgi_param HTTP_X_DEVICE $http_x_device;
        fastcgi_param SCRIPT_FILENAME  $document_root$fastcgi_script_name;
        fastcgi_pass {{{NGINX_FAST_CGI_PASS}}};
        fastcgi_index index.php;
        include fastcgi_params;
    }
}

server {
    listen 443;
    server_name {{{NGINX_SERVER_NAME}}};
    root {{{NGINX_ROOT_PATH}}};

    location ~ .* {
        proxy_pass http://127.0.0.1:8383;
    }
}
