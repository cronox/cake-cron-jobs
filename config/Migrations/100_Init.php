<?php

use Phinx\Migration\AbstractMigration;

class Init extends AbstractMigration
{
    /**
     * [up description]
     * @return void
     */
    public function up()
    {
        $this->table('cron_jobs')
            ->addColumn(
                'class_name',
                'string',
                [
                    'default' => null,
                    'null' => false,
                ]
            )
            ->addColumn(
                'method_name',
                'string',
                [
                    'default' => null,
                    'null' => false,
                ]
            )
            ->addColumn(
                'serialized_params',
                'text',
                [
                    'default' => null,
                    'null' => false,
                ]
            )
            ->addColumn(
                'method_result',
                'text',
                [
                    'default' => null,
                    'null' => true,
                ]
            )
            ->addColumn(
                'exception',
                'text',
                [
                    'default' => null,
                    'null' => true,
                ]
            )
            ->addColumn(
                'start_at',
                'datetime',
                [
                    'default' => null,
                    'limit' => null,
                    'null' => true,
                ]
            )
            ->addColumn(
                'started',
                'datetime',
                [
                    'default' => null,
                    'limit' => null,
                    'null' => true,
                ]
            )
            ->addColumn(
                'ended',
                'datetime',
                [
                    'default' => null,
                    'limit' => null,
                    'null' => true,
                ]
            )
            ->addColumn(
                'created',
                'datetime',
                [
                    'default' => null,
                    'limit' => null,
                    'null' => false,
                ]
            )
            ->addColumn(
                'modified',
                'datetime',
                [
                    'default' => null,
                    'limit' => null,
                    'null' => false,
                ]
            )
            ->create();
    }

    /**
     * [down description]
     * @return void
     */
    public function down()
    {
        $this->dropTable('cron_jobs');
    }
}
