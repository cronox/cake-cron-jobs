<?php

namespace Cronox\CronJobs\Shell;

use Cake\Console\Shell;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cronox\CronJobs\Lib\CronJobHelper;
use ReflectionMethod;

class CronJobsShell extends Shell
{

    /**
     * @return bool|int|void|null
     */
    public function main($code = null)
    {
        $whereConditions = ['start_at <' => date('Y-m-d H:i:s')];
        if (!empty($code)) {
            $whereConditions['code'] = $code;
        }
        $Table = TableRegistry::get('CronJobs');
        $jobs = $Table->find()
            ->where($whereConditions)
            ->andWhere(['ended IS' => null])
            ->all();

        $this->out('<info>Found '.$jobs->count().' jobs.</info>');

        foreach ($jobs as $job) {
            $job = $this->runJob($job);
            if (empty($job->getErrors())) {
                $Table->save($job);
            }
        }
    }

    /**
     * @param Entity $job
     * @return Entity
     */
    private function runJob(Entity $job): Entity
    {
        $className = $job->class_name;
        $methodName = $job->method_name;
        $parameters = unserialize($job->serialized_params);

        $this->out('Running job #'.$job->id.' <comment>'.$className.'::'.$methodName.'</comment>');

        $job->started = date('Y-m-d H:i:s');

        try {
            CronJobHelper::checkMethod($className, $methodName, $parameters);
            $reflection = new ReflectionMethod($className, $methodName);
            if (false === $reflection->isStatic()) {
                $className = new $className;
            }
            $result = call_user_func_array([$className, $methodName], $parameters);
            $job->method_result = serialize($result);
            $this->out('Job is completed <success>correctly</success>');
        } catch (\Exception $exception) {
            $job->exception = $exception->getMessage();
            $this->log($exception->getMessage());
            $this->out('Job is completed <error>incorrectly</error>');
        }

        $job->ended = date('Y-m-d H:i:s');

        return $job;
    }
}
