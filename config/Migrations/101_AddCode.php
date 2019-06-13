<?php

use Phinx\Migration\AbstractMigration;

class AddCode extends AbstractMigration
{
    /**
     * [up description]
     * @return void
     */
    public function up()
    {
        $this->table('cron_jobs')
            ->addColumn(
                'code',
                'string',
                [
                    'default' => null,
                    'null' => true,
                ]
            )
            ->update();
    }

    /**
     * [down description]
     * @return void
     */
    public function down()
    {
        $this->table('cron_jobs')
            ->removeColumn('code')
            ->update();
    }
}
