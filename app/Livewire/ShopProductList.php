<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Category;

class ShopProductList extends Component
{
    use WithPagination;

    public $category = '';
    public $search = '';

    protected $queryString = ['category', 'search'];
    protected $updatesQueryString = ['category', 'search'];

    public function updating($field)
    {
        if (in_array($field, ['category', 'search'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        $categories = Category::all();
        $products = Product::with('category')
            ->when($this->category, fn($q) => $q->where('category_id', $this->category))
            ->when($this->search, fn($q) => $q->where('name', 'like', '%'.$this->search.'%'))
            ->orderBy('id', 'desc')
            ->paginate(12);
        return view('livewire.shop-product-list', [
            'products' => $products,
            'categories' => $categories,
            'categoryId' => $this->category,
        ]);
    }
}
