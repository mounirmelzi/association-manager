<?php

namespace App\Controllers;

use App\Core\App;
use App\Core\Controller;
use App\Utils\Request;
use App\Models\CardType;
use App\Models\Partner;
use App\Models\DiscountOffer;
use App\Controllers\Error as ErrorController;
use App\Views\Pages\DiscountOffersList as DiscountOffersListPage;
use App\Views\Pages\DiscountOffersForm as DiscountOffersFormPage;

class DiscountOffers extends Controller
{
    public function index(): void
    {
        $partnerModel = new Partner();
        $cardTypeModel = new CardType();
        $discountOfferModel = new DiscountOffer();
        $discounts = array_map(function ($discount) use ($partnerModel, $cardTypeModel) {
            $partner = $partnerModel->get($discount['partner_id']);
            $cardType = $cardTypeModel->get($discount['card_type_id']);
            $discount['partner_name'] = $partner['name'];
            $discount['partner_category'] = $partner['category'];
            $discount['card_type'] = $cardType['type'];
            return $discount;
        }, $discountOfferModel->all());
        $page = new DiscountOffersListPage(["discounts" => $discounts]);
        $page->renderHtml();
    }

    public function create(): void
    {
        $partnerModel = new Partner();
        $cardTypeModel = new CardType();

        if (Request::method() === "GET") {
            $page = new DiscountOffersFormPage([
                'title' => 'Create New Discount Offer',
                'partners' => $partnerModel->all(),
                'card_types' => $cardTypeModel->all(),
            ]);
            $page->renderHtml();
        } else if (Request::method() === "POST") {
            $values = [
                'partner_id' => Request::data("partner_id"),
                'card_type_id' => Request::data("card_type_id"),
                'percentage' => Request::data("percentage"),
            ];

            $discount = new DiscountOffer($values);
            $errors = $discount->save();

            if (!empty($errors)) {
                $page = new DiscountOffersFormPage([
                    'title' => 'Create New Discount Offer',
                    'partners' => $partnerModel->all(),
                    'card_types' => $cardTypeModel->all(),
                    "values" => $values,
                    "errors" => $errors,
                ]);
                $page->renderHtml();
                return;
            }

            App::redirect("/discounts/offers");
        }
    }

    public function edit(int $id): void
    {
        $model = new DiscountOffer();
        $discount = $model->get($id);
        if ($discount === null) {
            $controller = new ErrorController();
            $controller->index(404, "Discount offer not found");
            return;
        }

        $partnerModel = new Partner();
        $cardTypeModel = new CardType();

        if (Request::method() === "GET") {
            $page = new DiscountOffersFormPage([
                'title' => 'Edit Discount Offer',
                'values' => $discount,
                'partners' => $partnerModel->all(),
                'card_types' => $cardTypeModel->all(),
            ]);
            $page->renderHtml();
        } else if (Request::method() === "POST") {
            $discount['partner_id'] =  Request::data("partner_id");
            $discount['card_type_id'] =  Request::data("card_type_id");
            $discount['percentage'] = Request::data('percentage');

            $discount = new DiscountOffer($discount);
            $errors = $discount->save();

            if (!empty($errors)) {
                $page = new DiscountOffersFormPage([
                    'title' => 'Edit Discount Offer',
                    'partners' => $partnerModel->all(),
                    'card_types' => $cardTypeModel->all(),
                    "values" => $discount->data,
                    "errors" => $errors,
                ]);
                $page->renderHtml();
                return;
            }

            App::redirect("/discounts/offers");
        }
    }

    public function delete(int $id): void
    {
        $model = new DiscountOffer();
        $discount = $model->get($id);
        if ($discount === null) {
            $controller = new ErrorController();
            $controller->index(404, "Discount offer not found");
            return;
        }

        $discount = new DiscountOffer($discount);
        if (!$discount->delete()) {
            $controller = new ErrorController();
            $controller->index(500, "Cannot delete the discount offer now, try again later!");
            return;
        }

        App::redirect("/discounts/offers");
    }
}
