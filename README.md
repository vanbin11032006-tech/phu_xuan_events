# 🎓 Phu Xuan Events – Hệ thống Quản lý Sự kiện Trường Đại học

**Bài tập lớn IT3042 – Lập trình Backend Laravel**  
Trường Đại học Phú Xuân | Khoa Công nghệ Thông tin | Học kỳ 1, Năm học 2024–2025

---

## 📌 Mô tả dự án

**phu-xuan-events** là ứng dụng web quản lý sự kiện nội bộ cho Trường Đại học Phú Xuân, xây dựng bằng **Laravel 10**. Hệ thống cho phép:

- **Admin** quản lý toàn bộ sự kiện, phê duyệt đăng ký, xem báo cáo và xuất CSV.
- **Organizer (Ban tổ chức/Giảng viên)** tạo và quản lý sự kiện của mình, xem danh sách sinh viên đăng ký.
- **Student (Sinh viên)** xem danh sách sự kiện công khai, đăng ký tham gia, hủy đăng ký, xem lịch sự kiện cá nhân.

---

## 👥 Thành viên nhóm & Phân công

| STT | Họ và tên | MSSV | Vai trò / Phân công |
|-----|-----------|------|---------------------|
| 1   | _(tên thành viên 1)_ | _(MSSV)_ | Database Design, Models, Migrations, Seeders |
| 2   | _(tên thành viên 2)_ | _(MSSV)_ | Web Controllers, Blade Views, Authorization |
| 3   | _(tên thành viên 3)_ | _(MSSV)_ | RESTful API, Sanctum, Postman Collection |

---

## ⚙️ Yêu cầu hệ thống

| Công cụ | Phiên bản tối thiểu |
|---------|---------------------|
| PHP | 8.1+ |
| Composer | 2.x |
| MySQL | 8.0+ |
| Node.js | 18.x+ |
| NPM | 9.x+ |

---

## 🚀 Hướng dẫn cài đặt

### Bước 1 – Clone dự án

```bash
git clone https://github.com/<your-org>/phu-xuan-events.git
cd phu-xuan-events
```

### Bước 2 – Cài đặt phụ thuộc PHP

```bash
composer install --no-security-blocking
```

### Bước 3 – Cài đặt phụ thuộc Node.js

```bash
npm install
```

### Bước 4 – Tạo file cấu hình môi trường

```bash
cp .env.example .env
php artisan key:generate
```

Mở file `.env` và cập nhật thông tin database:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=phu_xuan_events
DB_USERNAME=root
DB_PASSWORD=
```

### Bước 5 – Tạo database MySQL

```bash
php -r "(new PDO('mysql:host=127.0.0.1', 'root', ''))->query('CREATE DATABASE IF NOT EXISTS phu_xuan_events');"
```

### Bước 6 – Chạy Migration và Seeder

```bash
php artisan migrate:fresh --seed
```

> Seeder tạo tổng cộng: 3 Admin + 10 Organizer + 50 Student + 8 Categories + 18 Tags + 60 Events + ~546 Registrations

### Bước 7 – Tạo storage symbolic link (cho upload ảnh banner)

```bash
php artisan storage:link
```

### Bước 8 – Khởi chạy ứng dụng

```bash
php artisan serve
```

Truy cập tại: **http://127.0.0.1:8000**

---

## 🔑 Tài khoản test mặc định

| Vai trò | Email | Mật khẩu | Mô tả |
|---------|-------|----------|-------|
| **Admin** | admin@pxu.edu.vn | password | Toàn quyền quản trị hệ thống |
| **Organizer** | organizer@pxu.edu.vn | password | Tạo & quản lý sự kiện |
| **Student** | student@pxu.edu.vn | password | Đăng ký và theo dõi sự kiện |

---

## 🌐 Danh sách tính năng Web

| Mã | Chức năng | URL | Auth |
|----|-----------|-----|------|
| M1.1 | Đăng ký tài khoản | `/register` | Guest |
| M1.2 | Đăng nhập / Đăng xuất | `/login` | Guest |
| M1.3 | Hồ sơ cá nhân | `/profile` | Tất cả |
| M2.1 | Danh sách sự kiện công khai | `/events` | Public |
| M2.2 | Chi tiết sự kiện | `/events/{id}` | Public |
| M2.3 | Tạo sự kiện mới | `/events/create` | Organizer, Admin |
| M2.3 | Sửa sự kiện | `/events/{id}/edit` | Owner, Admin |
| M2.3 | Xóa sự kiện | `DELETE /events/{id}` | Owner, Admin |
| M2.4 | Upload ảnh banner | (trong form tạo/sửa) | Organizer, Admin |
| M2.5 | Lọc sự kiện | `/events?category=&date=` | Public |
| M2.6 | Tìm kiếm fulltext | `/events?search=` | Public |
| M3.1 | Đăng ký tham gia sự kiện | `POST /registrations` | Student |
| M3.2 | Hủy đăng ký | `DELETE /registrations/{id}` | Student (Owner) |
| M3.3 | Xem danh sách người đăng ký | `/events/{id}` (admin panel) | Organizer, Admin |
| M3.4 | Phê duyệt / Từ chối đăng ký | `/admin/dashboard` | Admin |
| M3.5 | "Sự kiện của tôi" | `/my-registrations` | Student |
| M5.1 | Dashboard Admin | `/admin/dashboard` | Admin |
| M5.2 | Xuất CSV đăng ký | `/admin/export-csv` | Admin |

---

## 📡 Danh sách API Endpoints

Base URL: `http://127.0.0.1:8000/api/v1`

### Auth

