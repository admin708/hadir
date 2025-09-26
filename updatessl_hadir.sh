#!/bin/bash

# Path to Certbot executable
certbotPath="/usr/bin/certbot" # Ganti dengan jalur Certbot Anda

# Domain yang ingin Anda perbarui sertifikatnya
domain="hadir.lawfaculty.unhas.ac.id" # Ganti dengan domain Anda

# Eksekusi perintah Certbot untuk memperbarui sertifikat
$certbotPath renew --cert-name $domain

# Restart server web
sudo systemctl stop nginx
