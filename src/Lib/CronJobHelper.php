<?php

namespace Cronox\CronJobs\Lib;

use Cake\ORM\TableRegistry;
use ReflectionMethod;

class CronJobHelper
{
    /**
     * @param $className
     * @param $methodName
     * @param $parameters
     * @throws \ReflectionException
     */
    public static function checkMethod($className, $methodName, $parameters)
    {
        if (false === class_exists($className)) {
            throw new \Exception('Class '.$className.' not found.');
        }
        if (false === method_exists($className, $methodName)) {
            throw new \Exception('Method '.$methodName.' in class '.$className.' not found.');
        }
        if (false === is_callable([$className, $methodName])) {
            throw new \Exception('Method '.$methodName.' in class '.$className.' is not callable.');
        }

        try {
            $reflection = new ReflectionMethod($className, $methodName);
            $numberOfRequiredParameters = $reflection->getNumberOfRequiredParameters();
            if (count($parameters) < $numberOfRequiredParameters) {
                throw new \Exception('Not enough parameters for '.$className.'::'.$methodName);
            }
        } catch (\ReflectionException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @param $className
     * @param $methodName
     * @param array $parameters
     * @param string $startAt
     * @param null $code
     * @return \Cake\Datasource\EntityInterface
     * @throws \ReflectionException
     */
    public static function create($className, $methodName, $parameters = [], $startAt = 'NOW', $code = null)
    {
        self::checkMethod($className, $methodName, $parameters);

        if ('NOW' === strtoupper($startAt) || null === $startAt) {
            $startAt = date('Y-m-d H:i:s');
        } else {
            if (is_integer($startAt)) {
            } else {
                $startAt = date('Y-m-d H:i:s', strtotime($startAt));
            }
        }

        $CronJobsTable = TableRegistry::getTableLocator()->get('CronJobs');
        $jobEntity = $CronJobsTable->newEntity(
            [
                'class_name' => $className,
                'method_name' => $methodName,
                'serialized_params' => serialize($parameters),
                'start_at' => $startAt,
                'code' => $code
            ]
        );

        return $CronJobsTable->saveOrFail($jobEntity);
    }
}
