<?php

namespace Drupal\farm_task\Plugin\Plan\PlanType;

use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\entity\BundleFieldDefinition;
use Drupal\farm_entity\Plugin\Plan\PlanType\FarmPlanType;

/**
 * Provides the task plan type.
 *
 * @PlanType(
 *   id = "task",
 *   label = @Translation("Task"),
 * )
 */
class Task extends FarmPlanType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();

    $fields['task_list'] = BundleFieldDefinition::create('entity_reference_revisions')
      ->setLabel('Task lists')
      ->setCardinality(FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED)
      ->setSetting('target_type', 'task_list')
      ->setSetting('handler', 'default:task_list')
      ->setSetting('handler_settings', [
        'sort' => [
          'field' => 'name',
          'direction' => 'asc',
        ],
        'auto_create' => FALSE,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'inline_entity_form_complex',
        'settings' => [
          'form_mode' => 'default',
          'revision' => TRUE,
          'allow_new' => TRUE,
          'allow_existing' => FALSE,
          'allow_duplicate' => TRUE,
        ],
        'weight' => '-5',
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'entity_reference_revisions_task_list',
        'settings' => [
          'view_mode' => 'default',
          'link' => FALSE,
        ],
        'weight' => '-5',
      ]);

    return $fields;
  }

}
