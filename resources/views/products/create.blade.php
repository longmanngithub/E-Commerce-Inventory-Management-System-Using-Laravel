<div id="add-product-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div id="add-product-panel" class="absolute right-0 top-0 h-full w-full max-w-2xl bg-white dark:bg-gray-900 shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out overflow-y-auto">
        {{-- Header --}}
        <div class="sticky top-0 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex items-center justify-between z-10">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Add New Product</h2>
            <button type="button" id="close-add-product" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-full transition-colors duration-200">
                <svg class="w-6 h-6 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        {{-- Form Content --}}
        <div class="p-6">
            <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data" id="add-product-form">
                @csrf

                <div class="space-y-6">
                    {{-- Product Name --}}
                    <div>
                        <label for="product_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Product Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="product_name"
                               name="product_name"
                               placeholder="Enter product name"
                               required
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                    </div>

                    {{-- Category and SKU Row --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Category --}}
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Category <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="category_id"
                                        id="category_id"
                                        required
                                        class="w-full appearance-none px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                    <option value="">Choose category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Product SKU --}}
                        <div>
                            <label for="product_SKU" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Product SKU <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="product_SKU"
                                   name="product_SKU"
                                   placeholder="Enter product SKU"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        </div>
                    </div>

                    {{-- Amount and Price Row --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Stock Quantity --}}
                        <div>
                            <label for="stock_quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Amount <span class="text-red-500">*</span>
                            </label>
                            <input type="number"
                                   id="stock_quantity"
                                   name="stock_quantity"
                                   placeholder="Enter item number"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        </div>

                        {{-- Price --}}
                        <div>
                            <label for="product_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Price <span class="text-red-500">*</span>
                            </label>
                            <input type="number"
                                   step="0.01"
                                   id="product_price"
                                   name="product_price"
                                   placeholder="Enter price"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        </div>
                    </div>

                    {{-- Date Fields Row --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Purchase Date --}}
                        <div>
                            <label for="purchase_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Date of Purchased <span class="text-red-500">*</span>
                            </label>
                            <input type="date"
                                   id="purchase_date"
                                   name="purchase_date"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        </div>

                        {{-- Expiry Date --}}
                        <div>
                            <label for="product_expiry_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Expiry Date
                            </label>
                            <input type="date"
                                   id="product_expiry_date"
                                   name="product_expiry_date"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        </div>
                    </div>

                    {{-- Additional Fields --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Purchase Price --}}
                        <div>
                            <label for="purchase_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Purchase Price <span class="text-red-500">*</span>
                            </label>
                            <input type="number"
                                   step="0.01"
                                   id="purchase_price"
                                   name="purchase_price"
                                   placeholder="Cost per item"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        </div>

                        {{-- Reorder Point --}}
                        <div>
                            <label for="reorder_point" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Reorder Point <span class="text-red-500">*</span>
                            </label>
                            <input type="number"
                                   id="reorder_point"
                                   name="reorder_point"
                                   value="10"
                                   placeholder="e.g., 10"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        </div>
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="product_desc" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Description <span class="text-red-500">*</span>
                        </label>
                        <textarea id="product_desc"
                                  name="product_desc"
                                  rows="4"
                                  placeholder="Input description"
                                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 resize-none"></textarea>
                    </div>

                    {{-- Image Upload --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">Product Image</label>

                        {{-- Drop Zone --}}
                        <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-8 text-center hover:border-blue-400 dark:hover:border-blue-500 transition-colors duration-200 bg-gray-50 dark:bg-gray-800">
                            <div class="mb-4">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Drag and drop your image here</p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mb-4">or click to browse files</p>
                            <button type="button" id="upload-btn" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                Upload Image
                            </button>
                            <input type="file" id="product_image" name="product_image" accept="image/*" class="hidden">
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">Supported formats: JPG, PNG, GIF (max 5MB)</p>
                        </div>

                        {{-- Image Preview --}}
                        <div id="image-preview" class="hidden mt-4">
                            <div class="relative inline-block">
                                <img id="preview-img" src="" alt="Preview" class="h-32 w-32 object-cover rounded-lg border border-gray-300 dark:border-gray-600">
                                <button type="button" id="remove-image" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition-colors duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="sticky bottom-0 bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 px-6 py-4 mt-8 -mx-6 -mb-6">
                    <div class="flex gap-3">
                        <button type="button" id="cancel-add-product" class="flex-1 px-4 py-3 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg font-medium hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200">
                            Cancel
                        </button>
                        <button type="submit" class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors duration-200">
                            Add
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
