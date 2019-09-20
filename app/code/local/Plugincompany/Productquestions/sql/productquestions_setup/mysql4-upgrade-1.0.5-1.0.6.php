<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Product Questions, released under the MIT License.
 * See the LICENSE file in the project root.
 */
$installer = $this;
$installer->startSetup();
$table = $installer->getTable('productquestions/questions');
$sql="
alter table {$table} add column(
        customer_id int(4) default null
);
";

$installer->run($sql);
$installer->endSetup();
	 
