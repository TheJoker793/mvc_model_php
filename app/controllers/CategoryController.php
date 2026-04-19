<?php
// =============================================================
// app/controllers/CategoryController.php
// =============================================================

namespace App\Controllers;

use Core\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    private Category $model;

    public function __construct()
    {
        $this->model = new Category();
    }

    // ----------------------------------------------------------
    // GET /categories
    // ----------------------------------------------------------
    public function index(): void
    {
        $this->requireAuth();

        $this->render('categories/index', [
            'title'      => 'Catégories',
            'categories' => $this->model->findAllWithProductCount(),
        ]);
    }

    // ----------------------------------------------------------
    // GET /categories/create  (admin)
    // ----------------------------------------------------------
    public function createForm(): void
    {
        $this->requireAdmin();

        $this->render('categories/form', [
            'title'    => 'Nouvelle catégorie',
            'category' => null,
            'csrf'     => $this->generateCsrf(),
        ]);
    }

    // ----------------------------------------------------------
    // POST /categories/create  (admin)
    // ----------------------------------------------------------
    public function create(): void
    {
        $this->requireAdmin();
        $this->verifyCsrf();

        $name        = $this->input('name');
        $description = $this->input('description');
        $slug        = slugify($name);

        if (empty($name)) {
            $this->flash('danger', 'Le nom de la catégorie est requis.');
            $this->redirect('/categories/create');
            return;
        }

        if ($this->model->slugExists($slug)) {
            $this->flash('danger', 'Une catégorie avec ce nom existe déjà.');
            $this->redirect('/categories/create');
            return;
        }

        $this->model->insert([
            'name'        => $name,
            'slug'        => $slug,
            'description' => $description,
        ]);

        $this->flash('success', 'Catégorie créée avec succès.');
        $this->redirect('/categories');
    }

    // ----------------------------------------------------------
    // GET /categories/edit/:id  (admin)
    // ----------------------------------------------------------
    public function editForm(string $id): void
    {
        $this->requireAdmin();

        $category = $this->model->findById((int) $id);
        if (!$category) {
            $this->abort(404, 'Catégorie introuvable.');
        }

        $this->render('categories/form', [
            'title'    => 'Modifier la catégorie',
            'category' => $category,
            'csrf'     => $this->generateCsrf(),
        ]);
    }

    // ----------------------------------------------------------
    // POST /categories/edit/:id  (admin)
    // ----------------------------------------------------------
    public function edit(string $id): void
    {
        $this->requireAdmin();
        $this->verifyCsrf();

        $id          = (int) $id;
        $name        = $this->input('name');
        $description = $this->input('description');
        $slug        = slugify($name);

        if (empty($name)) {
            $this->flash('danger', 'Le nom de la catégorie est requis.');
            $this->redirect("/categories/edit/{$id}");
            return;
        }

        if ($this->model->slugExists($slug, $id)) {
            $this->flash('danger', 'Une autre catégorie avec ce nom existe déjà.');
            $this->redirect("/categories/edit/{$id}");
            return;
        }

        $this->model->update($id, [
            'name'        => $name,
            'slug'        => $slug,
            'description' => $description,
        ]);

        $this->flash('success', 'Catégorie mise à jour.');
        $this->redirect('/categories');
    }

    // ----------------------------------------------------------
    // POST /categories/delete/:id  (admin)
    // ----------------------------------------------------------
    public function delete(string $id): void
    {
        $this->requireAdmin();
        $this->verifyCsrf();

        $this->model->delete((int) $id);
        $this->flash('success', 'Catégorie supprimée.');
        $this->redirect('/categories');
    }
}
