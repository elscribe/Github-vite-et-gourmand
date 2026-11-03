<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\UserModel;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Garantit la passerelle entre les libelles SQL et les roles applicatifs.
 */
final class UserRoleNormalizationTest extends TestCase
{
    /**
     * @return iterable<string, array{string, string}>
     */
    public static function roleProvider(): iterable
    {
        yield 'administrateur SQL' => ['Administrator', 'administrateur'];
        yield 'administrateur francais' => ['administrateur', 'administrateur'];
        yield 'employe SQL' => ['Employee', 'employe'];
        yield 'employe accentue' => ['Employé', 'employe'];
        yield 'client SQL' => ['Customer', 'utilisateur'];
        yield 'role inconnu' => ['unknown', 'utilisateur'];
    }

    #[DataProvider('roleProvider')]
    public function testRoleSqlEstNormalisePourLesReglesApplicatives(string $storedRole, string $expectedRole): void
    {
        self::assertSame($expectedRole, (new UserModel())->normalizeRole($storedRole));
    }
}
