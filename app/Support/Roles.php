<?php

namespace App\Support;

final class Roles
{
    public const SUPER_ADMIN = 'super_admin';
    public const ADMIN = 'admin';
    public const MANAGER = 'manager';
    public const CLERK = 'clerk';

    public const ALL = [
        self::SUPER_ADMIN,
        self::ADMIN,
        self::MANAGER,
        self::CLERK,
    ];

    private function __construct()
    {
    }
}
