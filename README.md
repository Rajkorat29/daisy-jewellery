# 💎 Daisy — Handcrafted Jewellery E-Commerce & Admin Panel

Complete Laravel 11 Jewellery E-Commerce Storefront + Admin Panel with **33 real product images**, responsive **New Drops** showcase, login-protected Cart & Checkout, and full Android/Desktop responsiveness.

---

## 🔐 Admin Login Credentials

| Role | Email | Password | URL |
| :--- | :--- | :--- | :--- |
| **Admin** | `krishu@daisy.test` | `krisha1247` | `http://127.0.0.1:8000/admin` |

> **Note:** Customers can register a new account from `/register` or `/login`.

---

## 📤 PART 1: Is (Current) Laptop se Project GitHub par Push Karne ke Steps

1. **GitHub par New Repository banayein:**
   - [https://github.com/new](https://github.com/new) par jayein.
   - Repository name dalein (jaise: `daisy-jewellery`).
   - **Create repository** button par click karein aur repository ka URL copy kar lein (jaise: `https://github.com/your-username/daisy-jewellery.git`).

2. **Project folder (`kira`) me Terminal / PowerShell open karein aur ye commands chalayein:**
   ```bash
   # 1. Agar git init nahi hai to initialize karein
   git init

   # 2. Saari files, real product images, build assets aur database stage karein
   git add .

   # 3. Commit banayein
   git commit -m "Complete Daisy Jewellery project with real images and database"

   # 4. Main branch set karein
   git branch -M main

   # 5. Apna GitHub repo URL connect karein (agar pehle se connected hai to: git remote set-url origin <URL>)
   git remote add origin https://github.com/your-username/daisy-jewellery.git

   # 6. GitHub par push karein
   git push -u origin main
   ```

---

## 💻 PART 2: Naye (Fresh) Laptop me Zero se Setup Karne ke Steps (Jisme Kuch Bhi Install Nahi Hai)

Agar naye laptop me abhi kuch bhi install nahi hai, to niche diye gaye **Step 1 se Step 5** line se follow karein:

---

### Step 1: Zaroori Software Download & Install Karein (Sirf 1 Baar)

1. **Git Install Karein:**
   - Download link: [https://git-scm.com/download/win](https://git-scm.com/download/win)
   - Installer chalayein aur `Next -> Next -> Install` kar dein.

2. **XAMPP (PHP 8.2+ & MySQL) Install Karein:**
   - Download link: [https://www.apachefriends.org/download.html](https://www.apachefriends.org/download.html) (PHP 8.2 version download karein).
   - Install hone ke baad `C:\xampp\php\php.ini` file ko Notepad me open karein aur in lines ke aage se semicolon (`;`) hata dein (agar laga ho) taaki extensions enable ho jayein:
     ```ini
     extension=fileinfo
     extension=gd
     extension=pdo_mysql
     extension=pdo_sqlite
     extension=sqlite3
     extension=zip
     ```
   - File save (`Ctrl + S`) kar dein.

3. **Composer (PHP Package Manager) Install Karein:**
   - Download link: [https://getcomposer.org/Composer-Setup.exe](https://getcomposer.org/Composer-Setup.exe)
   - Install karte waqt jab PHP path puche to `C:\xampp\php\php.exe` select karein aur **"Add this PHP to your path"** checkbox par tick zaroor lagayein.

4. **Node.js (Optional - Frontend CSS/JS edit karne ke liye) Install Karein:**
   - Download link: [https://nodejs.org/](https://nodejs.org/) (LTS version download & install karein).

> ⚠️ **Important:** Sab install hone ke baad apna Terminal / PowerShell ya VS Code ek baar band karke dobara open karein.

---

### Step 2: Naye Laptop par GitHub se Project Clone Karein

Desktop ya jahan project rakhna ho wahan PowerShell / Terminal open karein:

```bash
# 1. GitHub se project download (clone) karein
git clone https://github.com/your-username/daisy-jewellery.git

# 2. Project folder ke andar jayein
cd daisy-jewellery
```

---

### Step 3: Project Packages aur `.env` File Setup Karein

Ab project folder ke andar ye commands run karein:

```bash
# 1. PHP vendor packages install karein
composer install

# 2. Environment (.env) file banayein
copy .env.example .env

# 3. Laravel app key generate karein
php artisan key:generate
```

---

### Step 4: Database Setup Karein (Dono me se koi 1 Option chunein)

#### ✅ Option A: XAMPP (MySQL) ke saath chalana hai (Recommended)
1. **XAMPP Control Panel** open karein aur **Apache** + **MySQL** ke aage **Start** par click karein.
2. Browser me [http://localhost/phpmyadmin](http://localhost/phpmyadmin) open karein.
3. Left side me **New** par click karein, database ka naam **`daisy`** rakhein aur **Create** par click karein.
4. Ab terminal me ye ek command chalayein (ye saari 18 tables, 33 products + images, drops aur admin account apne aap bana degi):
   ```bash
   php artisan migrate:fresh --seed
   ```

#### ✅ Option B: Bina XAMPP/MySQL ke Direct (SQLite) chalana hai
Project me `database/database.sqlite` file pehle se saare 33 products aur images ke saath ready hai!
1. Bas apni `.env` file open karein aur `DB_CONNECTION` ko aise set karein:
   ```env
   DB_CONNECTION=sqlite
   # DB_HOST=127.0.0.1
   # DB_PORT=3306
   # DB_DATABASE=daisy
   # DB_USERNAME=root
   # DB_PASSWORD=
   ```

---

### Step 5: Website Start Karein 🚀

Ab terminal me ye command chalayein:

```bash
php artisan serve
```

Ab apne browser me open karein:
- **Customer Website:** [http://127.0.0.1:8000](http://127.0.0.1:8000)
- **Admin Panel:** [http://127.0.0.1:8000/admin](http://127.0.0.1:8000/admin)
  - **Email:** `krishu@daisy.test`
  - **Password:** `krisha1247`

---

## 🛠️ Useful Commands (Agar kabhi CSS/JS me badlav karein)

Waise to compiled CSS/JS (`public/build`) aur saari product images (`public/storage/products`) pehle se GitHub me hain, lekin agar aap future me `resources/css` ya `resources/js` me koi change karein to:

```bash
npm install
npm run build
php artisan view:clear
```
