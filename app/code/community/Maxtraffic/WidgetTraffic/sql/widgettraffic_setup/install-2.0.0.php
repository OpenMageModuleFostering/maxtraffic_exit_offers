<?php
$installer = $this;
$installer->startSetup();
$table = $installer->getConnection()->newTable($installer->getTable('maxtraffic'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array(
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
        'identity' => true,
        ), 'ID')
    ->addColumn('maxtraffic_user', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array(
        'nullable' => false,
        ), 'Maxtraffic Website ID')
    ->addColumn('maxtraffic_website', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array(
        'nullable' => false,
        ), 'Maxtraffic Website ID')
    ->addColumn('maxtraffic_token', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => false,
        ), 'Maxtraffic token');
    $installer->getConnection()->createTable($table);
    $installer->endSetup();