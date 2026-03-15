# CRS Developer Guide & Architecture Reference

This document is written for your future self (or any other developer joining the project). It explains exactly *how* the Chat Reply System logic is structured under the hood, why certain decisions were made, and how all the moving parts connect together.

---

## 🏗️ 1. The Core Architecture Pattern

Instead of putting all the logic in the Controllers (which gets messy quickly), this project uses a combination of **Services**, **Actions**, **DTOs** (Data Transfer Objects), and **Repositories**.

- **DTOs (`app/DTOs`)**: These are simple objects used to pass data around clearly. When a user sends a message, their data is packed into a `MessageDTO` so we always know exactly what properties are available.
- **Repositories (`app/Repositories`)**: These handle the actual database queries. Instead of writing `Message::create()` everywhere, we use the `MessageRepository`. This makes swapping databases or changing queries easier.
- **Actions (`app/Actions`)**: These are classes that do *one specific thing*. E.g., `SendMessageAction.php`. Action classes orchestrate the step-by-step process of a feature (saving the message, firing the event, triggering the auto-reply).
- **Services (`app/Services`)**: These group related business logic together. `ChatService.php` contains methods for opening chats and finding chats, which act as a gateway to the Actions and Repositories.

**Why?** If you look at this in 6 months, you won't have to read through 500 lines of Controller code. The `MessageController` simply passes the Request to the `ChatService`, which uses `MessageDTO` and `SendMessageAction` to get the job done cleanly.

---

## 🤖 2. The Auto-Reply System (Synchronous Execution)

The Auto-Reply feature is the brain of the backend. 

### How it works:
1. When a user sends a message, `SendMessageAction` saves the message to the DB.
2. It then calls `AutoReplyService->replyIfMatched($message)` **synchronously** (meaning instantly, in the same request lifecycle). *Note: Previously, this was dispatched to a Laravel Queue (`ProcessAutoReply`), but it was changed to synchronous execution to ensure replies feel instantaneous.*
3. `AutoReplyService` checks the `AutomatedReply` table for a keyword match. 
4. **Fallback mechanism**: If no keywords match, the system falls back to a default hardcoded string: *"I'm sorry, I couldn't understand that..."* to ensure the user is never left hanging.
5. Finally, an `$is_auto_reply = true` Message record is created as the bot's response.

---

## 💳 3. Subscriptions (Laravel Cashier + Stripe)

You might wonder: *"Where is the Subscription Model?"*

- **The Plans**: You created a `SubscriptionPlan` model. This model stores the *definitions* of your tiers (Free, Basic, Pro), their prices, and their Stripe Price IDs.
- **The Actual Subscriptions**: The actual user subscriptions are entirely managed by the **Laravel Cashier** package. Cashier dynamically provides a hidden `Laravel\Cashier\Subscription` model. 
- **The DB Tables**: Cashier created the `subscriptions` and `subscription_items` tables during migration.
- **The Flow**: When a user clicks "Subscribe", `SubscriptionService->subscribe()` calls `$user->newSubscription('default', $plan->stripe_price_id)`. Cashier securely contacts Stripe, registers the payment, and updates the local database.

---

## 📡 4. Real-Time Events (Pusher & Laravel Echo)

When a message is sent—whether by a human or the auto-reply bot—the front-end needs to update without refreshing the page.

1. **The Event**: `SendMessageAction` or `AutoReplyService` calls `event(new MessageSent($message))`.
2. **The Broadcast**: The `MessageSent` event implements `ShouldBroadcast`. Laravel connects to Pusher using the credentials in your `.env` file and pushes the JSON representation of the message to a private channel called `chat.{chat_id}`.
3. **The Frontend**: In your frontend JavaScript (`resources/js/echo.js`), **Laravel Echo** is listening to that private channel. When it receives the payload, your Vite/JavaScript updates the DOM instantly to show the new message bubble.

---

## 🔐 5. Roles, Permissions & API Auth

### Roles via Spatie
The system uses the `spatie/laravel-permission` package. 
- In your `routes/web.php`, entire route groups are protected via middleware like `middleware(['role:Admin'])` or `middleware(['permission:manage-users'])`. 
- To add a new role or permission in the future, simply update the `RolePermissionSeeder` and run `php artisan db:seed`.

### API Auth via Sanctum
While the Web App uses standard session cookies via Laravel Breeze, mobile apps or external endpoints use **Laravel Sanctum**.
- A client calls `POST /api/auth/login`.
- Sanctum validates the user and generates a Plain Text Token.
- The client passes this token as a `Bearer` header (`Authorization: Bearer 1|abcdef...`) in subsequent requests to `/api/chats`. The `auth:sanctum` middleware handles validating the token seamlessly.

---

## 🛠️ 6. Quick Commands to Remember

If you are booting this project up after a long time away, here are the commands you will need:

- **Wipe and reset the database with dummy data**:
  `php artisan migrate:fresh --seed`
- **Recompile Frontend Assets**:
  `npm run build` or `npm run dev`
- **Test Stripe Subscriptions Local**:
  Use the test card `4242 4242 4242 4242` with any future expiry date.
- **Test Backend Logic Quickly**:
  Use `php artisan tinker`.

---

*End of Document. Trust the architecture, it is built to scale!*
