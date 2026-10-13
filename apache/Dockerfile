FROM httpd:2.4-alpine

#Habilitar módulos de proxy para FastCGI
RUN sed -i '/^#LoadModule proxy_module/s/^#//' conf/httpd.conf \
    && sed -i '/^#LoadModule proxy_fcgi_module/s/^#//' conf/httpd.conf \
    && sed -i '/^#LoadModule socache_shmcb_module/s/^#//' conf/httpd.conf