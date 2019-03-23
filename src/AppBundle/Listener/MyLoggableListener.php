<?php

namespace AppBundle\Listener;

use Gedmo\Loggable\LoggableListener;
use Gedmo\Tool\Wrapper\AbstractWrapper;


/**
 * Class MyLoggableListener
 * @package AppBundle\Listener
 */
class MyLoggableListener extends LoggableListener
{

    protected function getObjectChangeSetData($ea, $object, $logEntry)
    {
        $om = $ea->getObjectManager();
        $wrapped = AbstractWrapper::wrap($object, $om);
        $meta = $wrapped->getMetadata();
        $config = $this->getConfiguration($om, $meta->name);
        $uow = $om->getUnitOfWork();
        $values = [];

        $allvalues = [];

        foreach ($ea->getObjectChangeSet($uow, $object) as $field => $changes) {
            if (empty($config['versioned']) || !in_array($field, $config['versioned'])) {
                continue;
            }

            $oldValue = $changes[0];
            if ($meta->isSingleValuedAssociation($field) && $oldValue) {
                if ($wrapped->isEmbeddedAssociation($field)) {
                    $value = $this->getObjectChangeSetData($ea, $oldValue, $logEntry);
                } else {
                    $oid = spl_object_hash($oldValue);
                    $wrappedAssoc = AbstractWrapper::wrap($oldValue, $om);
                    $oldValue = $wrappedAssoc->getIdentifier(false);
                    if (!is_array($oldValue) && !$oldValue) {
                        $this->pendingRelatedObjects[$oid][] = [
                            'log' => $logEntry,
                            'field' => $field,
                        ];
                    }
                }
            }

            $value = $changes[1];
            if ($meta->isSingleValuedAssociation($field) && $value) {
                if ($wrapped->isEmbeddedAssociation($field)) {
                    $value = $this->getObjectChangeSetData($ea, $value, $logEntry);
                } else {
                    $oid = spl_object_hash($value);
                    $wrappedAssoc = AbstractWrapper::wrap($value, $om);
                    $value = $wrappedAssoc->getIdentifier(false);
                    if (!is_array($value) && !$value) {
                        switch ($field) {
                            case 'datedelivery':
                                $field = 'Fecha de Entrega';
                                break;
                            case 'datelast':
                                $field = 'Fecha última cuota';
                                break;
                            case 'dateend':
                                $field = 'Fecha termino obra';
                                break;
                            case 'dateescritura':
                                $field = 'Fecha de escrituración';
                                break;
                        }
                        $this->pendingRelatedObjects[$oid][] = [
                            'log' => $logEntry,
                            'field' => $field,
                        ];
                    }
                }
            }

            //fix for DateTime, integer and float entries
            if ($value == $oldValue) {
                continue;
            }

            $values[$field] = $value;
            $allvalues['new'][$field] = $value;
            $allvalues['old'][$field] = $oldValue;
        }

        return $allvalues;
    }
}