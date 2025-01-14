<?php

namespace App\Controllers;

use App\Core\App;
use App\Core\Controller;
use App\Core\Database;
use App\Utils\Query;
use App\Models\User;
use App\Models\Activity;
use App\Models\Volunteering;
use App\Controllers\Error as ErrorController;
use App\Views\Pages\VolunteeringsList as VolunteeringsListPage;
use App\Views\Pages\UserActivitiesList as UserActivitiesListPage;

class Volunteerings extends Controller
{
    public function index(): void
    {
        $volunteeringModel = new Volunteering();
        $activityModel = new Activity();
        $query = new Query(Database::getInstance());
        $query->setTable('users');
        $user = User::current();

        if ($user['role'] === 'admin') {
            $volunteerings = $volunteeringModel->all();
            $volunteerings = array_map(function ($volunteering) use ($activityModel, $query) {
                $activity = $activityModel->get($volunteering['activity_id']);
                $volunteer = $query->getById($volunteering['user_id']);
                $volunteerRole = User::getRoleById($volunteering['user_id']);

                $volunteering['activity_title'] = $activity['title'];
                $volunteering['user_email'] = $volunteer['email'];
                $volunteering['user_role'] = $volunteerRole;

                return $volunteering;
            }, $volunteerings);

            $page = new VolunteeringsListPage(['volunteerings' => $volunteerings]);
        } else {
            $volunteerings = $volunteeringModel->getByUserId($user['id']);
            $volunteerings = array_filter($volunteerings, function ($volunteering) {
                return (bool)$volunteering['is_valid'];
            });

            $activities = array_map(function ($volunteering) use ($activityModel) {
                return $activityModel->get($volunteering['activity_id']);
            }, $volunteerings);

            $page = new UserActivitiesListPage(['activities' => $activities]);
        }

        $page->renderHtml();
    }

    public function validation(int $id): void
    {
        $model = new Volunteering();
        $volunteering = $model->get($id);
        if ($volunteering === null) {
            $controller = new ErrorController();
            $controller->index(404, "Volunteering request not found");
            return;
        }

        $volunteering = new Volunteering($volunteering);
        if ($volunteering->data['is_valid']) {
            $volunteering->refuse();
        } else {
            $volunteering->accept();
        }

        App::redirect("/volunteerings");
    }

    public function delete(int $id): void
    {
        $model = new Volunteering();
        $volunteering = $model->get($id);
        if ($volunteering === null) {
            $controller = new ErrorController();
            $controller->index(404, "Volunteering request not found");
            return;
        }

        $volunteering = new Volunteering($volunteering);
        if (!$volunteering->delete()) {
            $controller = new ErrorController();
            $controller->index(500, "Cannot delete the volunteering request now, try again later!");
            return;
        }

        App::redirect("/volunteerings");
    }
}
