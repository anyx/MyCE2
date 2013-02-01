#!/bin/sh

DATE=`/bin/date "+%Y%m%d"`
DB="crosswords_prod"
DEST_DIR="/var/backups/mongo"
DUMP_FLAGS=""
 
/bin/mkdir -p $DEST_DIR/$DATE
 
mongodump $DUMP_FLAGS -o $DEST_DIR/$DATE
 
cd $DEST_DIR && tar czvf $DATE.tgz $DATE

/bin/rm -r $DEST_DIR/$DATE
