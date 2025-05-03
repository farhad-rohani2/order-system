مراحل اجرا

1. کلون کردن پروژه

```bash
git clone https://github.com/farhad-rohani2/order-system
cd order-system
```

2. کپی فایل env

```bash
cp .env.example .env
```

3. تنظیم پورت‌های دلخواه (اختیاری)
   فایل .env

```env
APP_PORT=8000
VITE_PORT=5173
FORWARD_DB_PORT=3307
FORWARD_REDIS_PORT=6380
```

4. اجرای سرویس‌ها

```bash
docker-compose up -d
```

5. نصب پکیج‌ها

```bash
docker-compose exec laravel.test composer install
```

افزودن sail به ترمینال (alias)

 ```bash
echo "alias sail='[ -f vendor/bin/sail ] && sh vendor/bin/sail || echo \"Sail not found\"" >> ~/.bashrc
source ~/.bashrc
```


6.
ادامه مراحل

 ```bash
sail php artisan key:generate
sail php artisan migrate --seed
sail npm install
sail npm run dev
```

7.

دستورات کاربردی

 ```bash
sail up -d               # اجرای کانتینرها در پس‌زمینه
sail down                # توقف سرویس‌ها
sail ps                   #بررسی وضعیت کانتینرها
sail logs -f              #لاگ‌ها
```

8.
http://localhost:8000

9.کاربران:

ادمین

username:admin@example.com
password:admin@example.com

کاربر معمولی

username:simple-user@example.com
password:simple-user@example.com
