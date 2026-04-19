<?php
// =============================================================
// app/controllers/ProductController.php
// =============================================================

namespace App\Controllers;

use Core\Controller;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    private Product  $product;
    private Category $category;

    public function __construct()
    {
        $this->product  = new Product();
        $this->category = new Category();
    }

    // ----------------------------------------------------------
    // GET /products
    // ----------------------------------------------------------
    public function index(): void
    {
        $this->requireAuth();

        $keyword  = trim($_GET['search'] ?? '');
        $catId    = (int) ($_GET['category'] ?? 0);

        if ($keyword) {
            $products = $this->product->search($keyword);
        } elseif ($catId) {
            $products = $this->product->findByCategory($catId);
        } else {
            $products = $this->product->findAllWithCategory();
        }

        $this->render('products/index', [
            'title'      => 'Produits',
            'products'   => $products,
            'categories' => $this->category->findAll('name'),
            'keyword'    => e($keyword),
            'catId'      => $catId,
        ]);
    }

    // ----------------------------------------------------------
    // GET /products/show/:id
    // ----------------------------------------------------------
    public function show(string $id): void
    {
        $this->requireAuth();

        $product = $this->product->findByIdWithCategory((int) $id);
        if (!$product) {
            $this->abort(404, 'Produit introuvable.');
        }

        $this->render('products/show', [
            'title'   => e($product['name']),
            'product' => $product,
        ]);
    }

    // ----------------------------------------------------------
    // GET /products/create  (admin)
    // ----------------------------------------------------------
    public function createForm(): void
    {
        $this->requireAdmin();

        $this->render('products/form', [
            'title'      => 'Nouveau produit',
            'product'    => null,
            'categories' => $this->category->findAll('name'),
            'csrf'       => $this->generateCsrf(),
        ]);
    }

    // ----------------------------------------------------------
    // POST /products/create  (admin)
    // ----------------------------------------------------------
    public function create(): void
    {
        $this->requireAdmin();
        $this->verifyCsrf();

        [$data, $errors] = $this->validateForm();

        if ($errors) {
            $this->flash('danger', implode('<br>', $errors));
            $this->redirect('/products/create');
            return;
        }

        if ($this->product->slugExists($data['slug'])) {
            $this->flash('danger', 'Un produit avec ce nom existe déjà.');
            $this->redirect('/products/create');
            return;
        }

        $this->product->insert($data);
        $this->flash('success', 'Produit créé avec succès.');
        $this->redirect('/products');
    }

    // ----------------------------------------------------------
    // GET /products/edit/:id  (admin)
    // ----------------------------------------------------------
    public function editForm(string $id): void
    {
        $this->requireAdmin();

        $product = $this->product->findById((int) $id);
        if (!$product) {
            $this->abort(404, 'Produit introuvable.');
        }

        $this->render('products/form', [
            'title'      => 'Modifier le produit',
            'product'    => $product,
            'categories' => $this->category->findAll('name'),
            'csrf'       => $this->generateCsrf(),
        ]);
    }

    // ----------------------------------------------------------
    // POST /products/edit/:id  (admin)
    // ----------------------------------------------------------
    public function edit(string $id): void
    {
        $this->requireAdmin();
        $this->verifyCsrf();

        $id = (int) $id;
        [$data, $errors] = $this->validateForm();

        if ($errors) {
            $this->flash('danger', implode('<br>', $errors));
            $this->redirect("/products/edit/{$id}");
            return;
        }

        if ($this->product->slugExists($data['slug'], $id)) {
            $this->flash('danger', 'Un autre produit avec ce nom existe déjà.');
            $this->redirect("/products/edit/{$id}");
            return;
        }

        $this->product->update($id, $data);
        $this->flash('success', 'Produit mis à jour.');
        $this->redirect('/products');
    }

    // ----------------------------------------------------------
    // POST /products/delete/:id  (admin)
    // ----------------------------------------------------------
    public function delete(string $id): void
    {
        $this->requireAdmin();
        $this->verifyCsrf();

        $this->product->delete((int) $id);
        $this->flash('success', 'Produit supprimé.');
        $this->redirect('/products');
    }

    // ----------------------------------------------------------
    // Validation commune create/edit
    // ----------------------------------------------------------
    private function validateForm(): array
    {
        $name        = $this->input('name');
        $description = $this->input('description');
        $price       = (float) str_replace(',', '.', $this->input('price'));
        $stock       = (int)   $this->input('stock');
        $categoryId  = (int)   $this->input('category_id');
        $slug        = slugify($name);

        $errors = [];
        if (empty($name))       $errors[] = 'Le nom du produit est requis.';
        if ($price < 0)         $errors[] = 'Le prix doit être positif.';
        if ($stock < 0)         $errors[] = 'Le stock doit être positif.';
        if ($categoryId <= 0)   $errors[] = 'Veuillez sélectionner une catégorie.';

        $data = [
            'category_id' => $categoryId,
            'name'        => $name,
            'slug'        => $slug,
            'description' => $description,
            'price'       => $price,
            'stock'       => $stock,
        ];

        return [$data, $errors];
    }
}
