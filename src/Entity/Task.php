<?php

namespace Drupal\farm_task\Entity;

use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\RevisionLogEntityTrait;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Form\FormStateInterface;
use Drupal\entity\Revision\RevisionableContentEntityBase;
use Drupal\user\EntityOwnerTrait;

/**
 * The task entity.
 *
 * @ContentEntityType(
 *   id = "task",
 *   label = @Translation("Task"),
 *   bundle_label = @Translation ("Task type"),
 *   label_collection = @Translation("Tasks"),
 *   label_singular = @Translation("task"),
 *   label_plural = @Translation("tasks"),
 *   handlers = {
 *      "storage" = "Drupal\Core\Entity\Sql\SqlContentEntityStorage",
 *      "access" = "\Drupal\entity\UncacheableEntityAccessControlHandler",
 *      "permission_provider" = "\Drupal\entity\UncacheableEntityPermissionProvider",
 *      "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *      "views_data" = "Drupal\views\EntityViewsData",
 *      "list_builder" = "Drupal\farm_task\TaskListBuilder",
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
 *   base_table = "task",
 *   data_table = "task_field_data",
 *   revision_table = "task_revision",
 *   translatable = TRUE,
 *   revisionable = TRUE,
 *   show_revision_ui = TRUE,
 *   admin_permission = "administer tasks",
 *   entity_keys = {
 *      "id" = "id",
 *      "revision" = "revision_id",
 *      "bundle" = "type",
 *      "label" = "name",
 *      "owner" = "uid",
 *      "uuid" = "uuid",
 *      "langcode" = "langcode",
 *    },
 *    bundle_entity_type = "task_type",
 *    bundle_plugin_type = "task_type",
 *    common_reference_target = TRUE,
 *    links = {
 *      "canonical" = "/task/{task}",
 *      "add-page" = "/task/add",
 *      "add-form" = "/task/add/{task_type}",
 *      "collection" = "/admin/content/task",
 *      "delete-form" = "/task/{task}/delete",
 *      "delete-multiple-form" = "/task/delete",
 *      "edit-form" = "/task/{task}/edit",
 *      "revision" = "/task/{task}/revisions/{task_revison}/view",
 *      "revision-revert-form" = "/task/{task}/revisions/{task_revison}/revert",
 *      "version-history" = "/task/{task}/revisions",
 *    },
 *    revision_metadata_keys = {
 *      "revision_user" = "revision_user",
 *      "revision_created" = "revision_created",
 *      "revision_log_message" = "revision_log_message"
 *    },
 *  )
 */
class Task extends RevisionableContentEntityBase implements TaskInterface {

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
      ->setDescription(t('The name of the task.'))
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

    $fields['completed'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Completed'))
      ->setDescription(t('If the task is completed.'))
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE)
      ->setDefaultValue(FALSE)
      ->setSetting('on_label', t('Yes'))
      ->setSetting('off_label', t('No'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'boolean_checkbox',
      ])
      ->setDisplayOptions('view', [
        'label' => 'inline',
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the user was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the user was last edited.'))
      ->setTranslatable(TRUE);

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function completed(): bool {
    return $this->get('completed')->value ?? FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function getTargetFieldName(): string {
    return "{$this->bundle()}_value";
  }

  /**
   * {@inheritdoc}
   */
  public function getComponentConfiguration(): array {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function buildTaskForm(array &$form, FormStateInterface $form_state) {
    return;
  }


}
