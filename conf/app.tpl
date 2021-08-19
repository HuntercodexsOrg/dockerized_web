server {
    server_name {{{NGINX_SERVER_NAME}}};
    index index.php index.html;
    listen {{{NGINX_LISTEN}}};
    error_log  /var/log/nginx/error.log;
    access_log /var/log/nginx/access.log;
    root {{{NGINX_ROOT_PATH}}};

    location / {
        #try_files $uri /index.php?$args;
        #try_files $uri $uri/ /index.php?$query_string;
        try_files $uri /index.php$is_args$args$query_string;
    }

    #Rewrite Samples - Edit here
    #rewrite /(.+) /index.php?$1 break;
    #rewrite /app/(.+) /api/index.php?$1 break;
    #rewrite /api/(.+) /app/index.php?$1 break;

    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass {{{NGINX_FAST_CGI_PASS}}};
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
        fastcgi_read_timeout 600;
    }
}
