<?php

declare(strict_types=1);

namespace App\Pager\Shared;

use App\Enum\Pager\ColumnEnum;
use App\Form\Type\Pager\Shared\BasePagerFormType;
use App\Model\Pager\Column;
use App\Tools\Parser\ArrayParser;
use App\Tools\Parser\StringParser;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractPager implements PagerInterface
{
    #[Required]
    public EntityManagerInterface $entityManager;
    #[Required]
    public FormFactoryInterface $formFactory;
    #[Required]
    public RouterInterface $router;

    protected static ?string $pagerId = null;

    protected static string $formType = BasePagerFormType::class;

    /**
     * @var array<string, string>
     */
    protected static array $defaultSort = ['createdAt' => 'DESC'];

    /**
     * @var array<ColumnEnum>
     */
    protected static array $columns = [];

    /**
     * @var array<Column>
     */
    protected static array $columnSchema = [];

    private ?Request $request = null;

    private ?FormInterface $form = null;

    protected Query|QueryBuilder|null $query = null;

    public function init(Request $request): void
    {
        $this->request = $request;
        $this->form = $this->formFactory->create(static::$formType, null, [
            'action' => $this->request->getRequestUri(),
            'pager_default_data' => static::$defaultSort,
        ]);

        $this->configureColumnSchema();
    }

    /**
     * @return array<mixed>
     */
    public function getItems(): array
    {
        if (!$this->query) {
            return [];
        }

        if ($this->query instanceof QueryBuilder) {
            return $this->query->getQuery()->getResult();
        }

        return $this->query->getResult();
    }

    public function getItemsCount(): int
    {
        if (!$this->query) {
            return 0;
        }

        return (new Paginator($this->query))->count();
    }

    /**
     * @return array<Column>
     */
    public function getColumns(): array
    {
        $columns = [];
        foreach (static::$columns as $columnId) {
            $column = \array_filter(static::$columnSchema, static fn (Column $column) => $column->getId() === $columnId);
            $columns[$columnId->name] = ArrayParser::getFirstValue($column);
        }

        return $columns;
    }

    public function getPagerId(): string
    {
        if (static::$pagerId) {
            return static::$pagerId;
        }

        return StringParser::slugify(static::class);
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getForm(): FormInterface
    {
        if (!$this->form) {
            throw new \Exception("Form must be initialized before calling this method");
        }

        return $this->form;
    }

    public function setForm(FormInterface $form): static
    {
        $this->form = $form;

        return $this;
    }

    /**
     * @return array<string, string>
     */
    public function getSort(): array
    {
        return static::$defaultSort;
    }

    public function getLimit(): int
    {
        return static::DEFAULT_LIMIT;
    }

    abstract protected function configureColumnSchema(): void;
}
