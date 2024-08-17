#!/bin/bash

# Backblaze B2 settings
B2_BUCKET="backup-bucket"
B2_PREFIX="mysql_backups/tot_track"

# Calculate the date 30 days ago
THIRTY_DAYS_AGO=$(date -d "30 days ago" +"%s")
echo "THIRTY_DAYS_AGO=$THIRTY_DAYS_AGO"

# List files in the bucket with their modification dates
FILES=$(b2 ls "b2://$B2_BUCKET/$B2_PREFIX/")

# Loop through each file and check if it's older than 30 days
while read -r FILE; do
    FILE_URI="b2://$B2_BUCKET/$FILE"
    echo "FILE_URI: $FILE_URI"
    FILE_INFO=$(b2 file info "$FILE_URI")

    MODIFICATION_DATE=$(echo $FILE_INFO | jq -r '.fileInfo.src_last_modified_millis')
    echo "MODIFICATION_DATE: $MODIFICATION_DATE"

    # Check if the modification date is older than 30 days
    if [[ "$MODIFICATION_DATE" < "$THIRTY_DAYS_AGO" ]]; then
        # Delete the file
        b2 rm "$FILE_URI"
        echo "Deleted file: $FILE_URI"
    fi
done <<< "$FILES"
