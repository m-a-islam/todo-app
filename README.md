# System Design: TODO apps
## Clarify Question 
### Step 1: Requirements
#### Application to Todo App:
 - **Functional:** CRUD tasks (create/read/update/delete). Features: Add task with title/description/status/due date; Filter/sort by status/date; Basic auth (login/logout) to own tasks per user.
 - **Non-Functional:** Responsive UI (Vue handles this); <1s response time; Handle 100 concurrent users initially; Secure (prevent data leaks); Reliable (backup DB).
 - **User Stories Example:** "As a busy professional, I want to mark tasks as done so I can track progress." Edge: No internet? (Offline support via Vue later.)
 - **Trade-off:** Use sessions for auth (simple) over JWT (more scalable but complex for beginners).



### Step 2: High-Level Design
### Components
- **Frontend (Vue.js):** Responsive UI, handles user interactions, displays tasks.
- **Backend (PHP symfony):** RESTful API endpoints (e.g., /api/tasks). Uses controllers for routing, services for business logic, Doctrine ORM for MySQL interaction.
- **Database (MySQL):** Stores tasks, users, and relationships. Tables: `users`, `tasks`. Relationships: One-to-many (user to tasks).
- **Authentication:** Auth (Symfony SecurityBundle), Caching (optional Redis later).

### Data Flow
1. User interacts in browser → Vue sends HTTP request (e.g., POST /api/tasks) → Symfony Controller validates → Doctrine queries MySQL → Response (JSON) back to Vue → Update UI.
2. Error Flow: Validation fails? Return 400 error; DB down? Retry or log.

### Patterns
- MVC in Symfony (Model: Entities/Repositories; View: JSON responses; Controller: Handles requests). Vue uses Composition API for reusable logic.

### Step 3: Database Design
#### Entities:
 - Users: id, username, password_hash, email.
 - Tasks: id, title, description, status (enum: pending/in_progress/done), due_date, created_at, updated_at, user_id (FK to users).
 - Relationships: One User to Many Tasks (JOIN for user-specific lists).

### Step 4: API Design
#### Endpoints (Symfony Routes):
- GET /api/tasks: List user's tasks (query params: ?status=pending&page=1).
- POST /api/tasks: Create {title, description, due_date}.
- PUT /api/tasks/{id}: Update status.
- DELETE /api/tasks/{id}.
- Auth: POST /api/login for session/JWT.
- Vue Integration: Use Axios in Vue to call these (e.g., axios.get('/api/tasks').then(updateStore)).
- Best Practice: Use OpenAPI/Swagger (Symfony bundle) to document APIs

### Step 5: Non-Functional Aspects (Scalability, Security, Reliability)
 - Scalability: Stateless Symfony (sessions in Redis if needed). MySQL replication for reads. Vue CDN for static assets.
 - Security: Symfony Validator for inputs; Hash passwords; CORS for Vue-API.
 - Reliability: Doctrine transactions for atomic ops; Error logging (Monolog in Symfony).
 - Monitoring: Add Prometheus later; Basic: Symfony Profiler.
 - Trade-off: For beginners, skip advanced caching—add when needed.

#### Step 6: Trade-Offs, Iterations, and Validation
- If grows, split to microservices (e.g., Auth service). Validate: Simulate 10 tasks
