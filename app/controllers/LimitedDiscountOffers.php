<?php

namespace App\Controllers;

use App\Core\App;
use App\Core\Controller;
use App\Utils\Request;
use App\Models\CardType;
use App\Models\Partner;
use App\Models\LimitedDiscountOffer;
use App\Controllers\Error as ErrorController;
use App\Views\Pages\LimitedDiscountOffersList as LimitedDiscountOffersListPage;
use App\Views\Pages\LimitedDiscountOffersForm as LimitedDiscountOffersFormPage;

class LimitedDiscountOffers extends Controller
{
    public function index(): void
    {
        $partnerModel = new Partner();
        $cardTypeModel = new CardType();
        $limitedDiscountOfferModel = new LimitedDiscountOffer();
        $discounts = array_map(function ($discount) use ($partnerModel, $cardTypeModel) {
            $partner = $partnerModel->get($discount['partner_id']);
            $cardType = $cardTypeModel->get($discount['card_type_id']);
            $discount['partner_name'] = $partner['name'];
            $discount['partner_category'] = $partner['category'];
            $discount['card_type'] = $cardType['type'];
            return $discount;
        }, $limitedDiscountOfferModel->all());
        $page = new LimitedDiscountOffersListPage(["discounts" => $discounts]);
        $page->renderHtml();
    }

    public function create(): void
    {
        $partnerModel = new Partner();
        $cardTypeModel = new CardType();

        if (Request::method() === "GET") {
            $page = new LimitedDiscountOffersFormPage([
                'title' => 'Create New Limited Discount Offer',
                'partners' => $partnerModel->all(),
                'card_types' => $cardTypeModel->all(),
            ]);
            $page->renderHtml();
        } else if (Request::method() === "POST") {
            $values = [
                'partner_id' => Request::data("partner_id"),
                'card_type_id' => Request::data("card_type_id"),
                'percentage' => Request::data("percentage"),
                'start_date' => Request::data("start_date"),
                'end_date' => Request::data("end_date"),
            ];

            $discount = new LimitedDiscountOffer($values);
            $errors = $discount->save();

            if (!empty($errors)) {
                $page = new LimitedDiscountOffersFormPage([
                    'title' => 'Create New Limited Discount Offer',
                    'partners' => $partnerModel->all(),
                    'card_types' => $cardTypeModel->all(),
                    "values" => $values,
                    "errors" => $errors,
                ]);
                $page->renderHtml();
                return;
            }

            App::redirect("/discounts/limited/offers");
        }
    }

    public function edit(int $id): void
    {
        $model = new LimitedDiscountOffer();
        $discount = $model->get($id);
        if ($discount === null) {
            $controller = new ErrorController();
            $controller->index(404, "Limited discount offer not found");
            return;
        }

        $partnerModel = new Partner();
        $cardTypeModel = new CardType();

        if (Request::method() === "GET") {
            $page = new LimitedDiscountOffersFormPage([
                'title' => 'Edit Limited Discount Offer',
                'values' => $discount,
                'partners' => $partnerModel->all(),
                'card_types' => $cardTypeModel->all(),
            ]);
            $page->renderHtml();
        } else if (Request::method() === "POST") {
            $discount['partner_id'] =  Request::data("partner_id");
            $discount['card_type_id'] =  Request::data("card_type_id");
            $discount['percentage'] = Request::data('percentage');
            $discount['start_date'] = Request::data('start_date');
            $discount['end_date'] = Request::data('end_date');

            $discount = new LimitedDiscountOffer($discount);
            $errors = $discount->save();

            if (!empty($errors)) {
                $page = new LimitedDiscountOffersFormPage([
                    'title' => 'Edit Limited Discount Offer',
                    'partners' => $partnerModel->all(),
                    'card_types' => $cardTypeModel->all(),
                    "values" => $discount->data,
                    "errors" => $errors,
                ]);
                $page->renderHtml();
                return;
            }

            App::redirect("/discounts/limited/offers");
        }
    }

    public function delete(int $id): void
    {
        $model = new LimitedDiscountOffer();
        $discount = $model->get($id);
        if ($discount === null) {
            $controller = new ErrorController();
            $controller->index(404, "Limited discount offer not found");
            return;
        }

        $discount = new LimitedDiscountOffer($discount);
        if (!$discount->delete()) {
            $controller = new ErrorController();
            $controller->index(500, "Cannot delete the limited discount offer now, try again later!");
            return;
        }

        App::redirect("/discounts/limited/offers");
    }
}
