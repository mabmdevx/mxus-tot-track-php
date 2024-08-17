#!/bin/bash

# MySQL settings
DB_USER=""
DB_PASS=""
DB_NAME="tot_track"

# Backblaze B2 settings
B2_BUCKET="backup-bucket"
B2_PREFIX_PARENT="mysql_backups"
B2_PREFIX_DB_NAME="tot_track"

# Backup settings
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
BACKUP_DIR_PARENT="/opt/backups/mysql"
BACKUP_DIR_DB_NAME="tot_track"
BACKUP_FILE="$BACKUP_DIR_PARENT/$BACKUP_DIR_DB_NAME/${DB_NAME}_${TIMESTAMP}.sql"
B2_FILE="$B2_PREFIX_PARENT/$B2_PREFIX_DB_NAME/${DB_NAME}_${TIMESTAMP}.sql"
echo "BACKUP_FILE: $BACKUP_FILE"
echo "B2_FILE: $B2_FILE"

# Dump the MySQL database
mysqldump -u$DB_USER -p$DB_PASS $DB_NAME > $BACKUP_FILE

# Keep last copy in local
BACKUP_FILE_LATEST_COPY="$BACKUP_DIR_PARENT/$BACKUP_DIR_DB_NAME/${DB_NAME}_latest.sql"
echo "BACKUP_FILE_LATEST_COPY: $BACKUP_FILE_LATEST_COPY"
rm -f $BACKUP_FILE_LATEST_COPY
cp $BACKUP_FILE $BACKUP_FILE_LATEST_COPY

# Upload backup to Backblaze B2
b2 upload-file $B2_BUCKET $BACKUP_FILE $B2_FILE

# Clean up local backup file
rm $BACKUP_FILE
