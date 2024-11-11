# Task List Application

## Problem Description

The Task List Application enables users to manage tasks by creating, updating, marking as complete, and deleting them. Each user has a private space for managing tasks, with authentication and permissions ensuring secure access.

## Stack and Tools

- Laravel 10.10
- Vue.js with Inertia.js for SPA functionality
- MySQL
- TailwindCSS
- Laravel Jetstream for authentication
- Vue Toastification for notifications

## Project Structure

This application separates concerns between a Laravel backend and a Vue.js frontend, using Inertia.js for seamless SPA interactions. **Vuex** is used for centralized state management, while **Vue Toastification** provides notifications for user actions.

### Backend

- **TaskController**: Manages all task actions:
  - **index**: Lists all tasks for the authenticated user.
  - **store**: Creates a new task.
  - **markComplete**: Marks a task as complete.
  - **destroy**: Deletes a task if authorized.

To ensure data integrity and security, only the owner of a task can modify it. The `markComplete` and `destroy` actions enforce this requirement.

### Frontend

The frontend includes structured components organized within **TaskPage**:

- **TaskPage**: The main page displaying all task management functionality.
- **TaskList**: Renders the list of tasks from the backend and updates on task actions.
- **TaskItem**: Displays individual task details with options to mark as complete or delete.
- **TaskForm**: Allows users to create new tasks with a title and optional description.

**Vuex** manages task state through a centralized **task store** where core logic and requests are handled, including task creation, deletion, and updates. **Vue Toastification** provides feedback for actions, improving the user experience with clear, non-intrusive notifications.

## Requirements

- PHP >= 8.0
- Composer
- Node.js and npm
- MySQL

## Installation and Setup

1. **Clone the Repository**:

   ```bash
   git clone https://github.com/your-username/task-list-app.git
   cd task-list-app
   ```

2. **Install Dependencies**:

   ```bash
   composer install
   npm install
   ```

3. **Environment Setup**:

   - Copy `.env.example` to `.env`
   - Update database settings:
     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=your_database_name
     DB_USERNAME=your_database_user
     DB_PASSWORD=your_database_password
     ```

4. **Run Migrations**:

   ```bash
   php artisan migrate
   ```

5. **Run the Application**:

   ```bash
   php artisan serve
   npm run dev
   ```

5. **Run the Database Seeder:**:

   - Run the following command to seed the database with a default user:

   ```bash
   php artisan db:seed --class=UserSeeder
   ```
   The seeded user credentials are:

    - Email: test@example.com
    - Password: password

Visit the app at `http://localhost:8000`.

## Running Tests

To run the tests for the application, including tests for the TaskController, use the following command:

```bash
php artisan test
```

To run only the TaskController tests with a filter, use the command:

```bash
php artisan test --filter=TaskControllerTest
```

