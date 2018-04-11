<?php

namespace AstralWeb\NLP\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{

    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        $this->addEntityTable($installer, Product::ENTITY);

        $installer->endSetup();
    }


    /**
     * @param SchemaSetupInterface $setup
     * @param string $entity
     * @return InstallSchema
     * @throws \Zend_Db_Exception
     */
    private function addEntityTable(SchemaSetupInterface $setup, string $entity): InstallSchemaInterface
    {
        $table = $setup->getConnection()
            ->newTable($setup->getTable('google_nlp_sentiment'))
            ->addColumn(
                'analyze_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ],
                'Analyze ID'
            )
            ->addColumn(
                'entity_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => false,
                    'unsigned' => false,
                    'nullable' => true,
                    'primary' => false
                ],
                'Entity ID'
            )
            ->addColumn(
                'entity_type_id',
                Table::TYPE_TEXT,
                32,
                [
                    'identity' => false,
                    'unsigned' => false,
                    'nullable' => true,
                    'primary' => false
                ],
                'Entity Type ID'
            )
            ->addColumn(
                'detail',
                Table::TYPE_TEXT,
                null,
                [
                    'nullable' => true
                ],
                ''
            )
            ->addColumn(
                'magnitude',
                Table::TYPE_FLOAT,
                4,
                [
                    'nullable' => true
                ],
                'magnitude of sentiment'
            )
            ->addColumn(
                'score',
                Table::TYPE_FLOAT,
                4,
                [
                    'nullable' => true
                ],
                'score of sentiment'
            )
            ->addColumn(
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                [
                    'nullable' => false,
                    'default' => Table::TIMESTAMP_INIT
                ],
                'Creation Time'
            );

        $setup->getConnection()->createTable($table);

        return $this;
    }
}
