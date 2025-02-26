# Laravel Real-Time Product Display Application

This Laravel application fetches product data from a public API and displays it in real-time using Pusher and Laravel Echo. Users can see new products appear instantly without needing to refresh the page.

## Features

- **API Integration**: Fetches product data from the [Fake Store API](https://fakestoreapi.com/).
- **Real-Time Updates**: Implements real-time product updates using Pusher and Laravel Echo.
- **Responsive Design**: Utilizes Tailwind CSS for a responsive and modern UI.

## Prerequisites

Before setting up the project, ensure you have the following installed:

- PHP >= 7.4
- Composer
- Node.js & npm
- Laravel CLI
- A Pusher account (for real-time broadcasting)

## Installation

Follow these steps to set up and run the application:

1. **Clone the repository**:

   ```bash
   git clone https://github.com/your-username/your-repo-name.git
   cd your-repo-name
   ```

2. **Install PHP dependencies**:

   ```bash
   composer install
   ```

3. **Install JavaScript dependencies**:

   ```bash
   npm install
   ```

4. **Set up environment variables**:

   - Duplicate the `.env.example` file and rename it to `.env`:

     ```bash
     cp .env.example .env
     ```

   - Generate a new application key:

     ```bash
     php artisan key:generate
     ```

   - Configure the `.env` file with your database and Pusher credentials:

     ```
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=your_database
     DB_USERNAME=your_username
     DB_PASSWORD=your_password

     BROADCAST_DRIVER=pusher
     PUSHER_APP_ID=your_pusher_app_id
     PUSHER_APP_KEY=your_pusher_app_key
     PUSHER_APP_SECRET=your_pusher_app_secret
     PUSHER_APP_CLUSTER=your_pusher_app_cluster
     ```

5. **Run database migrations**:

   ```bash
   php artisan migrate
   ```

6. **Compile assets**:

   ```bash
   npm run dev
   ```

7. **Start the development server**:

   ```bash
   php artisan serve
   ```

8. **Start the queue worker** (for handling broadcast events):

   ```bash
   php artisan queue:work
   ```

## Fetching Products

To fetch products from the Fake Store API and broadcast them in real-time:

```bash
php artisan fetch:products
```

This command retrieves products and updates the database. New products are broadcasted to all connected clients, updating the product list in real-time.

## Real-Time Updates with Pusher

The application integrates Pusher for real-time broadcasting. Here's how it works:

1. **Event Broadcasting**: When new products are fetched, a `ProductUpdated` event is dispatched. This event implements the `ShouldBroadcast` interface, ensuring it's broadcasted via the configured driver (Pusher).

2. **Frontend Listening**: The frontend uses Laravel Echo to listen for the `product.updated` event on the `products` channel. Upon receiving the event, the product list is updated dynamically without a page refresh.

   ```javascript
   window.Echo.channel('products')
       .listen('.product.updated', (data) => {
           // Update the product list with the new data
       });
   ```

Ensure your Pusher credentials in the `.env` file are correct and match your Pusher dashboard settings.
