\# UniPack - Student Portal API



A complete REST API for managing student tasks and calendar events with authentication and CRUD operations.



\## ✨ Features

\- ✅ User Authentication (Register, Login, Logout with Sanctum)

\- ✅ Task Management (Create, Read, Update, Delete)

\- ✅ Event Calendar (Create, Read, Update, Delete)

\- ✅ Category Management for Tasks

\- ✅ Form Validation \& Error Handling

\- ✅ RESTful API Architecture

\- ✅ Repository Pattern Implementation

\- ✅ Service Layer for Business Logic

\- ✅ API Resources for JSON Response Transformation

\- ✅ Eager Loading to Prevent N+1 Queries



\## 🛠 Tech Stack

\- \*\*Backend:\*\* Laravel 11

\- \*\*Database:\*\* MySQL

\- \*\*Authentication:\*\* Laravel Sanctum (API Tokens)

\- \*\*Architecture:\*\* Repository Pattern + Service Layer

\- \*\*Frontend:\*\* HTML5, Tailwind CSS, JavaScript



\## 📋 Project Structure

```

app/

├── Http/

│   ├── Controllers/Api/

│   │   ├── AuthController.php

│   │   ├── TaskController.php

│   │   ├── EventController.php

│   │   └── CategoryController.php

│   ├── Requests/

│   │   ├── Auth/

│   │   ├── Task/

│   │   └── Event/

│   └── Resources/

│       ├── UserResource.php

│       ├── TaskResource.php

│       ├── EventResource.php

│       └── CategoryResource.php

├── Models/

│   ├── User.php

│   ├── Task.php

│   ├── Event.php

│   └── Category.php

├── Repositories/

│   ├── Interfaces/

│   └── \[Repository Implementations]

├── Services/

│   ├── TaskService.php

│   ├── EventService.php

│   └── CategoryService.php

└── Providers/

&nbsp;   └── RepositoryServiceProvider.php



database/

├── migrations/

│   ├── create\_categories\_table.php

│   ├── create\_tasks\_table.php

│   └── create\_events\_table.php



routes/

└── api.php



public/

├── index.html

├── login.html

├── signup.html

└── userDashboard.html

```



\## 🚀 Getting Started



\### Prerequisites

\- PHP 8.1+

\- MySQL 8.0+

\- Composer



\### Installation



1\. \*\*Clone the repository\*\*

```bash

git clone https://github.com/yourusername/unipack.git

cd unipack

```



2\. \*\*Install dependencies\*\*

```bash

composer install

```



3\. \*\*Setup environment\*\*

```bash

cp .env.example .env

php artisan key:generate

```



4\. \*\*Configure database\*\*

Edit `.env`:

```env

DB\_CONNECTION=mysql

DB\_HOST=127.0.0.1

DB\_PORT=3306

DB\_DATABASE=saunipack

DB\_USERNAME=root

DB\_PASSWORD=

```



5\. \*\*Run migrations\*\*

```bash

php artisan migrate

```



6\. \*\*Install Sanctum\*\* (for API authentication)

```bash

php artisan vendor:publish --provider="Laravel\\Sanctum\\SanctumServiceProvider"

php artisan migrate

```



7\. \*\*Start the server\*\*

```bash

php artisan serve

```



The API will be available at: `http://localhost:8000/api`



\## 📚 API Endpoints



\### Authentication

\- `POST /api/auth/register` - Register new user

\- `POST /api/auth/login` - Login user

\- `POST /api/auth/logout` - Logout user (protected)

\- `GET /api/auth/user` - Get authenticated user (protected)



\### Tasks (Protected)

\- `GET /api/tasks` - Get all user tasks

\- `POST /api/tasks` - Create new task

\- `GET /api/tasks/{id}` - Get single task

\- `PUT /api/tasks/{id}` - Update task

\- `DELETE /api/tasks/{id}` - Delete task



\### Events (Protected)

\- `GET /api/events` - Get all user events

\- `POST /api/events` - Create new event

\- `GET /api/events/{id}` - Get single event

