<?php

declare(strict_types=1);

use MultiagentSecurity\AuthorizationMatrix;
use MultiagentSecurity\TestDatabaseRegistry;

if (PHP_SAPI !== 'cli' || $argc !== 1) {
    fwrite(STDERR, "Este ejecutor no admite argumentos ni otro SAPI.\n");
    exit(1);
}

require dirname(__DIR__) . '/src/TestDatabaseRegistry.php';
require dirname(__DIR__) . '/src/AuthorizationMatrix.php';

$results = [];

$expect = static function (string $id, $expected, $actual) use (&$results): void {
    $results[] = ['id' => $id, 'passed' => $expected === $actual, 'expected' => $expected, 'actual' => $actual];
};

$expectThrows = static function (string $id, string $expectedMessage, callable $callback) use (&$results): void {
    try {
        $callback();
        $results[] = ['id' => $id, 'passed' => false, 'expected' => $expectedMessage, 'actual' => 'no exception'];
    } catch (Throwable $exception) {
        $message = $exception->getMessage();
        $results[] = [
            'id' => $id,
            'passed' => $exception instanceof RuntimeException && str_contains($message, $expectedMessage),
            'expected' => $expectedMessage,
            'actual' => $message,
        ];
    }
};

$markdown = static function (array $registry): string {
    $json = json_encode($registry, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
    return "<!-- TEST_DATABASE_REGISTRY:BEGIN -->\n```json\n{$json}\n```\n<!-- TEST_DATABASE_REGISTRY:END -->";
};

$unconfigured = [
    'schema_version' => 1,
    'status' => 'UNCONFIGURED',
    'authorized_test_database' => [
        'engine' => null,
        'database_name' => null,
        'connection_name' => null,
        'host' => null,
        'port' => null,
        'schema' => null,
        'server_identifier' => null,
        'canonical_application_environment' => 'TEST',
    ],
    'confirmed_by_user' => false,
];

$configured = [
    'schema_version' => 1,
    'status' => 'CONFIGURED',
    'authorized_test_database' => [
        'engine' => 'mysql',
        'database_name' => 'registered_test',
        'connection_name' => 'testing',
        'host' => '127.0.0.1',
        'port' => 3306,
        'schema' => null,
        'server_identifier' => 'server-test-1',
        'canonical_application_environment' => 'TEST',
    ],
    'confirmed_by_user' => true,
];

$connection = static function (array $registry, bool $verified = true): array {
    return [
        'identity_verified' => $verified,
        'identity' => $registry['authorized_test_database'],
    ];
};

try {
    $parsedUnconfigured = TestDatabaseRegistry::fromMarkdown($markdown($unconfigured));
    $expect('REG-001', 'UNCONFIGURED', $parsedUnconfigured['status']);
    $expect('REG-002', false, $parsedUnconfigured['confirmed_by_user']);

    $projectRegistry = TestDatabaseRegistry::fromFile(dirname(__DIR__, 2) . '/bases-de-datos/test-database.md');
    $expect('REG-003', 'TEST', $projectRegistry['authorized_test_database']['canonical_application_environment']);

    $expectThrows('REG-004', 'El JSON del registro TEST no es válido.', static function () use ($markdown, $unconfigured): void {
        TestDatabaseRegistry::fromMarkdown(str_replace('"schema_version": 1', '"schema_version": ', $markdown($unconfigured)));
    });
    $expectThrows('REG-005', 'Debe existir exactamente un bloque de registro TEST.', static function () use ($markdown, $unconfigured): void {
        TestDatabaseRegistry::fromMarkdown($markdown($unconfigured) . $markdown($unconfigured));
    });
    $expectThrows('REG-006', 'Un registro no configurado no puede contener identidad.', static function () use ($markdown, $unconfigured): void {
        $invalid = $unconfigured;
        $invalid['authorized_test_database']['database_name'] = 'invented';
        TestDatabaseRegistry::fromMarkdown($markdown($invalid));
    });
    $expectThrows('REG-007', 'Un registro configurado requiere confirmación del usuario.', static function () use ($markdown, $configured): void {
        $invalid = $configured;
        $invalid['confirmed_by_user'] = false;
        TestDatabaseRegistry::fromMarkdown($markdown($invalid));
    });
    $expectThrows('REG-008', 'Hay campos desconocidos en base TEST', static function () use ($markdown, $configured): void {
        $invalid = $configured;
        $invalid['authorized_test_database']['extra'] = 'no-permitido';
        TestDatabaseRegistry::fromMarkdown($markdown($invalid));
    });
    $expectThrows('REG-009', 'El estado del registro TEST no es válido.', static function () use ($markdown, $unconfigured): void {
        $invalid = $unconfigured;
        $invalid['status'] = 'OTHER';
        TestDatabaseRegistry::fromMarkdown($markdown($invalid));
    });
    $expectThrows('REG-010', 'Falta el campo engine del registro TEST.', static function () use ($markdown, $configured): void {
        $invalid = $configured;
        unset($invalid['authorized_test_database']['engine']);
        TestDatabaseRegistry::fromMarkdown($markdown($invalid));
    });
    $expectThrows('REG-011', 'El puerto registrado no es válido.', static function () use ($markdown, $configured): void {
        $invalid = $configured;
        $invalid['authorized_test_database']['port'] = 70000;
        TestDatabaseRegistry::fromMarkdown($markdown($invalid));
    });
    $expectThrows('REG-012', 'La confirmación del usuario debe ser booleana.', static function () use ($markdown, $configured): void {
        $invalid = $configured;
        $invalid['confirmed_by_user'] = 'yes';
        TestDatabaseRegistry::fromMarkdown($markdown($invalid));
    });
    $expectThrows('REG-013', 'El entorno canónico registrado debe ser TEST.', static function () use ($markdown, $configured): void {
        $invalid = $configured;
        $invalid['authorized_test_database']['canonical_application_environment'] = 'DEVELOPMENT';
        TestDatabaseRegistry::fromMarkdown($markdown($invalid));
    });
    $expectThrows('REG-014', 'La versión del registro TEST no es compatible.', static function () use ($markdown, $configured): void {
        $invalid = $configured;
        $invalid['schema_version'] = 2;
        TestDatabaseRegistry::fromMarkdown($markdown($invalid));
    });
    $expectThrows('REG-015', 'El registro TEST no existe.', static function (): void {
        TestDatabaseRegistry::fromFile(__DIR__ . '/registro-inexistente.md');
    });
    $expectThrows('REG-016', 'El bloque de registro TEST no tiene un formato válido.', static function (): void {
        TestDatabaseRegistry::fromMarkdown('<!-- TEST_DATABASE_REGISTRY:BEGIN -->sin-json<!-- TEST_DATABASE_REGISTRY:END -->');
    });
    $expectThrows('REG-017', 'El registro TEST debe ser un objeto JSON.', static function (): void {
        TestDatabaseRegistry::fromMarkdown("<!-- TEST_DATABASE_REGISTRY:BEGIN -->\n```json\n\"texto\"\n```\n<!-- TEST_DATABASE_REGISTRY:END -->");
    });
    $expectThrows('REG-018', 'Hay campos desconocidos en registro', static function () use ($markdown, $configured): void {
        $invalid = $configured;
        $invalid['extra'] = 'no-permitido';
        TestDatabaseRegistry::fromMarkdown($markdown($invalid));
    });
    $expectThrows('REG-019', 'Falta el campo canonical_application_environment del registro TEST.', static function () use ($markdown, $configured): void {
        $invalid = $configured;
        unset($invalid['authorized_test_database']['canonical_application_environment']);
        TestDatabaseRegistry::fromMarkdown($markdown($invalid));
    });
    $expectThrows('REG-020', 'El campo engine es obligatorio en un registro configurado.', static function () use ($markdown, $configured): void {
        $invalid = $configured;
        $invalid['authorized_test_database']['engine'] = '  ';
        TestDatabaseRegistry::fromMarkdown($markdown($invalid));
    });
    $expectThrows('REG-021', 'El campo host debe ser null o una cadena no vacía.', static function () use ($markdown, $configured): void {
        $invalid = $configured;
        $invalid['authorized_test_database']['host'] = '';
        TestDatabaseRegistry::fromMarkdown($markdown($invalid));
    });
    $expectThrows('REG-022', 'Un registro no configurado no puede estar confirmado.', static function () use ($markdown, $unconfigured): void {
        $invalid = $unconfigured;
        $invalid['confirmed_by_user'] = true;
        TestDatabaseRegistry::fromMarkdown($markdown($invalid));
    });
    $expectThrows('REG-023', 'El puerto registrado no es válido.', static function () use ($markdown, $configured): void {
        $invalid = $configured;
        $invalid['authorized_test_database']['port'] = 0;
        TestDatabaseRegistry::fromMarkdown($markdown($invalid));
    });
    $expectThrows('REG-024', 'El puerto registrado no es válido.', static function () use ($markdown, $configured): void {
        $invalid = $configured;
        $invalid['authorized_test_database']['port'] = '3306';
        TestDatabaseRegistry::fromMarkdown($markdown($invalid));
    });

    $expect('DB-001', true, AuthorizationMatrix::authorize($configured, 'TEST', [$connection($configured)], true));

    $development = $configured;
    $development['authorized_test_database']['database_name'] = 'development';
    $expect('DB-002', false, AuthorizationMatrix::authorize($configured, 'TEST', [$connection($development)], true));

    $production = $configured;
    $production['authorized_test_database']['database_name'] = 'production';
    $expect('DB-003', false, AuthorizationMatrix::authorize($configured, 'TEST', [$connection($production)], true));
    $expect('DB-004', false, AuthorizationMatrix::authorize($configured, 'DEVELOPMENT', [$connection($configured)], true));
    $expect('DB-005', false, AuthorizationMatrix::authorize($configured, 'PRODUCTION', [$connection($configured)], true));
    $expect('DB-006', false, AuthorizationMatrix::authorize($configured, 'DEVELOPMENT', [$connection($development)], true));
    $expect('DB-007', false, AuthorizationMatrix::authorize($configured, 'PRODUCTION', [$connection($production)], true));
    $expect('DB-008', false, AuthorizationMatrix::authorize($configured, 'UNKNOWN', [$connection($configured)], true));
    $expect('DB-009', false, AuthorizationMatrix::authorize($unconfigured, 'TEST', [$connection($configured)], true));
    $expect('DB-010', false, AuthorizationMatrix::authorize($configured, 'TEST', [$connection($configured, false)], true));

    $differentServer = $configured;
    $differentServer['authorized_test_database']['server_identifier'] = 'another-server';
    $expect('DB-011', false, AuthorizationMatrix::authorize($configured, 'TEST', [$connection($differentServer)], true));

    $unknownSecondary = $configured;
    $unknownSecondary['authorized_test_database']['database_name'] = 'unknown_secondary';
    $expect('DB-012', false, AuthorizationMatrix::authorize($configured, 'TEST', [$connection($configured), $connection($unknownSecondary)], true));
    $expect('DB-013', false, AuthorizationMatrix::authorize($configured, 'TEST', [$connection($configured)], false));
    $expect('DB-014', false, AuthorizationMatrix::authorize($configured, 'testing', [$connection($configured)], true));
    $expect('DB-015', false, AuthorizationMatrix::authorize($configured, 'TEST', [], true));

    $unknownEngine = $configured;
    $unknownEngine['authorized_test_database']['engine'] = 'sqlserver';
    $expect('DB-016', false, AuthorizationMatrix::authorize($unknownEngine, 'TEST', [$connection($unknownEngine)], true));

    $sqlite = $configured;
    $sqlite['authorized_test_database'] = [
        'engine' => 'sqlite',
        'database_name' => 'C:/registered/test.sqlite',
        'connection_name' => 'testing',
        'host' => null,
        'port' => null,
        'schema' => null,
        'server_identifier' => null,
        'canonical_application_environment' => 'TEST',
    ];
    $expect('DB-017', true, AuthorizationMatrix::authorize($sqlite, 'TEST', [$connection($sqlite)], true));

    $wrongType = $configured;
    $wrongType['authorized_test_database']['port'] = 1;
    $wrongTypeConnection = $connection($wrongType);
    $wrongTypeConnection['identity']['port'] = true;
    $expect('DB-018', false, AuthorizationMatrix::authorize($wrongType, 'TEST', [$wrongTypeConnection], true));

    $failed = 0;
    foreach ($results as $result) {
        if ($result['passed']) {
            fwrite(STDOUT, "PASS {$result['id']}\n");
            continue;
        }

        $failed++;
        fwrite(STDERR, "FAIL {$result['id']} | expected=" . json_encode($result['expected']) . ' actual=' . json_encode($result['actual']) . "\n");
    }

    $total = count($results);
    fwrite(STDOUT, "RESULT: " . ($failed === 0 ? 'PASS' : 'FAIL') . " | tests={$total} | failures={$failed} | mode=LOGIC_ONLY_NO_DATABASE\n");
    exit($failed === 0 ? 0 : 1);
} catch (Throwable $exception) {
    fwrite(STDERR, 'FAIL RUNNER: ' . $exception->getMessage() . "\n");
    exit(1);
}
