<?php

declare(strict_types=1);

namespace MultiagentSecurity;

use Throwable;

final class AuthorizationMatrix
{
    public static function authorize(
        array $registry,
        string $applicationEnvironment,
        array $connections,
        bool $sameExecutionContext
    ): bool {
        try {
            TestDatabaseRegistry::assertValid($registry);
        } catch (Throwable) {
            return false;
        }

        if ($applicationEnvironment !== 'TEST' || $sameExecutionContext !== true) {
            return false;
        }

        if ($registry['status'] !== 'CONFIGURED' || $registry['confirmed_by_user'] !== true) {
            return false;
        }

        if ($connections === []) {
            return false;
        }

        $registered = $registry['authorized_test_database'];
        $requiredFields = self::requiredFieldsForEngine($registered['engine']);
        if ($requiredFields === []) {
            return false;
        }

        foreach ($connections as $connection) {
            if (!is_array($connection) || ($connection['identity_verified'] ?? null) !== true) {
                return false;
            }

            if (!isset($connection['identity']) || !is_array($connection['identity'])) {
                return false;
            }

            $actual = $connection['identity'];
            foreach ($requiredFields as $field) {
                if (!self::exactMatch($registered, $actual, $field)) {
                    return false;
                }
            }

            foreach (['host', 'port', 'schema', 'server_identifier'] as $field) {
                if ($registered[$field] !== null && !self::exactMatch($registered, $actual, $field)) {
                    return false;
                }
            }
        }

        return true;
    }

    public static function requiredFieldsForEngine(string $engine): array
    {
        return match ($engine) {
            'mysql', 'mariadb' => ['engine', 'database_name', 'connection_name', 'host', 'port', 'server_identifier'],
            'postgresql' => ['engine', 'database_name', 'connection_name', 'host', 'port', 'schema', 'server_identifier'],
            'sqlite' => ['engine', 'database_name', 'connection_name'],
            default => [],
        };
    }

    private static function exactMatch(array $registered, array $actual, string $field): bool
    {
        if (!array_key_exists($field, $registered) || !array_key_exists($field, $actual)) {
            return false;
        }

        $expectedValue = $registered[$field];
        $actualValue = $actual[$field];
        if ($expectedValue === null || $actualValue === null || !is_scalar($expectedValue) || !is_scalar($actualValue)) {
            return false;
        }

        if (gettype($expectedValue) !== gettype($actualValue)) {
            return false;
        }

        return (string) $expectedValue === (string) $actualValue;
    }
}
