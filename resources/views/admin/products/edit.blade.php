<x-layout>
    <x-header favorites="{{ $favorites }}" numberOfCartItems="{{ $numberOfCartItems }}"/>
    <x-setting :heading="'Edit Product: ' . $product->title">
        <form method="POST" action="{{ route('products.update', ['product' => $product->id]) }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <p class="text-sm">All fields marked with * are required</p>

            <x-form.input name="title" type="text" :value="old('title', $product->title)">*</x-form.input>
            <x-form.input name="slug" type="text" :value="old('slug', $product->slug)">*</x-form.input>

            <div class="flex ">

                <div class="flex-1">
                    <x-form.input-label for="image">Image</x-form.input-label>
                    <x-form.text-input name="image" type="file" :value="old('image', $product->image)"/>
                </div>

                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}" class="rounded-xl ml-6 mt-6"
                     width="100">
                <x-form.input-error name="image"/>
            </div>

            <x-form.input name="in_stock_quantity" type="number" label="Quantity in stock" :value="old('inStockQuantity', $product->in_stock_quantity)">*</x-form.input>

            <div>
                <x-form.input-label for="category">Category*</x-form.input-label>
                <select
                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-96 h-8 dark:bg-slate-800 dark:text-white"
                    name="category_id"
                    id="category_id"
                >
                    @php
                        $categories = \App\Models\Category::all();
                    @endphp

                    @foreach($categories as $category)
                        <option
                            value="{{ $category->id }}"
                            {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}
                        >{{ ucwords($category->name) }}</option>
                    @endforeach
                </select>

                <x-form.input-error name="category"/>
            </div>

            <div>
                <x-form.input-label for="description">Description*</x-form.input-label>
                <textarea class="border border-gray-200 p-2 w-96 rounded h-36 dark:bg-slate-800 dark:text-white"
                          name="description"
                          id="description"
                          required
                >{{ old('description', $product->description)}}
                </textarea>
                <x-form.input-error name="description"/>
            </div>

            <x-form.input name="price" type="number" :value="old('price', $product->price)">*</x-form.input>
            <x-form.input name="dimensions" type="text" :value="old('dimensions', $product->dimensions)">*</x-form.input>
            <x-form.input name="type" type="text" :value="old('type', $product->type)">*</x-form.input>

            <x-primary-button class="mt-4">Update</x-primary-button>
        </form>
    </x-setting>
    <x-footer/>
</x-layout>
