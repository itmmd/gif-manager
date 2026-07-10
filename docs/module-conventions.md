# قراردادهای ساختاری ماژول‌ها (Module Conventions)

## استک پروژه

- **Laravel 13** + **Livewire 4** + **Alpine.js** + **Tailwind CSS 4**
- معماری: **Modular** با استفاده از `nwidart/laravel-modules`
- پنل ادمین: **Gentelella v4**

---

## ساختار داخلی هر ماژول

هر ماژول باید دقیقاً از ساختار زیر پیروی کند:

```
Modules/<ModuleName>/
├── app/
│   ├── Http/
│   │   ├── Controllers/        # کنترلرهای HTTP
│   │   └── Livewire/           # کامپوننت‌های Livewire مخصوص این ماژول
│   ├── Models/                 # مدل‌های Eloquent
│   └── Providers/              # Service Providerهای ماژول
├── config/
│   └── config.php              # تنظیمات اختصاصی ماژول
├── database/
│   ├── factories/              # Factoryهای تست
│   ├── migrations/             # Migrationهای مخصوص ماژول
│   └── seeders/                # Seederها
├── resources/
│   └── views/                  # Blade viewها
│       ├── components/         # کامپوننت‌های Blade
│       └── livewire/           # ویوهای Livewire
├── routes/
│   ├── web.php                 # مسیرهای وب
│   └── api.php                 # مسیرهای API (در صورت نیاز)
├── tests/
│   ├── Feature/                # تست‌های Feature
│   └── Unit/                   # تست‌های Unit
└── module.json                 # متادیتای ماژول
```

---

## قانون نام‌گذاری (Bounded Context)

هر ماژول یک **Bounded Context** مستقل است. نام ماژول باید کوتاه، معنادار و به صورت **StudlyCase** باشد.

### ماژول‌های تعریف‌شده در این پروژه:

| ماژول | مسئولیت |
|-------|---------|
| `Core` | قراردادها (Contracts)، abstraction‌های مشترک، زیرساخت پایه |
| `Admin` | پنل ادمین، layout و داشبورد |
| `Auth` | احراز هویت، ثبت‌نام، ورود و خروج |
| `Gif` | مدیریت GIF‌ها: آپلود، پردازش، نمایش |
| `Tag` | مدیریت تگ‌ها و برچسب‌گذاری |
| `Category` | مدیریت دسته‌بندی‌ها |

---

## قراردادهای کدنویسی

### Livewire Components
- تمام کامپوننت‌های Livewire هر ماژول داخل `app/Http/Livewire/` قرار می‌گیرند.
- namespace: `Modules\<ModuleName>\Http\Livewire`
- ویوی متناظر: `resources/views/livewire/<component-name>.blade.php`
- برای full-page components از `#[Layout('module-alias::layouts.layout-name')]` استفاده کنید.

### Routes
- هر ماژول route‌های خودش را در `routes/web.php` داخل همان ماژول تعریف می‌کند.
- prefix مسیرهای ادمین: `/admin`
- prefix مسیرهای API: `/api/v1`

### Views و Blade
- alias نام ماژول به صورت lowercase در view‌ها استفاده می‌شود:
  - مثال: `admin::layouts.admin`، `core::components.button`
- کلاس‌های Tailwind مستقیماً در blade فایل‌ها نوشته می‌شوند.
- Alpine.js برای تعاملات ساده فرانت‌اند بدون نیاز به Livewire.

### Models
- namespace: `Modules\<ModuleName>\Models`
- هر مدل migration مختص خود را در `database/migrations/` همان ماژول دارد.

### Contracts (اینترفیس‌های مشترک)
- اینترفیس‌های مشترک بین چند ماژول در `Modules\Core\Contracts` تعریف می‌شوند.
- پیاده‌سازی‌های پیش‌فرض در `Modules\Core\Services` قرار می‌گیرند.

---

## قوانین کلی

1. **هیچ ماژولی نباید مستقیماً به کلاس‌های داخلی ماژول دیگری وابسته باشد** — ارتباط فقط از طریق Contracts.
2. **Migration‌ها داخل ماژول می‌مانند** و با `php artisan module:migrate <ModuleName>` اجرا می‌شوند.
3. **هر ماژول باید تست‌های خود را داشته باشد** در پوشه `tests/` همان ماژول.
4. **Assets فرانت‌اند عمومی** در `resources/css/app.css` و `resources/js/app.js` پروژه اصلی قرار دارند.
5. **Assets اختصاصی پنل ادمین** در `public/admin-assets/` قرار دارند و از Gentelella build شده‌اند.

---

## دستورات پرکاربرد

```bash
# ساخت ماژول جدید
php artisan module:make <ModuleName>

# لیست ماژول‌های موجود
php artisan module:list

# اجرای migration یک ماژول
php artisan module:migrate <ModuleName>

# ساخت Livewire component داخل ماژول
php artisan make:livewire <ModuleName>/ComponentName

# فعال/غیرفعال کردن ماژول
php artisan module:enable <ModuleName>
php artisan module:disable <ModuleName>
```
