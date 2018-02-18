<?php

use yii\db\Migration;

class M161111144563_SystemParam_Init extends Migration
{
    public function up()
    {
        $this->execute('
                CREATE TABLE `system_param` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `param_key` varchar(100) NOT NULL,
                  `param_value` varchar(100) NOT NULL,
                  `description` varchar(255) DEFAULT NULL,
                  `validator` varchar(50) DEFAULT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8
        ');
    }

    public function down()
    {
        $this->execute('
            DROP TABLE `system_param`;
        ');
    }
}
