<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\News as NewsModel;
use App\Models\Activity as ActivityModel;
use App\Models\Partner as PartnerModel;
use App\Models\CardType as CardTypeModel;
use App\Models\DiscountOffer as DiscountOfferModel;
use App\Models\LimitedDiscountOffer as LimitedDiscountOfferModel;
use App\Views\Pages\Home as HomePage;
use App\Views\Pages\Discounts as DiscountsPage;
use App\Views\Pages\Dashboard as DashboardPage;

class Home extends Controller
{
    public function home(): void
    {
        $newsModel = new NewsModel();
        $activityModel = new ActivityModel();
        $partnerModel = new PartnerModel();
        $cardTypeModel = new CardTypeModel();
        $discountOfferModel = new DiscountOfferModel();
        $limitedDiscountOfferModel = new LimitedDiscountOfferModel();

        $news = $newsModel->all(limit: 8);
        $activities = $activityModel->all(limit: 8);
        $partners = $partnerModel->all();

        $discounts = array_map(function ($discount) use ($partnerModel, $cardTypeModel) {
            $partner = $partnerModel->get($discount['partner_id']);
            $cardType = $cardTypeModel->get($discount['card_type_id']);
            $discount['partner_name'] = $partner['name'];
            $discount['partner_category'] = $partner['category'];
            $discount['partner_address'] = $partner['address'];
            $discount['card_type'] = $cardType['type'];
            return $discount;
        }, $discountOfferModel->all(limit: 10));

        $limitedDiscounts = array_map(function ($discount) use ($partnerModel, $cardTypeModel) {
            $partner = $partnerModel->get($discount['partner_id']);
            $cardType = $cardTypeModel->get($discount['card_type_id']);
            $discount['partner_name'] = $partner['name'];
            $discount['partner_category'] = $partner['category'];
            $discount['partner_address'] = $partner['address'];
            $discount['card_type'] = $cardType['type'];
            return $discount;
        }, $limitedDiscountOfferModel->all(limit: 10));

        $logos = array_map(function ($partner) {
            return $partner['logo_url'];
        }, $partners);

        $socials = [
            ['icon' => 'facebook', 'link' => 'https://www.facebook.com'],
            ['icon' => 'twitter', 'link' => 'https://www.twitter.com'],
            ['icon' => 'instagram', 'link' => 'https://www.instagram.com'],
            ['icon' => 'linkedin', 'link' => 'https://www.linkedin.com'],
        ];

        $page = new HomePage([
            "news" => $news,
            "activities" => $activities,
            "logos" => $logos,
            "socials" => $socials,
            "discounts" => $discounts,
            "limitedDiscounts" => $limitedDiscounts,
            "cardTypes" => $cardTypeModel->all(),
        ]);

        $page->renderHtml();
    }

    public function discounts(): void
    {
        $partnerModel = new PartnerModel();
        $cardTypeModel = new CardTypeModel();
        $discountOfferModel = new DiscountOfferModel();
        $limitedDiscountOfferModel = new LimitedDiscountOfferModel();

        $discounts = array_map(function ($discount) use ($partnerModel, $cardTypeModel) {
            $partner = $partnerModel->get($discount['partner_id']);
            $cardType = $cardTypeModel->get($discount['card_type_id']);
            $discount['partner_name'] = $partner['name'];
            $discount['partner_category'] = $partner['category'];
            $discount['partner_address'] = $partner['address'];
            $discount['card_type'] = $cardType['type'];
            return $discount;
        }, $discountOfferModel->all());

        $limitedDiscounts = array_map(function ($discount) use ($partnerModel, $cardTypeModel) {
            $partner = $partnerModel->get($discount['partner_id']);
            $cardType = $cardTypeModel->get($discount['card_type_id']);
            $discount['partner_name'] = $partner['name'];
            $discount['partner_category'] = $partner['category'];
            $discount['partner_address'] = $partner['address'];
            $discount['card_type'] = $cardType['type'];
            return $discount;
        }, $limitedDiscountOfferModel->all());

        $page = new DiscountsPage([
            "discounts" => $discounts,
            "limitedDiscounts" => $limitedDiscounts,
        ]);

        $page->renderHtml();
    }

    public function dashboard(): void
    {
        $cards = [
            [
                "title" => "Members Management",
                "link" => BASE_URL . "members",
                "icon" => "people"
            ],
            [
                "title" => "Card Types Management",
                "link" => BASE_URL . "cards/types",
                "icon" => "person-vcard"
            ],
            [
                "title" => "Help Types Management",
                "link" => BASE_URL . "helps/types",
                "icon" => "person-wheelchair"
            ],
            [
                "title" => "Statistics",
                "link" => BASE_URL . "statistics",
                "icon" => "bar-chart-line"
            ],
            [
                "title" => "Discount Offers Management",
                "link" => BASE_URL . "discounts/offers",
                "icon" => "cart-dash"
            ],
            [
                "title" => "Limited Discount Offers Management",
                "link" => BASE_URL . "discounts/limited/offers",
                "icon" => "cart-dash-fill"
            ],
            [
                "title" => "Diaporama Management",
                "link" => BASE_URL . "diaporama",
                "icon" => "file-earmark-slides"
            ],
            [
                "title" => "Navbar Management",
                "link" => BASE_URL . "navbar",
                "icon" => "window"
            ],
        ];

        $page = new DashboardPage(["cards" => $cards]);
        $page->renderHtml();
    }
}
