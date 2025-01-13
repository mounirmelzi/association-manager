<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Database;
use App\Utils\Session;
use App\Utils\Query;

abstract class User extends Model
{
    #[\Override]
    public function delete(): bool
    {
        $this->query->setTable("users");
        return $this->query->delete($this->data["id"]);
    }

    public static function login(string $username, string $password): array
    {
        $session = new Session("auth");
        $session->clear();

        $query = new Query(Database::getInstance());
        $query->setTable("users");
        $response = $query->where(["username" => $username]);
        $user = $response[0] ?? null;

        if ($user === null) {
            return ["username" => "$username not found"];
        }

        if ($user["password"] !== $password) {
            return ["password" => "wrong password"];
        }

        $member = new Member();
        $member = $member->get($user["id"]);
        if ($member !== null) {
            if (!$member["is_active"]) {
                return ["username" => "this account have not been activated yet"];
            }

            $session->set("role", "member");
        }

        $admin = new Admin();
        $admin = $admin->get($user["id"]);
        if ($admin !== null) {
            $session->set("role", "admin");
        }

        $partner = new Partner();
        $partner = $partner->get($user["id"]);
        if ($partner !== null) {
            $session->set("role", "partner");
        }

        $session->set("id", $user["id"]);
        return [];
    }

    public static function logout(): void
    {
        $session = new Session("auth");
        $session->clear();
    }

    public static function getRoleById(int $id): mixed
    {
        $member = new Member();
        $member = $member->get($id);
        if ($member !== null) {
            return 'member';
        }

        $admin = new Admin();
        $admin = $admin->get($id);
        if ($admin !== null) {
            return 'admin';
        }

        $partner = new Partner();
        $partner = $partner->get($id);
        if ($partner !== null) {
            return 'partner';
        }

        return null;
    }

    public static function current(): mixed
    {
        $session = new Session("auth");

        $id = $session->get("id");
        if ($id === null) {
            return null;
        }

        $admin = new Admin();
        $member = new Member();
        $partner = new Partner();

        $role = $session->get("role");
        return match ($role) {
            "admin" => array_merge($admin->get($id), ["role" => $role]),
            "member" => array_merge($member->get($id), ["role" => $role]),
            "partner" => array_merge($partner->get($id), ["role" => $role]),
            default => null,
        };
    }
}
