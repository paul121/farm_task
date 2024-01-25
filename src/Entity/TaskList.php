<?php

namespace Drupal\farm_task\Entity;

use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\RevisionLogEntityTrait;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\entity\Revision\RevisionableContentEntityBase;
use Drupal\user\EntityOwnerTrait;

/**
 * The task list entity.
 *
 * @ContentEntityType(
 *   id = "task_list",
 *   label = @Translation("Task list"),
 *   label_collection = @Translation("Task lists"),
 *   label_singular = @Translation("task list"),
 *   label_plural = @Translation("task lists"),
 *   handlers = {
 *      "storage" = "Drupal\Core\Entity\Sql\SqlContentEntityStorage",
 *      "access" = "\Drupal\entity\UncacheableEntityAccessControlHandler",
 *      "permission_provider" = "\Drupal\entity\UncacheableEntityPermissionProvider",
 *      "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *      "views_data" = "Drupal\views\EntityViewsData",
 *      "form" = {
 *        "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *      },
 *      "route_provider" = {
 *        "default" = "Drupal\entity\Routing\AdminHtmlRouteProvider",
 *        "revision" = "\Drupal\entity\Routing\RevisionRouteProvider",
 *      },
 *      "local_task_provider" = {
 *        "default" = "\Drupal\entity\Menu\DefaultEntityLocalTaskProvider",
 *      },
 *   },
 *   base_table = "task_list",
 *   data_table = "task_list_field_data",
 *   revision_table = "task_list_revision",
 *   translatable = TRUE,
 *   revisionable = TRUE,
 *   show_revision_ui = TRUE,
 *   admin_permission = "administer tasks",
 *   entity_keys = {
 *      "id" = "id",
 *      "revision" = "revision_id",
 *      "label" = "name",
 *      "owner" = "uid",
 *      "uuid" = "uuid",
 *      "langcode" = "langcode",
 *    },
 *    common_reference_target = TRUE,
 *    links = {
 *      "canonical" = "/task-list/{task_list}",
 *      "add-form" = "/task-list/add/{task_list}",
 *      "collection" = "/admin/content/task-list",
 *      "delete-form" = "/task-list/{task_list}/delete",
 *      "delete-multiple-form" = "/task-list/delete",
 *      "edit-form" = "/task-list/{task_list}/edit",
 *      "revision" = "/task-list/{task_list}/revisions/{task_list_revison}/view",
 *      "revision-revert-form" = "/task-list/{task_list}/revisions/{task_list_revison}/revert",
 *      "version-history" = "/task-list/{task_list}/revisions",
 *    },
 *    revision_metadata_keys = {
 *      "revision_user" = "revision_user",
 *      "revision_created" = "revision_created",
 *      "revision_log_message" = "revision_log_message"
 *    },
 *  )
 */
class TaskList extends RevisionableContentEntityBase implements TaskListInterface {

  use EntityChangedTrait;
  use EntityOwnerTrait;
  use RevisionLogEntityTrait;

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);
    $fields += static::ownerBaseFieldDefinitions($entity_type);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the task list.'))
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE)
      ->setRequired(TRUE)
      ->setSetting('max_length', 255)
      ->setSetting('text_processing', 0)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('form', TRUE);

    $fields['description'] = BaseFieldDefinition::create('text_long')
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE)
      ->setLabel(t('Description'))
      ->setDisplayOptions('form', [
        'type' => 'text_textarea',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'text_default',
        'label' => 'above',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the user was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the user was last edited.'))
      ->setTranslatable(TRUE);

    $fields['task'] = BaseFieldDefinition::create('entity_reference_revisions')
      ->setLabel('Tasks')
      ->setDescription('Add the tasks to include in this list.')
      ->setCardinality(FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED)
      ->setSetting('target_type', 'task')
      ->setSetting('handler', 'default:task')
      ->setSetting('handler_settings', [
        'sort' => [
          'field' => 'name',
          'direction' => 'asc',
        ],
        'auto_create' => FALSE,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}
