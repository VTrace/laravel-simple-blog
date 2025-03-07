# 🚀 Laravel 11 Blog Platform with Draft & Scheduling Feature  

This Laravel 11-based project provides a simple blog platform with post management features, including drafts and scheduled publishing.  

---

## 📌 Features  

### 🔹 Homepage  
- **Switch Content Based on Login Status**:  
  - **Authenticated Users**: See all their own posts, including drafts and scheduled posts.  
  - **Guest Users**: See links to login and registration pages.  
- **Post Status Labels**: Each post displays its status (Published, Draft, or Scheduled).  

### 🔹 Post Management  
- **Post Visibility**: Anyone (including guests) can see published posts.  
- **Post Creation**: Only authenticated users can create new posts.  
- **Post Update/Deletion**: Only the post's author can update or delete their posts.  
- **Title Length Restriction**: Post titles must be 60 characters or less.  
- **Draft & Scheduling**:  
  - Posts can be saved as drafts or scheduled for future publishing.  
  - Draft and scheduled posts remain hidden from public listing and detail pages until published.  

---

## 🛠️ Installation & Setup  

### 1️⃣ Clone the Repository  
```sh
git clone https://github.com/VTrace/laravel-simple-blog.git
cd laravel-simple-blog
```

### 2️⃣ Install Dependencies 
```sh
composer install
npm install && npm run build
```

### 3️⃣ Setup Environment
- Copy the example environment file and update the necessary settings:
```sh
cp .env.example .env
```

- Generate the application key:
```sh
php artisan key:generate
```

### 4️⃣ Configure Database
- Set up your database connection in .env:
```sh
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

- Set up your database connection in .env:
```sh
php artisan serve
```

- You can login using default admin user:
    - Email: ```admin@example.com```
    - Password: ```password```


### ✅ Running Tests
- This project includes tests to ensure correct functionality following Laravel 11 best practices.
- 📌 Test Cases
    - **Post Creation**: Only authenticated users can create posts.
    - **Post Update & Deletion**: Only the author of a post can update or delete it.
    - **Post Title Validation**: Titles must be 60 characters or less.
    - **Drafts & Scheduling**:
        - Draft and scheduled posts are not visible to the public.
        - Scheduled posts are automatically published when their scheduled time arrives.

- Run Tests
    - To execute tests, use the following command:
        ```sh
        php artisan test
        ```

### ⏳ Scheduling for Auto-Publishing
- Laravel’s scheduler handles scheduled post publishing. Ensure it's set up properly:
```sh
php artisan schedule:work
```

- Alternatively, you can set up a cron job to run Laravel's scheduler every minute:

```sh
* * * * * php /path-to-project/artisan schedule:run >> /dev/null 2>&1
```