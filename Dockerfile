# PHP公式イメージをベースにする
FROM php:7.4-apache

# ソースコードをコンテナにコピー
COPY . /var/www/html/

# Apacheの設定を開く
# RUN a2enmod rewrite && \
#     echo '<Directory /var/www/html/>' > /etc/apache2/conf-available/custom-conf.conf && \
#     echo '    AllowOverride All' >> /etc/apache2/conf-available/custom-conf.conf && \
#     echo '    Require all granted' >> /etc/apache2/conf-available/custom-conf.conf && \
#     echo '</Directory>' >> /etc/apache2/conf-available/custom-conf.conf && \
#     a2enconf custom-conf.conf && \
#     service apache2 restart

# RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
RUN chown -R www-data:www-data /var/www/html
