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
drop table if exists plugincompany_questions;
create table {$table}(
    question_id int not null auto_increment,
    question text,
    answer text,
    approved int,
    product_id int,
    date_asked datetime not NULL,
    date_answered datetime,
    email varchar(100),
    name varchar(255),
    answered_by varchar(255),
    store_ids varchar(255),
    originalstoreid varchar(255),
    is_notified int default 0,
    private int default 0,
    primary key(question_id)
    )
    CHARACTER SET utf8 COLLATE utf8_general_ci
    ;
";

$installer->run($sql);
$installer->endSetup();