| Method | Endpoint | Auth | Mô tả |
|--------|----------|------|-------|
| `POST` | `/auth/login` | Public | Đăng nhập, lấy Sanctum token |
| `POST` | `/auth/register` | Public | Đăng ký tài khoản mới (student/organizer) |
| `POST` | `/auth/logout` | Bearer Token | Đăng xuất, thu hồi token |
| `GET` | `/auth/me` | Bearer Token | Lấy thông tin user hiện tại |

### Events (M4.1, M4.2)

| Method | Endpoint | Auth | Query Params |
|--------|----------|------|-------------|
| `GET` | `/events` | Public | `?search=&category=&status=&from_date=&to_date=` |
| `GET` | `/events/{id}` | Public | — |

### Registrations (M4.4, M4.5)

| Method | Endpoint | Auth | Mô tả |
|--------|----------|------|-------|
| `GET` | `/user/registrations` | Bearer Token | Danh sách đăng ký của tôi |
| `POST` | `/registrations` | Bearer Token | Đăng ký tham gia sự kiện |
| `DELETE` | `/registrations/{id}` | Bearer Token | Hủy đăng ký |

### Ví dụ Request – Login

```http
POST /api/v1/auth/login
Content-Type: application/json
Accept: application/json

{
  "email": "student@pxu.edu.vn",
  "password": "password"
}
```

### Ví dụ Response – Events List

```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Hội thảo AI & Machine Learning",
      "slug": "hoi-thao-ai-machine-learning",
      "location": "Hội trường A",
      "start_time": "2026-08-01T08:00:00+07:00",
      "capacity": 200,
      "registered_count": 45,
      "is_full": false,
      "status": "published",
      "category": { "id": 1, "name": "Công nghệ thông tin" },
      "tags": [{ "id": 1, "name": "AI" }, { "id": 2, "name": "Machine Learning" }]
    }
  ],
  "meta": {
    "pagination": {
      "total": 60,
      "current_page": 1,
      "total_pages": 4,
      "has_more_pages": true
    }
  }
}
```

---

## 🗄️ Cấu trúc Database

```
users            (id, name, email, password, role, phone, avatar)
categories       (id, name, slug, description)
events           (id, title, description, location, start_time, end_time,
                  capacity, status, user_id→users, category_id→categories,
                  image, deleted_at)
tags             (id, name, slug)
event_tag        (event_id, tag_id)   ← pivot many-to-many
registrations    (id, user_id, event_id, status, note)
```

---

## 📁 Cấu trúc thư mục chính

```
phu-xuan-events/
├── app/
│   ├── Http/Controllers/         # Web Controllers (Event, Registration, Admin)
│   ├── Http/Controllers/Api/     # API Controllers (Auth, Event, Registration)
│   ├── Http/Requests/            # FormRequests (EventRequest)
│   ├── Http/Resources/           # API Resources (EventResource, UserResource, RegistrationResource)
│   ├── Models/                   # Eloquent Models
│   └── Policies/                 # EventPolicy
├── database/
│   ├── factories/                # UserFactory, EventFactory
│   ├── migrations/               # 6 migration files
│   └── seeders/                  # DatabaseSeeder, CategorySeeder, TagSeeder
├── resources/views/
│   ├── layouts/                  # app.blade.php, guest.blade.php
│   ├── components/               # event-card.blade.php
│   ├── auth/                     # login, register
│   ├── events/                   # index, show, create, edit
│   ├── registrations/            # my.blade.php
│   └── admin/                    # dashboard.blade.php
├── routes/
│   ├── web.php                   # Web routes
│   └── api.php                   # API v1 routes
├── postman_collection.json       # Postman test collection
├── .env.example                  # Environment template
└── README.md
```

---

## 🧰 Kiến thức tích hợp theo buổi học

| Buổi | Kiến thức | File minh chứng |
|------|-----------|-----------------|
| 1 | Môi trường & Cấu trúc | `.env`, `README.md`, `artisan serve` |
| 2 | Routing & Controller | `routes/web.php`, `routes/api.php`, named routes trong Blade |
| 3 | Blade & Layout | `layouts/app.blade.php`, `components/event-card.blade.php`, `@extends/@yield/@csrf` |
| 4 | Validation | `Http/Requests/EventRequest.php`, messages tiếng Việt, `@error` Blade |
| 5 | Migration | 6 migration files, FK constraints, softDeletes, timestamps |
| 6 | Eloquent CRUD | `$fillable`, `$casts`, scopes `published()`, `upcoming()`, soft delete |
| 7 | Relationships | `belongsTo`, `hasMany`, `belongsToMany`, eager loading với `with()` |
| 8 | Factory & Seeder | `UserFactory`, `EventFactory`, `CategorySeeder`, `paginate(12)` |
| 9 | Authentication | Laravel Breeze, middleware `auth`, role-based redirect |
| 10 | Authorization | `EventPolicy`, `@can` Blade directive, `$this->authorize()` |
| 11 | RESTful API | `EventResource`, `RegistrationResource`, `/api/v1/`, JSON pagination |
| 12 | Sanctum | `HasApiTokens`, `createToken()`, Bearer token, `auth:sanctum` middleware |

---

## 🔗 Link Demo

_(Cập nhật sau khi deploy)_

---

## 📸 Screenshots

_(Thêm ít nhất 5 ảnh chụp màn hình các trang chính sau khi hoàn thiện)_

1. Trang danh sách sự kiện (`/events`)
2. Chi tiết sự kiện + Form đăng ký (`/events/{id}`)
3. Form tạo sự kiện mới (`/events/create`)
4. Dashboard Admin + Bảng phê duyệt (`/admin/dashboard`)
5. Trang "Sự kiện của tôi" của sinh viên (`/my-registrations`)
#   p h u _ x u a n _ e v e n t s  
 #   p h u _ x u a n _ e v e n t s  
 