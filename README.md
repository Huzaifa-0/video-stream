### Configuring the project
```
- composer install
- php artisan preset bootstrap
- npm install && npm run dev

# update database credentials, queue connection driver and FFmpeg binaries

DB_DATABASE=DB
DB_USERNAME=username
DB_PASSWORD=password

QUEUE_CONNECTION=database

FFMPEG_BINARIES=''
FFPROBE_BINARIES=''
```

#### Running queue worker
```
$ php artisan queue:work --tries=3 --timeout=8600
```
