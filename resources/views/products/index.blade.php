<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products List</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js for UI interactions -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Pusher & Laravel Echo -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.3/echo.iife.js"></script>

    <style>
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gray-100 p-10">

    <div class="max-w-5xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 text-center mb-6">Product List</h1>

        <!-- Notifications -->
        <div x-data="{ show: false, message: '' }" x-show="show"
             x-transition.opacity.duration.500ms
             class="fixed top-5 right-5 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg fade-in">
            <span x-text="message"></span>
        </div>

        <!-- Products Grid -->
        <div id="product-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($products as $product)
                <div class="bg-white shadow-lg rounded-lg p-6 fade-in">
                    <h2 class="text-xl font-semibold text-gray-700">{{ $product->name }}</h2>
                    <p class="text-gray-500 text-sm mt-2">{{ $product->description }}</p>
                    <div class="mt-4 flex justify-between items-center">
                        <span class="text-green-500 font-bold text-lg">${{ number_format($product->price, 2) }}</span>
                        <span class="text-gray-400 text-xs">{{ $product->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Real-Time Updates with Laravel Echo -->
    <script>
        Pusher.logToConsole = true;

        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: "b95d7bcc8929e45c785c",
            cluster: "us2",
            forceTLS: true
        });

       window.Echo.channel('products')
    .listen('.product.updated', (data) => {
        const container = document.getElementById('product-container');
        const updatedTime = new Date(data.product.updated_at).toLocaleTimeString();

        const existingProduct = document.querySelector(`[data-product="${data.product.id}"]`);

        if (existingProduct) {
            existingProduct.innerHTML = `
                <h2 class="text-xl font-semibold text-gray-700">${data.product.name}</h2>
                <p class="text-gray-500 text-sm mt-2">${data.product.description}</p>
                <div class="mt-4 flex justify-between items-center">
                    <span class="text-green-500 font-bold text-lg">$${parseFloat(data.product.price).toFixed(2)}</span>
                    <span class="text-gray-400 text-xs">${updatedTime}</span>
                </div>
            `;
        } else {
            const newProduct = document.createElement('div');
            newProduct.classList = "bg-white shadow-lg rounded-lg p-6 fade-in";
            newProduct.setAttribute('data-product', data.product.id);
            newProduct.innerHTML = `
                <h2 class="text-xl font-semibold text-gray-700">${data.product.name}</h2>
                <p class="text-gray-500 text-sm mt-2">${data.product.description}</p>
                <div class="mt-4 flex justify-between items-center">
                    <span class="text-green-500 font-bold text-lg">$${parseFloat(data.product.price).toFixed(2)}</span>
                    <span class="text-gray-400 text-xs">${updatedTime}</span>
                </div>
            `;
            container.prepend(newProduct);
        }

        let notification = document.querySelector('[x-data]');
        notification.__x.$data.show = false;
        setTimeout(() => {
            notification.__x.$data.show = true;
            notification.__x.$data.message = `Product "${data.product.name}" updated!`;
        }, 100);

        setTimeout(() => {
            notification.__x.$data.show = false;
        }, 3000);
    });

    </script>
</body>
</html>
