<?php
// =============================================================
// app/controllers/DashboardController.php
// =============================================================

namespace App\Controllers;

use Core\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;

class DashboardController extends Controller
{
    // GET /dashboard
    public function index(): void
    {
        $this->requireAuth();

        $productModel  = new Product();
        $categoryModel = new Category();
        $userModel     = new User();

        $this->render('dashboard/index', [
            'title'         => 'Tableau de bord',
            'totalProducts' => $productModel->count(),
            'totalCategories'=> $categoryModel->count(),
            'totalUsers'    => $userModel->count(),
            'recentProducts'=> $productModel->findAllWithCategory(),
        ]);
    }
}
