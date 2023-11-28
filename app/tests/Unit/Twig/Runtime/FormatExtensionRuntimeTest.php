<?php

declare(strict_types=1);

namespace App\Tests\Unit\Twig\Runtime;

use App\Entity\User;
use App\Tests\Shared\AbstractTestCase;
use App\Twig\Runtime\FormatExtensionRuntime;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Contracts\Translation\TranslatorInterface;

class FormatExtensionRuntimeTest extends AbstractTestCase
{
    private ?FormatExtensionRuntime $formatExtensionRuntime;

    private UrlGeneratorInterface|ObjectProphecy|null $urlGenerator;

    private TranslatorInterface|ObjectProphecy|null $translator;

    protected function setUp(): void
    {
        $this->urlGenerator = $this->prophesize(UrlGeneratorInterface::class);
        $this->translator = $this->prophesize(TranslatorInterface::class);

        $this->formatExtensionRuntime = new FormatExtensionRuntime(
            $this->urlGenerator->reveal(),
            $this->translator->reveal(),
        );
    }

    public function testFormatDateWithUnlimited(): void
    {
        $this->assertSame(
            '<i class="fas fa-infinity"></i>',
            $this->formatExtensionRuntime->formatDate(null, true)
        );

        $this->assertSame(
            '01/01/2021',
            $this->formatExtensionRuntime->formatDate(new \DateTime('2021-01-01'))
        );
    }

    public function testFormatDateTime(): void
    {
        $this->assertSame(
            '01/01/2021 00:00:00',
            $this->formatExtensionRuntime->formatDateTime(new \DateTime('2021-01-01'))
        );

        $this->assertSame(
            '<i class="fas fa-infinity"></i>',
            $this->formatExtensionRuntime->formatDateTime(null, true)
        );
    }

    public function testFormatInitial(): void
    {
        $this->assertSame(
            'AB',
            $this->formatExtensionRuntime->formatInitial('A B')
        );
    }

    public function testFormatUser(): void
    {
        $this->urlGenerator->generate('app_user_show', ['id' => 1])->willReturn('/user/1');

        $user = (new User())
            ->setId(1)
            ->setFirstName('John')
            ->setLastName('Doe')
        ;

        $this->assertSame(
            '<a href="/user/1">John Doe</a>',
            $this->formatExtensionRuntime->formatUser($user)
        );

        $this->assertNull(
            $this->formatExtensionRuntime->formatUser(null)
        );
    }

    public function testFormatCountry(): void
    {
        $this->assertSame(
            'France',
            $this->formatExtensionRuntime->formatCountry('FR')
        );

        $this->assertNull($this->formatExtensionRuntime->formatCountry(null));
    }

    public function testFormatAttributes(): void
    {
        $this->assertSame(
            'foo="bar" bar="foo"',
            $this->formatExtensionRuntime->formatAttributes([
                'foo' => 'bar',
                'bar' => 'foo',
            ])
        );

        $this->translator->trans('bar', [], 'messages')->willReturn('bar');
        $this->translator->trans('foo', [], 'messages')->willReturn('foo');

        $this->assertSame(
            'foo="bar" bar="foo"',
            $this->formatExtensionRuntime->formatAttributes([
                'foo' => new TranslatableMessage('bar', [], 'messages'),
                'bar' => new TranslatableMessage('foo', [], 'messages'),
            ])
        );

        $this->assertSame(
            '',
            $this->formatExtensionRuntime->formatAttributes([])
        );
    }

    /**
     * @dataProvider provideBoolFormat
     */
    public function testBoolFormat(bool $value, bool $onlyTrue, bool $color, ?string $expected): void
    {
        $this->assertSame(
            $expected,
            $this->formatExtensionRuntime->boolFormat($value, $onlyTrue, $color)
        );
    }

    /**
     * @return array<int, array{0: bool, 1: bool, 2: bool, 3: string|null}>
     */
    public function provideBoolFormat(): array
    {
        return [
            [true, false, false, '<i class=\'fas fa-check \'></i>'],
            [false, false, false, '<i class=\'fas fa-times \'></i>'],
            [true, true, false, '<i class=\'fas fa-check \'></i>'],
            [false, true, false, null],
            [true, false, true, '<i class=\'fas fa-check text-success\'></i>'],
            [false, false, true, '<i class=\'fas fa-times text-danger\'></i>'],
            [true, true, true, '<i class=\'fas fa-check text-success\'></i>'],
            [false, true, true, null],
        ];
    }

    public function testArchivedFormat(): void
    {
        $this->translator->trans('Archived', [], 'misc')->willReturn('Archived');

        $this->assertSame(
            '<i class=\'fas fa-archive text-warning\' title=\'Archived\'></i>',
            $this->formatExtensionRuntime->archivedFormat(true)
        );

        $this->assertNull(
            $this->formatExtensionRuntime->archivedFormat(false)
        );
    }
}
