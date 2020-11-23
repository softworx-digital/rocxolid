<?php

namespace Softworx\RocXolid\Tables\Builders;

// rocXolid requests
use Softworx\RocXolid\Http\Requests\TableRequest;
// rocXolid table contracts
use Softworx\RocXolid\Tables\Contracts\Table;
use Softworx\RocXolid\Tables\Contracts\Tableable;
// rocXolid table builder contracts
use Softworx\RocXolid\Tables\Builders\Contracts\TableBuilder as TableBuilderContract;
use Softworx\RocXolid\Tables\Builders\Contracts\TableFilterBuilder;
use Softworx\RocXolid\Tables\Builders\Contracts\TableColumnBuilder;
use Softworx\RocXolid\Tables\Builders\Contracts\TableButtonBuilder;

/**
 * Table builder and dependencies connector.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
class TableBuilder implements TableBuilderContract
{
    /**
     * Reference to table filter builder.
     *
     * @var \Softworx\RocXolid\Tables\Builders\Contracts\TableFilterBuilder
     */
    protected $table_filter_builder;

    /**
     * Reference to table columns builder.
     *
     * @var \Softworx\RocXolid\Tables\Builders\Contracts\TableColumnBuilder
     */
    protected $table_column_builder;

    /**
     * Reference to table rows buttons builder.
     *
     * @var \Softworx\RocXolid\Tables\Builders\Contracts\TableButtonBuilder
     */
    protected $table_button_builder;

    /**
     * Constructor.
     *
     * @param \Softworx\RocXolid\Tables\Builders\Contracts\TableFilterBuilder $table_filter_builder
     * @param \Softworx\RocXolid\Tables\Builders\Contracts\TableColumnBuilder $table_column_builder
     * @param \Softworx\RocXolid\Tables\Builders\Contracts\TableButtonBuilder $table_button_builder
     */
    public function __construct(
        TableFilterBuilder $table_filter_builder,
        TableColumnBuilder $table_column_builder,
        TableButtonBuilder $table_button_builder
    ) {
        $this->table_filter_builder = $table_filter_builder;
        $this->table_column_builder = $table_column_builder;
        $this->table_button_builder = $table_button_builder;
    }

    /**
     * {@inheritDoc}
     */
    public function buildTable(Tableable $container, string $type, string $param, array $custom_options = []): Table
    {
        $table = app($this->validateTableType($type), [
            'param' => $param,
        ]);

        $this
            ->setTableDependencies($table, $container)
            ->setTableOptions($table, $custom_options);

        // @todo: delegate to subbuilders, not table itself
        $table
            ->buildFilters()
            ->buildColumns()
            ->buildButtons()
            ->init();

        return $table;
    }

    /**
     * Check the provided class conforms the restrains.
     *
     * @param string $type
     * @param string $interface
     * @return string
     */
    protected function validateTableType(string $type, string $interface = Table::class): string
    {
        if (!class_exists($type)) {
            throw new \InvalidArgumentException(sprintf('Table class [%s] does not exist.', $type));
        }

        if (!(new \ReflectionClass($type))->implementsInterface($interface)) {
            throw new \InvalidArgumentException(sprintf('Class must be or extend [%s]; [%s] is not.', $interface, $type));
        }

        return $type;
    }

    /**
     * Set depedencies on existing table instance.
     *
     * @param \Softworx\RocXolid\Tables\Contracts\Table $table
     * @param \Softworx\RocXolid\Tables\Contracts\Tableable $controller
     * @return \Softworx\RocXolid\Tables\Contracts\TableBuilder
     */
    protected function setTableDependencies(Table &$table, Tableable $controller): TableBuilderContract
    {
        $table
            ->setTableBuilder($this)
            ->setTableFilterBuilder($this->table_filter_builder)
            ->setTableColumnBuilder($this->table_column_builder)
            ->setTableButtonBuilder($this->table_button_builder)
            ->setRequest(app(TableRequest::class))
            ->setController($controller); // @todo: container would be nicer...?

        return $this;
    }

    /**
     * Set options on existing table instance.
     *
     * @param \Softworx\RocXolid\Tables\Contracts\Table $table
     * @param array $custom_options
     * @return \Softworx\RocXolid\Tables\Contracts\TableBuilder
     */
    protected function setTableOptions(Table &$table, array $custom_options = []): TableBuilderContract
    {
        $table
            ->processTableOptions()
            ->setCustomOptions($custom_options);

        return $this;
    }
}
