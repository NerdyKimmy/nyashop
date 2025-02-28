<x-app-layout>

    <form action="" method="GET">
    <x-input type="text" name="search" placeholder="Search for the products" value="{{request()->get('search')}}"/>
    </form>
    <div
        class="grid gap-8 grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 items-stretch p-5"
    >
        @foreach($products as $product)
            <!-- Product Item -->
            <div
                x-data="productItem({{ json_encode([
                    'id' => $product->id,
                    'image' => $product->image,
                    'title' => $product->title,
                    'price' => $product->price,
                    'addToCartUrl' => route('cart.add', $product)
                ]) }})"
                class=" flex flex-col h-full border border-1 border-gray-200 rounded-md hover:border-pink-300 transition-colors bg-white"
            >
                <a
                    href="{{ route('product.view', $product->slug) }}"
                    class="block overflow-hidden h-80"
                >
                    <img
                        src="{{ $product->image }}"
                        alt="{{ $product->title }}"
                        class="object-cover w-full h-full transition-transform rounded-lg
               hover:scale-105 hover:rotate-1"
                    />
                </a>

                <div class="p-4 flex-1">
                    <h3 class="text-lg">
                        <a href="{{ route('product.view', $product->slug) }}">
                            {{$product->title}}
                        </a>
                    </h3>
                    <h5 class="font-bold">₴{{$product->price}}</h5>
                </div>
                <div class="p-4">
                    <button class="btn-primary" @click="addToCart()">
                        Add to Cart
                    </button>
                </div>
            </div>
            <!--/ Product Item -->
        @endforeach
    </div>
    {{$products->links()}}
</x-app-layout>
