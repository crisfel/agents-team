<?php

declare(strict_types=1);

namespace MultiagentSecurity;

use JsonException;
use RuntimeException;

final class TestDatabaseRegistry
{
    private const BEGIN_MARKER = '<!-- TEST_DATABASE_REGISTRY:BEGIN -->';
    private const END_MARKER = '<!-- TEST_DATABASE_REGISTRY:END -->';

    public static function fromFile(string $path): array
    {
        if ($path === '' || !is_file($path)) {
            throw new RuntimeException('El registro TEST no existe.');
        }

        $markdown = file_get_contents($path);
        if ($markdown === false) {
            throw new RuntimeException('No se pudo leer el registro TEST.');
        }

        return self::fromMarkdown($markdown);
    }

    public static function fromMarkdown(string $markdown): array
    {
        if (substr_count($markdown, self::BEGIN_MARKER) !== 1 || substr_count($markdown, self::END_MARKER) !== 1) {
            throw new RuntimeException('Debe existir exactamente un bloque de registro TEST.');
        }

        $pattern = '/<!-- TEST_DATABASE_REGISTRY:BEGIN -->\s*```json\s*(.*?)\s*```\s*<!-- TEST_DATABASE_REGISTRY:END -->/s';
        if (preg_match($pattern, $markdown, $matches) !== 1) {
            throw new RuntimeException('El bloque de registro TEST no tiene un formato válido.');
        }

        try {
            $registry = json_decode($matches[1], true, 32, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new RuntimeException('El JSON del registro TEST no es válido.', 0, $exception);
        }

        if (!is_array($registry)) {
            throw new RuntimeException('El registro TEST debe ser un objeto JSON.');
        }

        self::assertValid($registry);

        return $registry;
    }

    public static function assertValid(array $registry): void
    {
        self::assertOnlyKeys($registry, ['schema_version', 'status', 'authorized_test_database', 'confirmed_by_user'], 'registro');

        if (($registry['schema_version'] ?? null) !== 1) {
            throw new RuntimeException('La versión del registro TEST no es compatible.');
        }

        $status = $registry['status'] ?? null;
        if (!in_array($status, ['UNCONFIGURED', 'CONFIGURED'], true)) {
            throw new RuntimeException('El estado del registro TEST no es válido.');
        }

        if (!array_key_exists('confirmed_by_user', $registry) || !is_bool($registry['confirmed_by_user'])) {
            throw new RuntimeException('La confirmación del usuario debe ser booleana.');
        }

        if (!isset($registry['authorized_test_database']) || !is_array($registry['authorized_test_database'])) {
            throw new RuntimeException('Falta la base TEST autorizada.');
        }

        $database = $registry['authorized_test_database'];
        $identityFields = [
            'engine',
            'database_name',
            'connection_name',
            'host',
            'port',
            'schema',
            'server_identifier',
            'canonical_application_environment',
        ];

        self::assertOnlyKeys($database, $identityFields, 'base TEST');
        foreach ($identityFields as $field) {
            if (!array_key_exists($field, $database)) {
                throw new RuntimeException("Falta el campo {$field} del registro TEST.");
            }
        }

        if ($database['canonical_application_environment'] !== 'TEST') {
            throw new RuntimeException('El entorno canónico registrado debe ser TEST.');
        }

        if ($status === 'UNCONFIGURED') {
            if ($registry['confirmed_by_user'] !== false) {
                throw new RuntimeException('Un registro no configurado no puede estar confirmado.');
            }

            foreach ($identityFields as $field) {
                if ($field !== 'canonical_application_environment' && $database[$field] !== null) {
                    throw new RuntimeException('Un registro no configurado no puede contener identidad.');
                }
            }

            return;
        }

        if ($registry['confirmed_by_user'] !== true) {
            throw new RuntimeException('Un registro configurado requiere confirmación del usuario.');
        }

        foreach (['engine', 'database_name', 'connection_name'] as $field) {
            if (!is_string($database[$field]) || trim($database[$field]) === '') {
                throw new RuntimeException("El campo {$field} es obligatorio en un registro configurado.");
            }
        }

        foreach (['host', 'schema', 'server_identifier'] as $field) {
            if ($database[$field] !== null && (!is_string($database[$field]) || trim($database[$field]) === '')) {
                throw new RuntimeException("El campo {$field} debe ser null o una cadena no vacía.");
            }
        }

        if ($database['port'] !== null) {
            $port = $database['port'];
            if (!is_int($port) || $port < 1 || $port > 65535) {
                throw new RuntimeException('El puerto registrado no es válido.');
            }
        }
    }

    private static function assertOnlyKeys(array $value, array $allowedKeys, string $context): void
    {
        $unknownKeys = array_diff(array_keys($value), $allowedKeys);
        if ($unknownKeys !== []) {
            throw new RuntimeException("Hay campos desconocidos en {$context}: " . implode(', ', $unknownKeys));
        }
    }
}
