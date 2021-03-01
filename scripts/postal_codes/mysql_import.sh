#!/bin/bash

. .env/mysql_creds

# echo "$user" "$pass"

mysql -u$user -p$pass < import_postal_code_data.sql