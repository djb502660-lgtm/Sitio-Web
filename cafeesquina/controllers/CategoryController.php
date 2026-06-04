<?php

declare(strict_types=1);

/**
 * API interna de categorías (usado por admin vía AdminController).
 */
class CategoryController
{
    private Category $categories;

    public function __construct()
    {
        $this->categories = new Category();
    }

    public function listJson(): void
    {
        require_admin();
        header('Content-Type: application/json');
        echo json_encode($this->categories->all());
        exit;
    }
}
