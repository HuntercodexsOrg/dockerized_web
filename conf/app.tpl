server {
    index index.php index.html;
    listen {{{NGINX_LISTEN}}};
    error_log  /var/log/nginx/error.log;
    access_log /var/log/nginx/access.log;
    root {{{NGINX_ROOT_PATH}}};
    location / {
        try_files $uri /index.php?$args;
    }
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
