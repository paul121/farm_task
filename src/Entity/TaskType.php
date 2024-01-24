<?php

namespace Drupal\farm_task\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the task type entity.
 *
 * @ConfigEntityType(
 *    id = "task_type",
 *    label = @Translation("Task type"),
 *    label_collection = @Translation("Task types"),
 *    label_singular = @Translation("Task type"),
 *    label_plural = @Translation("task types"),
 *    label_count = @PluralTranslation(
 *      singular = "@count task type",
 *      plural = "@count task types",
 *    ),
 *    handlers = {
 *      "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *      "form" = {
 *        "add" = "Drupal\farm_task\Form\TaskTypeForm",
 *        "edit" = "Drupal\farm_task\Form\TaskTypeForm",
 *        "delete" = "Drupal\Core\Entity\EntityDeleteForm",
 *      },
 *      "list_builder" = "Drupal\farm_task\TaskTypeListBuilder",
 *      "route_provider" = {
 *        "default" = "Drupal\entity\Routing\DefaultHtmlRouteProvider",
 *      },
 *    },
 *    admin_permission = "administer task types",
 *    config_prefix = "task_type",
 *    bundle_of = "task",
 *    entity_keys = {
 *      "id" = "id",
 *      "label" = "label",
 *      "uuid" = "uuid"
 *    },
 *    links = {
 *      "canonical" = "/admin/structure/task-type/{task_type}",
 *      "add-form" = "/admin/structure/task-type/add",
 *      "edit-form" = "/admin/structure/task-type/{task_type}/edit",
 *      "delete-form" = "/admin/structure/task-type/{task_type}/delete",
 *      "collection" = "/admin/structure/task-type"
 *    },
 *    config_export = {
 *      "id",
 *      "label",
 *      "description",
 *      "workflow",
 *      "new_revision",
 *    }
 *  )
 */
class TaskType extends ConfigEntityBundleBase implements TaskTypeInterface {

  /**
   * {@inheritdoc}
   */
  public function shouldCreateNewRevision() {
    return $this->new_revision;
  }

}