\- `PUT /api/events/{id}` - Update event

\- `DELETE /api/events/{id}` - Delete event



\### Categories

\- `GET /api/categories` - Get all categories

\- `POST /api/categories` - Create category

\- `GET /api/categories/{id}` - Get single category

\- `PUT /api/categories/{id}` - Update category

\- `DELETE /api/categories/{id}` - Delete category



\## 🧪 Testing with Postman



1\. Import `UniPack-API.postman\_collection.json` into Postman

2\. Register a user and copy the authentication token

3\. Add token to Authorization header: `Bearer YOUR\_TOKEN\_HERE`

4\. Test all endpoints



\### Example: Create Task

```

POST http://localhost:8000/api/tasks

Headers:

&nbsp; Authorization: Bearer YOUR\_TOKEN

&nbsp; Content-Type: application/json



Body:

{

&nbsp; "category\_id": 1,

&nbsp; "title": "Study Laravel",

&nbsp; "description": "Learn API development",

&nbsp; "due\_date": "2026-02-20",

&nbsp; "priority": "high"

}

```



\## 🎯 Database Schema



\### Users Table

\- id (Primary Key)

\- name

\- email (Unique)

\- password

\- created\_at, updated\_at



\### Tasks Table

\- id (Primary Key)

\- user\_id (Foreign Key)

\- category\_id (Foreign Key, Nullable)

\- title

\- description

\- due\_date

\- priority (low, medium, high)

\- status (pending, in\_progress, completed)

\- created\_at, updated\_at



\### Events Table

\- id (Primary Key)

\- user\_id (Foreign Key)

\- title

\- description

\- event\_date

\- event\_time

\- color

\- created\_at, updated\_at



\### Categories Table

\- id (Primary Key)

\- name

\- description

\- color

\- created\_at, updated\_at



\## 🔐 Security Features



\- \*\*Authentication:\*\* Laravel Sanctum for API token authentication

\- \*\*Validation:\*\* Form Requests validate all incoming data

\- \*\*Authorization:\*\* Users can only access their own tasks/events

\- \*\*Mass Assignment Protection:\*\* Models use $fillable to prevent injection

\- \*\*CSRF Protection:\*\* Built-in Laravel security



\## 📦 Available Commands

```bash

\# Database

php artisan migrate              # Run migrations

php artisan migrate:fresh        # Reset database

php artisan tinker              # Interactive shell



\# Routes

php artisan route:list          # List all routes



\# Development

php artisan serve               # Start development server

php artisan make:model Post     # Create model



\# Caching

php artisan cache:clear         # Clear cache

php artisan config:cache        # Cache config

```



\## 🎨 Frontend



The project includes beautiful HTML pages with Tailwind CSS:

\- `index.html` - Landing page

\- `login.html` - Login page

\- `signup.html` - Registration page

\- `userDashboard.html` - Main dashboard with tasks and calendar



All pages are fully responsive and styled with cyberpunk theme.



\## 🚀 Deployment



For production deployment:



1\. Set `APP\_DEBUG=false` in `.env`

2\. Run `php artisan config:cache`

3\. Run `php artisan route:cache`

4\. Ensure `.env` is secure and not committed to version control

5\. Use a production database (not SQLite)

6\. Enable HTTPS

7\. Setup proper error logging



\## 📖 Learning Resources



\- \[Laravel Documentation](https://laravel.com/docs)

\- \[Laravel Sanctum](https://laravel.com/docs/sanctum)

\- \[RESTful API Design](https://restfulapi.net/)

\- \[Eloquent ORM](https://laravel.com/docs/eloquent)



\## 👥 Contributing



Contributions are welcome! Please:

1\. Fork the repository

2\. Create a feature branch

3\. Commit your changes

4\. Push to the branch

5\. Create a Pull Request



\## 📝 License



This project is licensed under the MIT License - see LICENSE file for details.



\## 👨‍💻 Author



\*\*Lunos Code\*\* - Created for Software Academy



\## 📞 Support



For issues and questions, please create an issue on GitHub.



---



Made with ❤️ by LunosCode

