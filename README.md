# Chat Reply System (CRS) – Laravel Project

## 1. Project Overview
CRS (Chat Reply System) is a Laravel-based SaaS platform that allows users to send and receive chat messages with automated replies and real-time updates. The system demonstrates modern Laravel development practices including authentication, role-based access control, subscription billing, real-time notifications, background job processing, and API-driven architecture.

## 2. Technologies Used
- **Laravel** – Backend framework
- **Spatie Permission** – Role and permission management
- **Laravel Cashier** – Stripe subscription billing
- **Stripe** – Payment processing
- **Pusher** – Real-time notifications
- **Laravel Queues** – Background job processing
- **Laravel Events** – Event-driven architecture
- **Laravel Policies** – Authorization control
- **Laravel Sanctum** – API authentication

## 3. Core Modules
- **User Management Module** – Registration, login, email verification, profile management.
- **Role & Permission Module** – Roles, permissions, and route protection using Spatie.
- **Chat Management Module** – Chat threads, sending messages, automated replies.
- **Real-Time Notification Module** – Instant updates using Pusher broadcasting.
- **Subscription Management Module** – Plan subscription, upgrades, downgrades using Stripe Cashier.
- **Notification System** – Email and database notifications.
- **Admin Management Module** – Admin dashboard to manage users, chats, and subscriptions.
- **Queue & Job Processing** – Background processing of heavy tasks (e.g., auto-replies).
- **API Module** – REST APIs secured with Sanctum authentication.

## 4. Subscription Plans
- **Free** – Limited messages (50/month)
- **Basic** – Unlimited messages
- **Pro** – Advanced chat analytics and premium features

## 5. Event Driven Architecture
**Example Flow:**
`User Sends Message` → `MessageSent Event Fired` → `Listener Broadcasts Event` → `Frontend Receives Update (Pusher)`

**Auto-Reply Flow:**
`Message Received` → `ProcessAutoReply (Queue Job)` → `AutoReplyService Matches Keyword` → `Reply Message Created` → `MessageSent Event (Broadcasted)`

## 6. Security & Authorization
- **Policies**: `ChatPolicy`, `UserPolicy`, `SubscriptionPolicy`
- **Middleware**: `auth`, `role:Admin`, `permission:manage-users`
- **Rate Limiting**: Applied to APIs to prevent abuse.

## 7. Folder Structure
```text
app/
├── Actions/        # Business logic classes
├── DTOs/           # Data Transfer Objects
├── Events/         # Laravel Events (e.g., MessageSent)
├── Jobs/           # Queueable Jobs (e.g., ProcessAutoReply)
├── Listeners/      # Event Listeners
├── Notifications/  # App Notifications
├── Policies/       # Authorization Policies
├── Repositories/   # Data Access Layer
├── Services/       # Complex domain logic
└── Traits/         # Reusable traits (e.g., HasSubscriptionPlan)
```

## 8. Deliverables
- [x] Complete Laravel project
- [x] Database migrations & Seeders
- [x] Role & Permission system (Spatie)
- [x] Working Stripe subscription system (Cashier)
- [x] Real-time chat events (Pusher)
- [x] Queue jobs processing
- [x] Comprehensive Documentation
