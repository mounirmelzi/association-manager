<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Database;
use App\Utils\Session;
use App\Utils\Logger;
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

        $session = new Session("auth");
        $session->clear();
        $session->set("id", $user["id"]);

        $admin = new Admin();
        $admin = $admin->get($user["id"]);
        if ($admin !== null) {
            $session->set("role", "admin");
            return [];
        }

        $member = new Member();
        $member = $member->get($user["id"]);
        if ($member !== null) {
            $session->set("role", "member");
            return [];
        }

        $partner = new Partner();
        $partner = $partner->get($user["id"]);
        if ($partner !== null) {
            $session->set("role", "partner");
            return [];
        }

        Logger::warning("unreachable code");
        Logger::error("invalid role");
        return ["role" => "invalid role"];
    }

    public static function logout(): void
    {
        $session = new Session("auth");
        $session->clear();
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
