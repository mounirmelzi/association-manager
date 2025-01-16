<?php

namespace App\Controllers;

use App\Core\Database;
use App\Core\Controller;
use App\Views\Pages\Statistics as StatisticsPage;

class Statistics extends Controller
{
    public function index(): void
    {
        $database = Database::getInstance();
        $pdo = $database->getConnection();
        $data = [];

        $query = "SELECT COUNT(*) AS total_admins FROM admins";
        $data['total_admins'] = $pdo->query($query)->fetchColumn();

        $query = "SELECT COUNT(*) AS total_partners FROM partners";
        $data['total_partners'] = $pdo->query($query)->fetchColumn();

        $query = "SELECT COUNT(*) AS active_members FROM members WHERE is_active = TRUE";
        $data['active_members'] = $pdo->query($query)->fetchColumn();

        $query =
            "SELECT ct.type, COUNT(c.id) AS total_cards
            FROM cards c
            JOIN card_types ct ON c.card_type_id = ct.id
            GROUP BY ct.type";
        $data['cards_distribution'] = $pdo->query($query)->fetchAll();

        $query =
            "SELECT category, COUNT(id) AS total_partners
            FROM partners
            GROUP BY category";
        $data['partners_distribution'] = $pdo->query($query)->fetchAll();

        $query =
            "SELECT COUNT(*) AS total_payments, SUM(amount) AS total_amount
            FROM payments WHERE is_valid = TRUE";
        $data['payments'] = $pdo->query($query)->fetch();

        $query = "SELECT COUNT(*) AS total_helps FROM helps WHERE is_valid = TRUE";
        $data['total_helps'] = $pdo->query($query)->fetchColumn();

        $query = "SELECT COUNT(*) AS total_volunteerings FROM volunteerings WHERE is_valid = TRUE";
        $data['total_volunteerings'] = $pdo->query($query)->fetchColumn();

        $query =
            "SELECT p.name AS partner_name, p.category AS partner_category, SUM(d.amount) AS total_discount
            FROM discounts d INNER JOIN partners p ON d.partner_id = p.id
            WHERE d.is_valid = true
            GROUP BY p.name
            ORDER BY total_discount DESC";
        $data['total_discount'] = $pdo->query($query)->fetchAll();

        $page = new StatisticsPage($data);
        $page->renderHtml();
    }
}
